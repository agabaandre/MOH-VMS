<?php

namespace Modules\Report\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Employee\Entities\Driver;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DriverDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('is_active', function ($query) {
                $status = $query->is_active == 1 ? 'Active' : 'Inactive';
                return $status; // Remove HTML for clean Excel export
            })
            ->editColumn('leave_status', function ($query) {
                $status = $query->leave_status == 1 ? 'On Leave' : 'Working';
                return $status; // Remove HTML for clean Excel export
            })
            ->editColumn('avatar_path', function ($query) {
                return $query->avatar_url ? 'Has Image' : 'No Image'; // Simplified for Excel
            })
            ->editColumn('created_at', function ($query) {
                return $query->created_at->format('Y-m-d H:i:s');
            })
            ->editColumn('updated_at', function ($query) {
                return $query->updated_at->format('Y-m-d H:i:s');
            })
            ->setRowId('id')
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Driver $model): QueryBuilder
    {
        $driver = $this->request()->get('driver_id');
        $phone = $this->request()->get('mobile');
        $vehicle = $this->request()->get('vehicle_id');
        $leave_status = $this->request()->get('leave_status');
        $join_date_from = $this->request()->get('join_date_from');
        $join_date_to = $this->request()->get('join_date_to');

        $query = $model->newQuery();

        $query->when($driver, function ($query) use ($driver) {
            $query->where('id', $driver);
        });

        $query->when($phone, function ($query) use ($phone) {
            $query->where('phone', 'like', '%'.$phone.'%');
        });

        $query->when($vehicle, function ($query) use ($vehicle) {
            $query->where('vehicle_id', $vehicle);
        });

        $query->when($leave_status, function ($query) use ($leave_status) {
            $query->where('leave_status', $leave_status);
        });

        $query->when($join_date_from, function ($query) use ($join_date_from) {
            $query->where('joining_date', '>=', $join_date_from);
        });

        $query->when($join_date_to, function ($query) use ($join_date_to) {
            $query->where('joining_date', '<=', $join_date_to);
        });

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('driver-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->orderBy(1)
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'headerCallback' => 'function(thead, data, start, end, display) {
                    $(thead).addClass("table-themed");
                }',
                'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            ])
            ->buttons([
                Button::make('excel')->className('btn btn-success box-shadow--4dp btn-sm-menu'),
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
            Column::make('driver_code')->title(localize('Driver Code'))->defaultContent('N/A'),
            Column::make('name')->title(localize('Driver Name'))->defaultContent('N/A'),
            Column::make('phone')->title(localize('Mobile'))->defaultContent('N/A'),
            Column::make('license_type_id')->title(localize('License Type'))->defaultContent('N/A'),
            Column::make('license_num')->title(localize('License Number'))->defaultContent('N/A'),
            Column::make('license_issue_date')->title(localize('License Issue Date'))->defaultContent('N/A'),
            Column::make('license_expiry_date')->title(localize('License Expiry Date'))->defaultContent('N/A'),
            Column::make('authorization_code')->title(localize('Authorization Code'))->defaultContent('N/A'),
            Column::make('nid')->title(localize('NIN'))->defaultContent('N/A'),
            Column::make('dob')->title(localize('Date of Birth'))->defaultContent('N/A'),
            Column::make('joining_date')->title(localize('Joining Date'))->defaultContent('N/A'),
            Column::make('working_time_slot')->title(localize('Working Time'))->defaultContent('N/A'),
            Column::make('leave_status')->title(localize('Leave Status'))->defaultContent('N/A'),
            Column::make('present_address')->title(localize('Present Address'))->defaultContent('N/A'),
            Column::make('permanent_address')->title(localize('Permanent Address'))->defaultContent('N/A'),
            Column::make('avatar_path')->title(localize('Photo'))->defaultContent('N/A'),
            Column::make('is_active')->title(localize('Status'))->defaultContent('N/A'),
            Column::make('employee_id')->title(localize('Employee ID'))->defaultContent('N/A'),
            Column::make('created_at')->title(localize('Created At'))->defaultContent('N/A'),
            Column::make('updated_at')->title(localize('Updated At'))->defaultContent('N/A'),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'Driver_'.date('YmdHis');
    }
}
