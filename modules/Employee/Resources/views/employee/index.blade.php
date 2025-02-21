<x-app-layout>
    <x-card>
        <x-slot name='actions'>
            <div class="accordion-header d-flex justify-content-end align-items-center" id="flush-headingOne">
                <a class="btn btn-success btn-sm"  href="javascript:void(0);"
                    onclick="axiosModal('{{ route(\config('theme.rprefix') . '.create') }}')">
                    <i class="fa fa-plus"></i>&nbsp;
                    {{ localize('Add Employee') }}
                </a>

                <a class="btn btn-primary btn-sm mx-2" href="javascript:void(0);"
                    onclick="confirmImport('{{ route(\config('theme.rprefix') . '.import') }}')">
                    <i class="fas fa-file-import"></i>&nbsp;
                    {{ localize('Import Employees') }}
                </a>

                <button type="button" class="btn btn-success btn-sm mx-2" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne"> <i
                        class="fas fa-filter"></i> @localize('Filter')</button>
            </div>
        </x-slot>
        <x-filter-layout>
            <div class="col-sm-12 col-xl-4">
                <div class="form-group row mb-1">
                    <label for="department"
                        class="col-sm-5 col-form-label justify-content-start text-left">@localize('Department') <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-7">
                        <select class="form-control basic-single required" name="department" id="departmentsh"
                            tabindex="-1" aria-hidden="true">
                            <option value="">@localize('Please Select One')</option>
                            @foreach ($departments as $key => $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-1">
                    <label for="designation"
                        class="col-sm-5 col-form-label justify-content-start text-left">@localize('Designation') <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-7">
                        <select class="form-control basic-single required" name="designation" id="designationsh"
                            tabindex="-1" aria-hidden="true">
                            <option value="">@localize('Please Select One')</option>
                            @foreach ($positions as $key => $position)
                                <option value="{{ $position->id }}">{{ $position->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-4">
                <div class="row">
                    <div class="col-sm-12 col-xl-12">
                        <div class="form-group row mb-1">
                            <label for="join_datefrsh"
                                class="col-sm-5 col-form-label justify-content-start text-left">@localize('Joining Date From')
                            </label>
                            <div class="col-sm-7">
                                <input name="join_date_from" autocomplete="off" class="form-control  w-100"
                                    type="date" placeholder="@localize('Joining Date From')" id="join_datefrsh">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-12">
                        <div class="form-group row mb-1">
                            <label for="joining_d_to"
                                class="col-sm-5 col-form-label justify-content-start text-left">@localize('Joining Date To')
                            </label>
                            <div class="col-sm-7">
                                <input name="join_date_to" autocomplete="off" class="form-control w-100" type="date"
                                    placeholder="@localize('Joining Date To')" id="joining_d_to">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-filter-layout>

        <div>
            <x-data-table :dataTable="$dataTable" />
            <div id="page-axios-data" data-table-id="#employee-table"></div>
        </div>
    </x-card>

@push('js')
    <script type="module">
        "use strict";
        
        // Import confirmation handler
        window.confirmImport = function(url) {
            Swal.fire({
                title: '{{ localize("Confirm Import") }}',
                text: '{{ localize("Are you sure you want to import employees from HRIS?") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ localize("Yes, import!") }}',
                cancelButtonText: '{{ localize("Cancel") }}'
            }).then((result) => {
                console.log(result);
                if (result.isConfirmed) {
                    console.log(url);
                    axios.post(url)
                        .then(function (response) {
                            console.log(response)
                            if (response.data.success) {
                                toastr.success(response.data.message);
                                window.location.reload();
                            } else {
                                toastr.error(response.data.message);
                            }
                        })
                        .catch(function (error) {
                            toastr.error('{{ localize("An error occurred during import") }}');
                        });
                }
            });
        };
    </script>
@endpush
</x-app-layout>
