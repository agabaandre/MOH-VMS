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
            <div class="col-md-12 col-lg-6">
                <div class="form-group row my-2">
                    <label for="emp_name" class="col-sm-5 col-form-label">@localize('Employee Name') <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-7">
                        <input name="name" required id="emp_name" class="form-control" type="text"
                            placeholder="@localize('Employee Name')" value="{{ isset($item) ? $item->name : old('name') }}">
                    </div>
                </div>
                
                <div class="form-group row my-2">
                    <label for="department" class="col-sm-5 col-form-label">@localize('Department') <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-7">
                        <select class="form-control basic-single" required name="department_id" id="department"
                            data-select2-id="department" tabindex="-1" aria-hidden="true">
                            <option value="" selected="selected">@localize('Please Select One')</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ isset($item) ? ($item->department_id == $department->id ? 'selected' : '') : '' }}>
                                    {{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="email" class="col-sm-5 col-form-label">@localize('Email') <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-7">
                        <input name="email" required class="form-control" type="email"
                            placeholder="@localize('Email')" id="email"
                            value="{{ isset($item) ? $item->email : old('email') }}">
                    </div>
                </div>
                
                <div class="form-group row my-2">
                    <label for="present_cont" class="col-sm-5 col-form-label">@localize('Present Contact Number') </label>
                    <div class="col-sm-7">
                        <input name="present_contact" class="form-control" type="number"
                            placeholder="@localize('Present Contact Number')" id="present_cont"
                            value="{{ isset($item) ? $item->present_contact : old('present_contact') }}">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="present_address" class="col-sm-5 col-form-label">@localize('Present Address') </label>
                    <div class="col-sm-7">
                        <input name="present_address" class="form-control" type="text"
                            placeholder="@localize('Present Address')" id="present_address"
                            value="{{ isset($item) ? $item->present_address : old('present_address') }}">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="present_city" class="col-sm-5 col-form-label">@localize('Present City') </label>
                    <div class="col-sm-7">
                        <input name="present_city" class="form-control" type="text"
                            placeholder="@localize('Present City')" id="present_city"
                            value="{{ isset($item) ? $item->present_city : old('present_city') }}">
                    </div>
                </div>

                <div class="form-group row my-2">
                    <label for="referance" class="col-sm-5 col-form-label">@localize('Reference Name') </label>
                    <div class="col-sm-7">
                        <input name="reference_name" class="form-control" type="text"
                            placeholder="Reference Name" id="referance"
                            value="{{ isset($item) ? $item->reference_name : old('reference_name') }}">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="ref_address" class="col-sm-5 col-form-label">@localize('Reference Address') </label>
                    <div class="col-sm-7">
                        <input name="reference_address" class="form-control" type="text"
                            placeholder="@localize('Reference Address')" id="ref_address"
                            value="{{ isset($item) ? $item->reference_address : old('reference_address') }}">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="ref_email" class="col-sm-5 col-form-label">@localize('Reference Email') </label>
                    <div class="col-sm-7">
                        <input name="reference_email" class="form-control" type="email"
                            placeholder="@localize('Reference Email')" id="ref_email"
                            value="{{ isset($item) ? $item->reference_email : old('reference_email') }}">
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6">
                <div class="form-group row my-2">
                    <label for="emp_nid" class="col-sm-5 col-form-label">@localize('Employee NID') <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-7">
                        <input name="nid" required="" class="form-control" type="number"
                            placeholder="@localize('Employee NID')" id="emp_nid"
                            value="{{ isset($item) ? $item->nid : old('nid') }}">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="card_number" class="col-sm-5 col-form-label">@localize('Card Number') <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-7">
                        <input name="card_number" required="" class="form-control" type="text"
                            placeholder="@localize('Card Number')" id="card_number"
                            value="{{ isset($item) ? $item->card_number : old('card_number') }}">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="designation" class="col-sm-5 col-form-label">@localize('Designation') <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-7">
                        <select class="form-control basic-single" required name="position_id" id="designation"
                            data-select2-id="designation" tabindex="-1" aria-hidden="true">
                            <option value="" selected="selected">@localize('Please Select One')
                            </option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}"
                                    {{ isset($item) ? ($item->position_id == $position->id ? 'selected' : '') : '' }}>
                                    {{ $position->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="phone" class="col-sm-5 col-form-label">@localize('Employee Mobile') <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-7">
                        <input name="phone" required class="form-control" type="number"
                            placeholder="@localize('Employee Mobile')" id="phone"
                            value="{{ isset($item) ? $item->phone : old('phone') }}">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="dob" class="col-sm-5 col-form-label">@localize('Date of Birth') <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-7">
                        <input name="dob" required autocomplete="off" class="form-control" type="date"
                            placeholder="@localize('Date of Birth')" id="dob"
                            value="{{ isset($item) ? $item->dob : old('dob') }}">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="picture" class="col-sm-5 col-form-label">@localize('Photograph') </label>
                    <div class="col-sm-7">
                        <input type="file" accept="image/*" name="picture"
                            onchange="get_img_url(this, '#avatar_image');">
                        <img id="avatar_image" src="{{ isset($item) ? $item->avatar_url : '' }}" width="120px"
                            class="mt-1">
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
