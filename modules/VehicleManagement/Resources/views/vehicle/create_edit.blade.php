<form
    action="{{ isset($item) ? route(config('theme.rprefix') . '.update', $item->id) : route(config('theme.rprefix') . '.store') }}"
    method="POST" class="needs-validation modal-content" novalidate="novalidate" enctype="multipart/form-data"
    onsubmit="submitFormAxios(event)">
    @csrf
    @if (isset($item))
        @method('PUT')
    @endif
    <div class="card-header my-3 p-2 border-bottom">
        <h4>{{ config('theme.title') }}</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="form-group row">
                    <label for="image" class="col-sm-2 col-form-label">@localize('Vehicle Image')</label>
                    <div class="col-sm-10">
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        @if(isset($item) && $item->image)
                            <div class="mt-2">
                                <img src="{{ asset($item->image) }}" alt="Vehicle Image" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6">

                <div class="form-group row my-2">
                    <label for="name" class="col-sm-5 col-form-label">@localize('Vehicle Name') <i
                            class="text-danger">*</i></label> </label>
                    <div class="col-sm-7">
                        <input name="name" class="form-control" type="text" placeholder="@localize('Vehicle Name')"
                            id="name" value="{{ isset($item) ? $item->name : old('name') }}" required>
                    </div>
                </div>

                <div class="form-group row my-2">
                    <label for="department_id" class="col-sm-5 col-form-label">@localize('Department') <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-7">
                        <select class="form-control basic-single" name="department_id" id="department_id" tabindex="-1"
                            aria-hidden="true">
                            <option value="">@localize('Please Select One')</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ isset($item) ? ($item->department_id == $department->id ? 'selected' : '') : '' }}>
                                    {{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row my-2">
                    <label for="registration_date" class="col-sm-5 col-form-label">@localize('Registration Date') <i
                            class="text-danger">*</i></label> </label>
                    <div class="col-sm-7">
                        <input name="registration_date" autocomplete="off" required class="form-control" type="date"
                            placeholder="@localize('Requisition Date')" id="registration_date"
                            value="{{ isset($item) ? $item->registration_date : old('registration_date') }}">
                    </div>
                </div>

                <div class="form-group row my-2">
                    <label for="previous_plate" class="col-sm-5 col-form-label">@localize('Previous Number Plate')</label>
                    <div class="col-sm-7">
                        <input name="previous_plate" class="form-control" type="text" placeholder="@localize('Previous Number Plate')"
                            id="previous_plate" value="{{ isset($item) ? $item->previous_plate : old('previous_plate') }}">
                    </div>
                </div>

                <div class="form-group row my-2">
                    <label for="license_plate" class="col-sm-5 col-form-label">@localize('Current License Plate') <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-7">
                        <input name="license_plate" class="form-control" type="text" placeholder="@localize('Current License Plate')"
                            id="license_plate" value="{{ isset($item) ? $item->license_plate : old('license_plate') }}"
                            required>
                    </div>
                </div>

                <div class="form-group row mb-1">
                    <label for="ownership" class="col-sm-5 col-form-label">@localize('Ownership') <i
                            class="text-danger">*</i></label> </label>
                    <div class="col-sm-7">
                        <select class="form-control basic-single" name="ownership_id" id="ownership" tabindex="-1"
                            aria-hidden="true">
                            <option value="">@localize('Please Select One')</option>
                            @foreach ($ownerships as $key => $value)
                                <option value="{{ $value->id }}"
                                    {{ isset($item) ? ($item->ownership_id == $value->id ? 'selected' : '') : '' }}>
                                    {{ $value->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>

            <div class="col-md-12 col-lg-6">

                <div class="form-group row mb-1">
                    <label for="vehicle_type" class="col-sm-5 col-form-label">@localize('Vehicle Type') <i
                            class="text-danger">*</i> </label>
                    <div class="col-sm-7">
                        <select class="form-control basic-single" name="vehicle_type_id" id="vehicle_type"
                            tabindex="-1" aria-hidden="true">
                            <option value="">@localize('Please Select One')</option>
                            @foreach ($vehicle_types as $key => $value)
                                <option value="{{ $value->id }}"
                                    {{ isset($item) ? ($item->vehicle_type_id == $value->id ? 'selected' : '') : '' }}>
                                    {{ $value->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row my-2">
                    <label for="facility" class="col-sm-5 col-form-label">@localize('Facility') <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-7">
                        <select class="form-control basic-single" name="rta_circle_office_id" id="facility"
                            tabindex="-1" aria-hidden="true">
                            <option value="">@localize('Please Select One')</option>
                            @foreach ($circle_offices as $facility)
                                <option value="{{ $facility->id }}"
                                    {{ isset($item) ? ($item->rta_circle_office_id == $facility->id ? 'selected' : '') : '' }}>
                                    {{ $facility->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row my-2">
                    <label for="driver_id" class="col-sm-5 col-form-label">@localize('Driver') <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-7">
                        <select class="form-control basic-single" name="driver_id" id="driver_id" tabindex="-1"
                            aria-hidden="true">
                            <option value="">@localize('Please Select One')</option>
                            @foreach ($drivers as $driver)
                                <option value="{{ $driver->id }}"
                                    {{ isset($item) ? ($item->driver_id == $driver->id ? 'selected' : '') : '' }}>
                                    {{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mb-1">
                    <label for="vendor" class="col-sm-5 col-form-label">@localize('Vendor') <i
                            class="text-danger">*</i> </label>
                    <div class="col-sm-7">
                        <select class="form-control basic-single" name="vendor_id" id="vendor" tabindex="-1"
                            aria-hidden="true">
                            <option value="">@localize('Please Select One')</option>
                            @foreach ($vendors as $key => $value)
                                <option value="{{ $value->id }}"
                                    {{ isset($item) ? ($item->vendor_id == $value->id ? 'selected' : '') : '' }}>
                                    {{ $value->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row my-2">
                    <label for="seat_capacity" class="col-sm-5 col-form-label">@localize('Seat Capacity with Driver') <i
                            class="text-danger">*</i> </label>
                    <div class="col-sm-7">
                        <input name="seat_capacity" class="form-control" type="number"
                            placeholder="@localize('Seat Capacity with Driver')" id="seat_capacity"
                            value="{{ isset($item) ? $item->seat_capacity : old('seat_capacity') }}" required>
                    </div>
                </div>

                <div class="form-group row my-2">
                    <label for="is_active" class="col-sm-5 col-form-label">@localize('Status')</label>
                    <div class="col-sm-7">
                        <select class="form-control" name="is_active" id="is_active">
                            <option value="1" {{ isset($item) && $item->is_active ? 'selected' : '' }}>@localize('Active')</option>
                            <option value="0" {{ isset($item) && !$item->is_active ? 'selected' : '' }}>@localize('Off-Board')</option>
                        </select>
                    </div>
                </div>

                <div class="offboard-fields" style="display: {{ isset($item) && !$item->is_active ? 'block' : 'none' }}">
                    <div class="form-group row my-2">
                        <label for="off_board_date" class="col-sm-5 col-form-label">@localize('Off-Board Date')</label>
                        <div class="col-sm-7">
                            <input type="date" class="form-control" name="off_board_date" id="off_board_date" 
                                value="{{ isset($item) ? $item->off_board_date?->format('Y-m-d') : '' }}">
                        </div>
                    </div>

                    <div class="form-group row my-2">
                        <label for="off_board_remarks" class="col-sm-5 col-form-label">@localize('Off-Board Remarks')</label>
                        <div class="col-sm-7">
                            <textarea class="form-control" name="off_board_remarks" id="off_board_remarks" rows="3">{{ isset($item) ? $item->off_board_remarks : '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">@localize('Close')</button>
        <button class="btn btn-success" type="submit">@localize('Save')</button>
    </div>
</form>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#is_active').on('change', function() {
            if ($(this).val() == '0') {
                $('.offboard-fields').show();
                $('#off_board_date').prop('required', true);
                $('#off_board_remarks').prop('required', true);
            } else {
                $('.offboard-fields').hide();
                $('#off_board_date').prop('required', false);
                $('#off_board_remarks').prop('required', false);
            }
        });
    });
</script>
@endpush
