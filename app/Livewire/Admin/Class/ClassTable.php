<?php

namespace App\Livewire\Admin\Class;

use App\Models\ClassModel;
use Throwable;
use Illuminate\Database\QueryException;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ClassTable extends DataTableComponent
{
    protected $model = ClassModel::class;

    protected $listeners = ['confirmDeleteClass' => 'deleteClass'];

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
            ->setEmptyMessage('No classes found.')
            ->setTheme('tailwind');
    }

    /**
     *  Filters
     */
    public function filters(): array
    {
        return [
            SelectFilter::make('Type')
                ->options(
                    ClassModel::pluck('type', 'type')
                        ->prepend('All', '')
                        ->toArray()
                )
                ->filter(fn($query, $value) =>
                    $value ? $query->where('type', $value) : null
                ),

            SelectFilter::make('Department')
                ->options(
                    ClassModel::pluck('department', 'department')
                        ->prepend('All', '')
                        ->toArray()
                )
                ->filter(fn($query, $value) =>
                    $value ? $query->where('department', $value) : null
                ),
        ];
    }

    /**
     *  Individual Delete
     */
    public function deleteClass(int $id): void
    {
        try {
            $class = ClassModel::find($id);

            if (!$class) {
                $this->dispatch('toast', message: 'Class not found.', type: 'error');
                return;
            }

            $name = $class->name;
            $class->delete();

            $this->setPage(1);
            $this->dispatch('$refresh');

            $this->dispatch('toast', message: "Class '{$name}' deleted successfully!", type: 'success');

        } catch (QueryException $e) {
            logger()->error('Database error deleting class', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred while deleting class.', type: 'error');

        } catch (Throwable $e) {
            logger()->error('Unexpected error deleting class', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'An unexpected error occurred.', type: 'error');
        }
    }

    /**
     *  Bulk Delete
     */
    public function deleteSelected(): void
    {
        try {
            $selected = $this->getSelected();
            $count = count($selected);

            if ($count === 0) {
                $this->dispatch('toast', message: 'No classes selected.', type: 'warning');
                return;
            }

            $rows = ClassModel::whereIn('id', $selected)->get();
            $deletedCount = 0;

            foreach ($rows as $class) {
                $class->delete();
                $deletedCount++;
            }

            $this->clearSelected();
            $this->setPage(1);
            $this->dispatch('$refresh');

            $this->dispatch('toast', message: "{$deletedCount} class(es) deleted successfully!", type: 'success');

        } catch (QueryException $e) {
            logger()->error('Database error bulk deleting classes', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred.', type: 'error');

        } catch (Throwable $e) {
            logger()->error('Unexpected bulk delete error', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'An unexpected error occurred during bulk delete.', type: 'error');
        }
    }

    /**
     *  Columns
     */
    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->sortable()
                ->searchable(),

            Column::make("Name", "name")
                ->sortable()
                ->searchable(),

            Column::make("Type", "type")
                ->sortable()
                ->searchable()
                ->collapseOnMobile(),

            Column::make("Code", "code")
                ->sortable()
                ->searchable()
                ->collapseOnMobile(),

            Column::make("Department", "department")
                ->sortable()
                ->searchable()
                ->collapseOnMobile(),

            Column::make("Position", "position")
                ->sortable()
                ->searchable()
                ->collapseOnMobile(),

            Column::make("Created At", "created_at")
                ->sortable()
                ->format(fn($value) => $value->format('M d, Y')),

            Column::make("Actions")
                ->label(fn($row) =>
                    view('livewire.admin.class.partials.actions', [
                        'class' => $row
                    ])->render()
                )
                ->html(),
        ];
    }
}
