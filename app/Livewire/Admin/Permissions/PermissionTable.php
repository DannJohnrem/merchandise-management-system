<?php

namespace App\Livewire\Admin\Permissions;

use App\Models\Permission;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Illuminate\Database\QueryException;
use Throwable;

class PermissionTable extends DataTableComponent
{
    protected $model = Permission::class;

    protected $listeners = ['confirmDeletePermission' => 'deletePermission'];

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
            ->setEmptyMessage('No permissions found.')
            ->setTheme('tailwind');
    }

    /**
     * 🔍 Filters
     */
    public function filters(): array
    {
        return [
            SelectFilter::make('Permission')
                ->options(
                    Permission::pluck('name', 'name')
                        ->prepend('All', '')
                        ->toArray()
                )
                ->filter(fn($query, $value) => $value ? $query->where('name', $value) : null),

            SelectFilter::make('Guard Name')
                ->options(
                    Permission::pluck('guard_name', 'guard_name')
                        ->prepend('All', '')
                        ->toArray()
                )
                ->filter(fn($query, $value) => $value ? $query->where('guard_name', $value) : null),
        ];
    }

    /**
     * 🗑️ Individual Delete (with toast)
     */
    public function deletePermission(int $id): void
    {
        try {
            $permission = Permission::find($id);

            if (!$permission) {
                $this->dispatch('toast', message: 'Permission not found.', type: 'error');
                return;
            }

            // Prevent deleting core permissions
            if (in_array(strtolower($permission->name), [
                'manage roles', 'manage permissions', 'super-admin'
            ])) {
                $this->dispatch('toast', message: 'This permission cannot be deleted.', type: 'warning');
                return;
            }

            $name = $permission->name;
            $permission->delete();

            $this->setPage(1);
            $this->dispatch('$refresh');

            $this->dispatch('toast', message: "Permission '{$name}' deleted successfully!", type: 'success');

        } catch (QueryException $e) {
            logger()->error('Database error while deleting permission', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred while deleting permission.', type: 'error');

        } catch (Throwable $e) {
            logger()->error('Unexpected error deleting permission', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'An unexpected error occurred while deleting permission.', type: 'error');
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
                $this->dispatch('toast', message: 'No permissions selected.', type: 'warning');
                return;
            }

            $permissions = Permission::whereIn('id', $selected)->get();
            $deletedCount = 0;

            foreach ($permissions as $permission) {
                if (in_array(strtolower($permission->name), [
                    'manage roles', 'manage permissions', 'super-admin'
                ])) {
                    continue;
                }
                $permission->delete();
                $deletedCount++;
            }

            $this->clearSelected();
            $this->setPage(1);
            $this->dispatch('$refresh');

            if ($deletedCount > 0) {
                $this->dispatch('toast', message: "{$deletedCount} permission(s) deleted successfully!", type: 'success');
            } else {
                $this->dispatch('toast', message: 'No permissions were deleted (protected permissions skipped).', type: 'info');
            }

        } catch (QueryException $e) {
            logger()->error('Database error while bulk deleting permissions', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred while deleting permissions.', type: 'error');

        } catch (Throwable $e) {
            logger()->error('Unexpected error bulk deleting permissions', ['error' => $e->getMessage()]);
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

            Column::make('Guard Name', 'guard_name')
                ->sortable()
                ->collapseOnMobile(),

            Column::make('Created At', 'created_at')
                ->sortable()
                ->format(fn($value) => $value->format('M d, Y')),

            Column::make('Updated At', 'updated_at')
                ->sortable()
                ->collapseOnMobile(),

            Column::make('Actions')
                ->label(fn($row) => view('livewire.admin.permissions.partials.actions', ['permission' => $row])->render())
                ->html(),
        ];
    }
}
