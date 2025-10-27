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
     * Bulk action handler
     */
    public function deleteSelected(): void
    {
        $count = $this->getSelected()->count();

        if ($count === 0) {
            $this->dispatch('notify', type: 'warning', message: 'No users selected.');
            return;
        }

        User::whereIn('id', $this->getSelected())->delete();

        $this->clearSelected();

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
                        ->map(fn($name) => "<span class='px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'>{$name}</span>")
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
