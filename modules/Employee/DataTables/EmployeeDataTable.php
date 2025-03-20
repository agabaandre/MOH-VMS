<?php

namespace Modules\Employee\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Employee\Entities\Employee;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmployeeDataTable extends DataTable
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
                $button .= '<a href="javascript:void(0);" class="btn btn-success-soft btn-sm m-1" title="Edit" onclick="'."axiosModal('".route(\config('theme.rprefix').'.edit', $query->id).'\')"><i class="fa fa-edit"></i></a>';
                $button .= '<a href="javascript:void(0);" class="btn btn-danger-soft btn-sm mx-1" onclick="delete_modal(\''.route(\config('theme.rprefix').'.destroy', $query->id).'\')"  title="Delete"><i class="fa fa-trash"></i></a>';
                $button .= '</div>';
                return $button;
            })
            ->editColumn('department', function ($query) {
                return $query->department?->name;
            })->filterColumn('department', function ($query, $keyword) {
                $query->whereHas('department', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%'.$keyword.'%');
                });
            })->orderColumn('department', function ($query, $order) {
                $query->orderBy('department_id', $order);
            })
            ->editColumn('position', function ($query) {
                return $query->position?->name;
            })->filterColumn('position', function ($query, $keyword) {
                $query->whereHas('position', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%'.$keyword.'%');
                });
            })->orderColumn('position', function ($query, $order) {
                $query->orderBy('position_id', $order);
            })
            ->editColumn('facility', function ($query) {
                return $query->facility?->name;
            })->filterColumn('facility', function ($query, $keyword) {
                $query->whereHas('facility', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%'.$keyword.'%');
                });
            })->orderColumn('facility', function ($query, $order) {
                $query->orderBy('facility_id', $order);
            })
            ->editColumn('dob', function ($query) {
                return $query->dob ? $query->dob->format('d-m-Y') : 'N/A';
            })
            ->editColumn('created_at', function ($query) {
                return $query->created_at ? $query->created_at->format('d-m-Y H:i') : 'N/A';
            })
            ->editColumn('updated_at', function ($query) {
                return $query->updated_at ? $query->updated_at->format('d-m-Y H:i') : 'N/A';
            })
            ->addColumn('avatar', function ($query) {
                return $query->avatar_path 
                    ? '<img src="'.$query->avatar_url.'" alt="'.$query->name.'" class="rounded-circle avatar-sm">' 
                    : '<span class="avatar-sm rounded-circle bg-primary">'.\substr($query->name, 0, 1).'</span>';
            })
            ->rawColumns(['action', 'avatar'])
            ->setRowId('id')
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Employee $model): QueryBuilder
    {
        $employee_type = $this->request()->get('employee_type');
        $department_id = $this->request()->get('department');
        $position_id = $this->request()->get('designation');
        $blood_group = $this->request()->get('blood_group');
        $join_date_from = $this->request()->get('join_date_from');
        $join_date_to = $this->request()->get('join_date_to');
        $facility_id = $this->request()->get('facility');

        // Add eager loading for relationships
        $query = $model->newQuery()->with(['department', 'position', 'facility']);

        $query->when($department_id, function ($query) use ($department_id) {
            return $query->where('department_id', $department_id);
        });

        $query->when($position_id, function ($query) use ($position_id) {
            return $query->where('position_id', $position_id);
        });

        $query->when($blood_group, function ($query) use ($blood_group) {
            return $query->where('blood_group', $blood_group);
        });

        $query->when($facility_id, function ($query) use ($facility_id) {
            return $query->where('facility_id', $facility_id);
        });

        $query->when($join_date_from, function ($query) use ($join_date_from) {
            return $query->where('join_date', '>=', $join_date_from);
        });
        $query->when($join_date_to, function ($query) use ($join_date_to) {
            return $query->where('join_date', '<=', $join_date_to);
        });

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('employee-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->parameters([
                'order' => [],
                'responsive' => true,
                'autoWidth' => false,
                'scrollX' => true,
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
            Column::computed('avatar')->title(localize('Photo'))->orderable(false)->width(60)->addClass('text-center'),
            Column::make('id')->title(localize('ID'))->defaultContent('N/A'),
            Column::make('employee_code')->title(localize('Employee ID'))->defaultContent('N/A'),
            Column::make('name')->title(localize('Name'))->defaultContent('N/A'),
            Column::make('department')->title(localize('Department'))->defaultContent('N/A'),
            Column::make('position')->title(localize('Designation'))->defaultContent('N/A'),
            Column::make('facility')->title(localize('Facility'))->defaultContent('N/A'),
            Column::make('nid')->title(localize('NID'))->defaultContent('N/A'),
            Column::make('card_number')->title(localize('Card Number'))->defaultContent('N/A'),
            Column::make('phone')->title(localize('Phone'))->defaultContent('N/A'),
            Column::make('email')->title(localize('Email'))->defaultContent('N/A'),
            Column::make('dob')->title(localize('Date of Birth'))->defaultContent('N/A'),
            Column::make('present_contact')->title(localize('Present Contact'))->defaultContent('N/A'),
            Column::make('present_address')->title(localize('Present Address'))->defaultContent('N/A'),
            Column::make('present_city')->title(localize('Present City'))->defaultContent('N/A'),
            Column::make('contact_person_name')->title(localize('Contact Person'))->defaultContent('N/A'),
            Column::make('contact_person_mobile')->title(localize('Contact Person Mobile'))->defaultContent('N/A'),
            Column::make('reference_name')->title(localize('Reference Name'))->defaultContent('N/A'),
            Column::make('reference_email')->title(localize('Reference Email'))->defaultContent('N/A'),
            Column::make('reference_address')->title(localize('Reference Address'))->defaultContent('N/A'),
            Column::make('created_at')->title(localize('Created At'))->defaultContent('N/A'),
            Column::make('updated_at')->title(localize('Updated At'))->defaultContent('N/A'),
            Column::computed('action')->title(localize('Action'))
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->printable(false)
                ->width(80)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'Employee_'.date('YmdHis');
    }
}
