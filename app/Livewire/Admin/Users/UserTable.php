<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Throwable;
use Illuminate\Database\QueryException;
use App\Models\Role;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class UserTable extends DataTableComponent
{
    protected $model = User::class;

    protected $listeners = ['confirmDeleteUser' => 'deleteUser'];

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
            ->setEmptyMessage('No users found.')
            ->setTheme('tailwind');
    }

    /**
     *  Filters
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
                        $query->whereHas('roles', fn($q) => $q->where('name', $value));
                    }
                }),

            SelectFilter::make('Name')
                ->options(
                    User::pluck('name', 'name')
                        ->prepend('All', '')
                        ->toArray()
                )
                ->filter(function ($query, $value) {
                    if ($value) {
                        $query->where('name', $value);
                    }
                }),

            SelectFilter::make('Email')
                ->options(
                    User::pluck('email', 'email')
                        ->prepend('All', '')
                        ->toArray()
                )
                ->filter(function ($query, $value) {
                    if ($value) {
                        $query->where('email', $value);
                    }
                }),
        ];
    }

    /**
     *  Individual Delete (with toast + error handling)
     */
    public function deleteUser(int $id): void
    {
        try {
            $user = User::find($id);

            if (!$user) {
                $this->dispatch('toast', message: 'User not found.', type: 'error');
                return;
            }

            // Prevent deleting self or protected users (optional)
            if (auth()->id() === $user->id) {
                $this->dispatch('toast', message: 'You cannot delete your own account.', type: 'warning');
                return;
            }

            $userName = $user->name;
            $user->delete();

            $this->setPage(1);
            $this->dispatch('$refresh');

            $this->dispatch('toast', message: "User '{$userName}' deleted successfully!", type: 'success');

        } catch (QueryException $e) {
            logger()->error('Database error while deleting user', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred while deleting user.', type: 'error');

        } catch (Throwable $e) {
            logger()->error('Unexpected error deleting user', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'An unexpected error occurred while deleting user.', type: 'error');
        }
    }

    /**
     *  Bulk Delete (with consistent toasts)
     */
    public function deleteSelected(): void
    {
        try {
            $selected = $this->getSelected();
            $count = count($selected);

            if ($count === 0) {
                $this->dispatch('toast', message: 'No users selected.', type: 'warning');
                return;
            }

            $users = User::whereIn('id', $selected)->get();
            $deletedCount = 0;

            foreach ($users as $user) {
                if (auth()->id() === $user->id) {
                    continue; // skip self
                }
                $user->delete();
                $deletedCount++;
            }

            $this->clearSelected();
            $this->setPage(1);
            $this->dispatch('$refresh');

            if ($deletedCount > 0) {
                $this->dispatch('toast', message: "{$deletedCount} user(s) deleted successfully!", type: 'success');
            } else {
                $this->dispatch('toast', message: 'No users were deleted (possibly skipped protected/self).', type: 'info');
            }

        } catch (QueryException $e) {
            logger()->error('Database error while bulk deleting users', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred while deleting users.', type: 'error');

        } catch (Throwable $e) {
            logger()->error('Unexpected error bulk deleting users', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'An unexpected error occurred during bulk delete.', type: 'error');
        }
    }

    /**
     *  Columns
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

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Roles')
                ->label(fn($row) => view('livewire.admin.users.partials.roles-badges', [
                    'roles' => $row->roles->pluck('name'),
                ])->render())
                ->html(),

            Column::make('Created At', 'created_at')
                ->sortable()
                ->format(fn($value) => $value->format('M d, Y')),

            Column::make('Updated At', 'updated_at')
                ->sortable()
                ->collapseOnMobile(),

            Column::make('Actions')
                ->label(fn($row) => view('livewire.admin.users.partials.actions', ['user' => $row])->render())
                ->html(),
        ];
    }
}
