<?php

namespace App\Livewire\Admin\Roles;

use App\Models\Role;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Throwable;
use Illuminate\Database\QueryException;

class RoleTable extends DataTableComponent
{
    public string $tableName = 'role-table';
    protected $model = Role::class;

    protected $listeners = ['confirmDeleteRole' => 'deleteRole'];

    public array $bulkActions = [
        'deleteSelected' => '🗑️ Delete Selected',
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setPerPage(10)
            ->setPerPageAccepted([10, 25, 50, 100])
            ->setDefaultSort('created_at', 'desc')
            ->setColumnSelectDisabled(false)
            ->setSearchDebounce(300)
            ->setBulkActionsEnabled()
            ->setEmptyMessage('No roles found.')
            ->setTheme('tailwind');
    }

    /**
     * 🔍 Filters
     */
    public function filters(): array
    {
        return [
            SelectFilter::make('Role')
                ->options(
                    Role::pluck('name', 'name')
                        ->prepend('All', '')
                        ->toArray()
                )
                ->filter(function ($query, $value) {
                    if ($value) {
                        $query->where('name', $value);
                    }
                }),

            SelectFilter::make('Guard Name')
                ->options(
                    Role::pluck('guard_name', 'guard_name')
                        ->prepend('All', '')
                        ->toArray()
                )
                ->filter(function ($query, $value) {
                    if ($value) {
                        $query->where('guard_name', $value);
                    }
                }),
        ];
    }

    /**
     * 🗑️ Individual Delete (with toast)
     */
    public function deleteRole(int $id): void
    {
        try {
            $role = Role::find($id);

            if (!$role) {
                $this->dispatch('toast', message: 'Role not found.', type: 'error');
                return;
            }

            // Prevent deleting "super-admin" or similar protected roles
            if (in_array(strtolower($role->name), ['super admin', 'administrator'])) {
                $this->dispatch('toast', message: 'This role cannot be deleted.', type: 'warning');
                return;
            }

            $roleName = $role->name;
            $role->delete();

            $this->setPage(1);
            $this->dispatch('$refresh');

            $this->dispatch('toast', message: "Role '{$roleName}' deleted successfully!", type: 'success');

        } catch (QueryException $e) {
            logger()->error('Database error while deleting role', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred while deleting role.', type: 'error');

        } catch (Throwable $e) {
            logger()->error('Unexpected error deleting role', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'An unexpected error occurred while deleting role.', type: 'error');
        }
    }

    /**
     * 🗑️ Bulk Delete (with toast)
     */
    public function deleteSelected(): void
    {
        try {
            $selected = $this->getSelected();
            $count = count($selected);

            if ($count === 0) {
                $this->dispatch('toast', message: 'No roles selected.', type: 'warning');
                return;
            }

            $roles = Role::whereIn('id', $selected)->get();
            $deletedCount = 0;

            foreach ($roles as $role) {
                if (in_array(strtolower($role->name), ['super admin', 'administrator'])) {
                    continue; // skip protected roles
                }
                $role->delete();
                $deletedCount++;
            }

            $this->clearSelected();
            $this->setPage(1);
            $this->dispatch('$refresh');

            if ($deletedCount > 0) {
                $this->dispatch('toast', message: "{$deletedCount} role(s) deleted successfully!", type: 'success');
            } else {
                $this->dispatch('toast', message: 'No roles were deleted (protected roles skipped).', type: 'info');
            }

        } catch (QueryException $e) {
            logger()->error('Database error while bulk deleting roles', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred while deleting roles.', type: 'error');

        } catch (Throwable $e) {
            logger()->error('Unexpected error bulk deleting roles', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'An unexpected error occurred during bulk delete.', type: 'error');
        }
    }

    /**
     * 📊 Columns
     */
    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Guard', 'guard_name')
                ->sortable()
                ->collapseOnMobile(),

            Column::make('Permissions')
                ->label(fn($row) => view('livewire.admin.roles.partials.permissions-badges', [
                    'permissions' => $row->permissions->pluck('name'),
                ])->render())
                ->html(),

            Column::make('Created At', 'created_at')
                ->sortable()
                ->format(fn($value) => $value->format('M d, Y')),

            Column::make('Updated At', 'updated_at')
                ->sortable()
                ->collapseOnMobile(),

            Column::make('Actions')
                ->label(fn($row) => view('livewire.admin.roles.partials.actions', ['role' => $row])->render())
                ->html(),
        ];
    }
}
