<?php

namespace Modules\Report\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Inventory\Entities\Expense;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExpenseDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->editColumn('vehicle_id', function ($query) {
                return $query->vehicle?->name ?? 'N/A';
            })
            ->filterColumn('vehicle_id', function ($query, $keyword) {
                $query->whereHas('vehicle', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%'.$keyword.'%');
                });
            })->orderColumn('vehicle_id', function ($query, $order) {
                $query->orderBy('vehicle_id', $order);
            })
            ->addColumn('vendor_name', function ($query) {
                return $query->vendor?->name ?? 'N/A';
            })
            ->filterColumn('vendor_name', function ($query, $keyword) {
                $query->whereHas('vendor', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%'.$keyword.'%');
                });
            })->orderColumn('vendor_name', function ($query, $order) {
                $query->orderBy('vendor_id', $order);
            })
            ->addColumn('total', function ($query) {
                return $query->total;
            })
            ->editColumn('status', function ($query) {
                return ucfirst($query->status);
            })
            ->setRowId('id')
            ->addIndexColumn()
            ->rawColumns(['status']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Expense $model): QueryBuilder
    {
        $date_from = $this->request()->get('date_from');
        $date_to = $this->request()->get('date_to');
        $vendor = $this->request()->get('vendor_id');

        $code = $this->request()->input('code');

        if ($code) {
            $code = preg_replace('/^'.setting('expense.code_prefix').'/', '', $code);
        } else {
            $code = null;
        }

        $query = $model->newQuery();

        $query->when($date_from, function ($query) use ($date_from) {
            $query->whereDate('date', '>=', $date_from);
        })
            ->when($date_to, function ($query) use ($date_to) {
                $query->whereDate('date', '<=', $date_to);
            })
            ->when($vendor, function ($query) use ($vendor) {
                $query->where('vendor_id', $vendor);
            })
            ->when($code, function ($query) use ($code) {
                $query->where('code', 'like', "%$code%");
            });

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('expense-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->parameters([
                'order' => [],
                'responsive' => true,
                'autoWidth' => false,
                'headerCallback' => 'function(thead, data, start, end, display) {
                    $(thead).addClass("table-themed");
                }',
                'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            ])
            ->buttons([
                Button::make('reset')->className('btn btn-success box-shadow--4dp btn-sm-menu'),
                Button::make('reload')->className('btn btn-success box-shadow--4dp btn-sm-menu'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title(localize('SL'))->searchable(false)->orderable(false)->width(30)->addClass('text-center'),
            Column::make('vehicle_id')->title(localize('Vehicle')),
            Column::make('vendor_name')->title(localize('vendor'))->defaultContent('N/A'),
            Column::make('date')->title(localize('date'))->defaultContent('N/A'),
            Column::make('trip_number')->title(localize('Trip Number'))->defaultContent('N/A'),
            Column::make('odometer_millage')->title(localize('Odometer/Milage'))->defaultContent('N/A'),
            Column::make('code')->title(localize('Invoice'))->defaultContent('N/A'),
            Column::make('total')->title(localize('total'))->defaultContent('N/A'),
            Column::make('status')->title(localize('status'))->defaultContent('N/A'),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'expense-'.date('YmdHis');
    }
}
