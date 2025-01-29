<?php

namespace Modules\VehicleManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\VehicleManagement\DataTables\FacilityDataTable;
use Modules\VehicleManagement\Entities\Facility;

class FacilityController extends Controller
{
    /**
     * Constructor for the controller.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'permission:vehicle_facility_management']);
        $this->middleware('request:ajax', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
        $this->middleware('strip_scripts_tag')->only(['store', 'update']);
        \cs_set('theme', [
            'title' => 'Vehicle office Lists',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Vehicle office Lists',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.vehicle.rta-office',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FacilityDataTable $dataTable)
    {
        \cs_set('theme', [
            'description' => 'Display a listing of Vehicle office in Database.',
        ]);

        return $dataTable->render('vehiclemanagement::facility.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vehiclemanagement::facility.create_edit')->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:facilitys,name',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);
        $item = Facility::create($data);

        return response()->success($item, localize('Item Created Successfully'), 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(Facility $office)
    {
        return view('vehiclemanagement::facility.create_edit', ['item' => $office])->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Facility $office, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:facilitys,name,' . $office->id . ',id',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);
        $office->update($data);

        return response()->success($office, localize('Item Updated Successfully'), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Facility $office)
    {

        $office->delete();
        return response()->success(null, localize('Item Deleted Successfully'), 200);
    }
}
