<?php

namespace Modules\Inventory\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Inventory\Entities\InventoryPartsUsage;
use Modules\Purchase\Entities\Purchase;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InventoryPartsUsageDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $button = '<div class="align-items-center">';
                $button .= '<a href="javascript:void(0);" class="btn btn-info-soft btn-sm m-1" title="View" onclick="'."axiosModal('".route(\config('theme.rprefix').'.show', $query->id).'\')"><i class="fa fa-eye"></i></a>';
                $button .= '<a href="'.route(\config('theme.rprefix').'.edit', $query->id).'" class="btn btn-success-soft btn-sm m-1" title="Edit" ><i class="fa fa-edit"></i></a>';
                $button .= '<a href="javascript:void(0);" class="btn btn-danger-soft btn-sm mx-1" onclick="delete_modal(\''.route(\config('theme.rprefix').'.destroy', $query->id).'\')"  title="Delete"><i class="fa fa-trash"></i></a>';
                $button .= '</div>';

                return $button;
            })
            ->addColumn('vehicle_name', function ($query) {
                return $query->vehicle?->name;
            })
            ->editColumn('status', function ($query) {
                return $this->statusBtn($query);
            })
            ->setRowId('id')
            ->addIndexColumn()
            ->rawColumns(['status', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(InventoryPartsUsage $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('parts-usage-table')
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
            Column::make('code')->title(localize('code'))->defaultContent('N/A'),
            Column::make('vehicle_name')->title(localize('vehicle'))->defaultContent('N/A'),
            Column::make('date')->title(localize('date'))->defaultContent('N/A'),
            Column::make('updated_at')->title(localize('Updated'))->defaultContent('N/A'),
            Column::make('status')->title(localize('status'))->defaultContent('N/A'),
            Column::computed('action')
                ->title(localize('Action'))
                ->searchable(false)
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'parts-usage-'.date('YmdHis');
    }

    private function statusBtn($i): string
    {
        $status = '<select class="form-control" name="status" id="status_id_'.$i->id.'" ';
        $status .= 'onchange="userStatusUpdate(\''.route(config('theme.rprefix').'.status-update', $i->id).'\','.$i->id.',\''.$i->status.'\')">';

        foreach (Purchase::getStatues() as $key => $value) {
            $selected = $i->status == $key ? 'selected' : '';
            $status .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }

        $status .= '</select>';

        return $status;
    }
}
