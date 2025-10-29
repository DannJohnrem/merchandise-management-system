<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class UserTable extends DataTableComponent
{
    protected $model = User::class;

    protected $listeners = ['confirmDelete' => 'deleteUser'];

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
     * Filters
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
     * 🔹 Individual Delete (Model Binding)
     */
    public function deleteUser(int $id): void
    {
        $user = User::find($id);

        if (! $user) {
            $this->dispatch('notify', type: 'error', message: 'User not found.');
            return;
        }

        $user->delete();

        $this->setPage(1);
        $this->dispatch('$refresh');

        $this->dispatch('notify', type: 'success', message: "User {$user->name} deleted successfully.");
    }

    /**
     * 🔹 Bulk Delete
     */
    public function deleteSelected(): void
    {
        $selected = $this->getSelected();
        $count = count($selected);

        if ($count === 0) {
            $this->dispatch('notify', type: 'warning', message: 'No users selected.');
            return;
        }

        // Delete users
        User::whereIn('id', $selected)->get()->each->delete();

        // Clear selection and refresh the table
        $this->clearSelected();
        $this->setPage(1);
        $this->dispatch('$refresh'); // ✅ triggers Livewire re-render safely

        // Notify success
        $this->dispatch('notify', type: 'success', message: "{$count} user(s) deleted successfully.");
    }

    /**
     * Columns
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
                ->label(fn($row) => $row->roles->isEmpty()
                    ? "<span class='px-2 py-1 text-xs text-gray-500'>No role yet</span>"
                    : $row->roles->pluck('name')
                        ->map(fn($name) => "<flux:badge color='sky'>{$name}</flux:badge>")
                        ->implode(' '))
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
