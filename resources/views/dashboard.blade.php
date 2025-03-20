<x-app-layout>
    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <!-- Vehicle Stats -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 stats-card">
                <div class="card-header bg-yellow border-bottom">
                    <h6 class="fs-17 fw-bold mb-0">@localize('Vehicle Stats')</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        <div>
                            <a class="text-themed">@localize('Total Vehicles')
                                <span class="float-end text-themed"><strong>{{ $vehicleStats['total'] }}</strong></span>
                            </a>
                        </div>
                        <div>
                            <a class="text-themed">@localize('Active Vehicles')
                                <span class="float-end text-themed"><strong>{{ $vehicleStats['active'] }}</strong></span>
                            </a>
                        </div>
                        <div>
                            <a class="text-themed">@localize('Off-Boarded Vehicles')
                                <span class="float-end text-themed"><strong>{{ $vehicleStats['offboarded'] }}</strong></span>
                            </a>
                        </div>
                        <div>&nbsp;</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Activities -->
        @if (can('vehicle_requisition_management') || can('vehicle_maintenance_management') || can('inventory_stock_management'))
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 stats-card">
                <div class="card-header bg-yellow border-bottom">
                    <h6 class="fs-17 fw-bold mb-0">@localize('Vehicles')</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        @can('vehicle_requisition_management')
                            <div>
                                <a class="text-themed"
                                    href="{{ route('admin.vehicle.requisition.index') }}">@localize('On Requisition')<span
                                        class="float-end text-themed"><strong>{{ $total_requisitions }}</strong></span></a>
                            </div>
                        @endcan
                        @can('vehicle_maintenance_management')
                            <div>
                                <a class="text-themed" href="{{ route('admin.vehicle.maintenance.index') }}">
                                    @localize('On Maintenance') <span
                                        class="float-end text-themed"><strong>{{ $total_maintenances }}</strong></span></a>
                            </div>
                        @endcan
                        @can('inventory_stock_management')
                            <div>
                                <a class="text-themed"
                                    href="{{ route('admin.inventory.stock.index') }}">@localize('Available')
                                    <span class="float-end text-themed"><strong>{{ $available }}</strong></span></a>
                            </div>
                        @endcan
                        <div>
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Today's Requisition -->
        @if (can('vehicle_requisition_management') || can('pick_drop_requisition') || can('vehicle_maintenance_management') || can('refueling_requisition_management'))
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 stats-card">
                <div class="card-header bg-yellow border-bottom">
                    <h6 class="fs-17 fw-bold mb-0">@localize('Todays Requisition')</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        @can('vehicle_requisition_management')
                            <div>
                                <a class="text-themed"
                                    href="{{ route('admin.vehicle.requisition.index') }}">@localize('Vehicle Requisition') <span
                                        class="float-end text-themed"><strong>{{ $todays_requisitions }}</strong></span></a>
                            </div>
                        @endcan
                        @can('pick_drop_requisition')
                            <div>
                                <a class="text-themed"
                                    href="{{ route('admin.vehicle.pick-drop.index') }}">@localize('Pick & Drop Requisition') <span
                                        class="float-end text-themed"><strong>{{ $todays_pick_drops }}</strong></span></a>
                            </div>
                        @endcan
                        @can('vehicle_maintenance_management')
                            <div>
                                <a class="text-themed"
                                    href="{{ route('admin.vehicle.maintenance.index') }}">@localize('Maintenance Requisition')<span
                                        class="float-end text-themed"><strong>{{ $todays_maintenances }}</strong></span></a>
                            </div>
                        @endcan
                        @can('refueling_requisition_management')
                            <div>
                                <a class="text-themed"
                                    href="{{ route('admin.refueling.requisition.index') }}">@localize('Fuel Requisition')<span
                                        class="float-end text-themed"><strong>{{ $todays_fuel_requisitions }}</strong></span></a>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Reminder -->
        @if (can('legal_document_management'))
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 stats-card">
                <div class="card-header bg-yellow border-bottom">
                    <h6 class="fs-17 fw-bold mb-0">@localize('Reminder')</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        @can('legal_document_management')
                            <div>
                                <a class="text-themed"
                                    href="{{ route('admin.vehicle.legal-document.index') }}">@localize('Legal Doc Soon Expire') <span
                                        class="float-end text-themed"><strong>{{ $doc_expire_soon }}</strong></span></a>
                            </div>
                            <div>
                                <a class="text-themed"
                                    href="{{ route('admin.vehicle.legal-document.index') }}">@localize('Legal Doc Expired') <span
                                        class="float-end text-themed"><strong>{{ $doc_expired }}</strong></span></a>
                            </div>
                        @endcan
                        <div>
                            &nbsp;
                        </div>
                        <div>
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Driver Stats -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 stats-card">
                <div class="card-header bg-yellow border-bottom">
                    <h6 class="fs-17 fw-bold mb-0">@localize('Driver Stats')</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        <div>
                            <a class="text-themed">@localize('Total Drivers')
                                <span class="float-end text-themed"><strong>{{ $totalDrivers }}</strong></span>
                            </a>
                        </div>
                        <div>
                            <a class="text-themed">@localize('Total Employees')
                                <span class="float-end text-themed"><strong>{{ $totalEmployees }}</strong></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fleet Utilization Stats -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 stats-card">
                <div class="card-header bg-yellow border-bottom">
                    <h6 class="fs-17 fw-bold mb-0">@localize('Fleet Utilization')</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        <div>
                            <a class="text-themed">@localize('Vehicles In Use')
                                <span class="float-end text-themed"><strong>{{ $fleetUtilization['vehicles_in_use'] }}</strong></span>
                            </a>
                        </div>
                        <div>
                            <a class="text-themed">@localize('Utilization Rate')
                                <span class="float-end text-themed"><strong>{{ $fleetUtilization['utilization_rate'] }}%</strong></span>
                            </a>
                        </div>
                        <div>
                            <a class="text-themed">@localize('Idle Vehicles')
                                <span class="float-end text-themed"><strong>{{ $fleetUtilization['idle_vehicles'] }}</strong></span>
                            </a>
                        </div>
                        <div>&nbsp;</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Purchase Stats (replacing Fuel Efficiency Stats) -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 stats-card">
                <div class="card-header bg-yellow border-bottom">
                    <h6 class="fs-17 fw-bold mb-0">@localize('Inventory Purchases')</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        <div>
                            <a class="text-themed">@localize('Total Purchases')
                                <span class="float-end text-themed"><strong>{{ $purchaseStats['total_purchases'] }}</strong></span>
                            </a>
                        </div>
                        <div>
                            <a class="text-themed">@localize('Recent Purchases (30 days)')
                                <span class="float-end text-themed"><strong>{{ $purchaseStats['recent_purchases'] }}</strong></span>
                            </a>
                        </div>
                        <div>
                            <a class="text-themed">@localize('Average Order Value')
                                <span class="float-end text-themed"><strong>{{ round($purchaseStats['avg_order_value'], 2) }}</strong></span>
                            </a>
                        </div>
                        <div>&nbsp;</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other Activities -->
        @if (can('inventory_stock_management'))
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 stats-card">
                <div class="card-header bg-yellow border-bottom">
                    <h6 class="fs-17 fw-bold mb-0">@localize('Others Activities')</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        @can('inventory_stock_management')
                            <div>
                                <a class="text-themed"
                                    href="{{ route('admin.inventory.stock.index') }}">@localize('Stock In')
                                    <span class="float-end text-themed"><strong>{{ $totalStockIn }}</strong></span>
                                </a>
                            </div>
                            <div>
                                <a class="text-themed" href="{{ route('admin.inventory.stock.index') }}">
                                    @localize('Stock Out')
                                    <span class="float-end text-themed"><strong>{{ $totalStockOut }}</strong></span>
                                </a>
                            </div>
                        @endcan
                        <div>
                            &nbsp;
                        </div>
                        <div>
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        @can('maintenance_report')
        <div class="col-12 col-xl-8">
            <div class="card h-100">
                <div class="card-header bg-yellow border-bottom">
                    <h6 class="fs-16 fw-bold mb-0">@localize('Last 12 Month Maintenance Cost Report')</h6>
                </div>
                <div class="card-body">
                    <div id="line_chart" class="h-100" data-chat-data='@json($data['line_chart'] ?? [])' data-name='@localize('Maintenance Cost')'></div>
                </div>
            </div>
        </div>
        @endcan

        @can('expense_report')
        <div class="col-12 col-xl-4">
            <div class="card h-100">
                <div class="card-header bg-yellow border-bottom">
                    <h6 class="fs-16 fw-bold mb-0">@localize('Last 12 Month Expense Report')</h6>
                </div>
                <div class="card-body">
                    <div id="doughnut_chart" class="h-100" data-chat-data='@json($data['doughnut_chart'] ?? [])' data-name='@localize('Expense Report')'></div>
                </div>
            </div>
        </div>
        @endcan
    </div>

    <!-- 2nd  -->
    @can('vehicle_requisition_report')
        <div class="row mb-4">
            <div class="col-xl-4 mb-4 mb-xl-0">
                <div class="card rounded-0">
                    <div class="card-header bg-yellow card_header px-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fs-16 fw-bold mb-0">@localize('Last 12 Month Vehicle Requisition Report')</h6>
                            </h6>
                        </div>
                    </div>
                    <div class="card-body w-100 p-0 px-2">
                        <div id="venn_diagram" data-chat-data='@json($data['venn_diagram'] ?? [])'
                            data-name='@localize('Vehicle Requisition Report')'></div>
                    </div>

                </div>
            </div>
            <div class="col-xl-8">
                <div class="card rounded-0">
                    <div class="card-header bg-yellow card_header px-3">
                        <div class="d-lg-flex justify-content-between align-items-center">
                            <h6 class="fs-16 fw-bold mb-0">@localize('Last 12 Month Requisition Report')</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="multi_axis_line" data-chat-data='@json($data['multi_axis_line'] ?? [])'
                            data-name='@localize('Vehicle Requisition Report')'></div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    <!-- new chart.js End -->
    @can('legal_document_management')
        <div class="card mb-4">
            <div class="card-header bg-yellow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">@localize('Reminder')</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-borderless table-hover rounded-3 table-light">
                        <thead>
                            <tr>
                                <th class="py-3">@localize('Vehicle No.')</th>
                                <th class="py-3">@localize('Document name')</th>
                                <th class="py-3">@localize('Expiration Date')</th>
                                <th class="py-3">@localize('Renewal Date')</th>
                                <th class="py-3 text-center">@localize('Current Status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reminders as $doc)
                                <tr>
                                    <td class="py-3">{{ $doc->vehicle?->name }}</td>
                                    <td class="py-3">{{ $doc->document_type?->name }}</td>
                                    <td class="py-3">{{ $doc->expiry_date }}</td>
                                    <td class="py-3">{{ $doc->expiry_date }}</td>
                                    <td class="py-3 text-center">{!! $doc->current_status !!}</td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">@localize('No data found')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $reminders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endcan
    @push('css')
    <link rel="stylesheet" href="{{ admin_asset('css/dashboard.min.css') }}">
    <style>
        .stats-card {
            min-height: 280px;
            overflow: hidden;
        }
        .stats-card .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-y: auto;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
            overflow: hidden;
        }
        .chart-container {
            min-height: 400px;
        }
        .bg-yellow {
            background-color: #ffcc00 !important;
            color: #000 !important;
        }
        .card-header {
            padding: 0.5rem 1rem;
            line-height: 1.2;
        }
        .card-header h6 {
            margin-bottom: 0;
        }
        .text-themed {
            color: #00000080 !important; /* Using Bootstrap's danger red color */
        }
        .text-themed strong {
            color: #000 !important;
        }
    </style>
    @endpush
    @push('js')
        <script src="{{ admin_asset('vendor/amcharts5/index.min.js') }}"></script>
        <script src="{{ admin_asset('vendor/amcharts5/venn.js') }}"></script>

        <script src="{{ admin_asset('vendor/amcharts5/percent.min.js') }}"></script>
        <script src="{{ admin_asset('vendor/amcharts5/percent.min.js') }}"></script>
        <script src="{{ admin_asset('vendor/amcharts5/themes/Animated.min.js') }}"></script>
        <script src="{{ admin_asset('vendor/amcharts5/xy.min.js') }}"></script>
        <script src="{{ admin_asset('js/dashboard.min.js') }}"></script>
    @endpush
</x-app-layout>
