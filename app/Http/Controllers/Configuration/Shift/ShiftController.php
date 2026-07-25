<?php

namespace App\Http\Controllers\Configuration\Shift;

use App\DataTables\Configuration\Shift\ShiftsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Shift\StoreShiftRequest;
use App\Http\Requests\Configuration\Shift\UpdateShiftRequest;
use App\Services\Configuration\Shift\ShiftService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ShiftController extends Controller
{
    public function __construct(
        private ShiftService $shiftService,
    ) {}

    public function index(ShiftsDataTable $dataTable)
    {
        return $dataTable->renderInertia('configuration/shifts/index');
    }

    public function create() {}

    public function store(StoreShiftRequest $request)
    {
        $data = $request->validated();
        $data['company_id'] = session(config('tenancy.company_id_session_key'));

        $this->shiftService->create($data);
        return redirect()->back()->with('success', 'Shift created successfully.');
    }

    public function show(string $id) {}

    public function edit(string $id) {}

    public function update(UpdateShiftRequest $request, string $id)
    {
        $this->shiftService->update($request->validated(), $id);
        return redirect()->route('configuration.shifts.index')->with([
            'success' => __('Shift updated successfully.'),
        ]);
    }

    public function destroy(string $id)
    {
        $this->shiftService->delete($id);
        return redirect()->route('configuration.shifts.index')->with([
            'success' => __('Shift deleted successfully.'),
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'required'])['ids'];
        $this->shiftService->bulkDelete($ids);

        return response()->json(['message' => 'Shifts deleted successfully.']);
    }
}
