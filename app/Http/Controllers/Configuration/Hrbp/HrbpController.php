<?php

namespace App\Http\Controllers\Configuration\Hrbp;

use App\DataTables\Configuration\Hrbp\HrbpsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Hrbp\StoreHrbpRequest;
use App\Http\Requests\Configuration\Hrbp\UpdateHrbpRequest;
use App\Models\Uam\User;
use App\Services\Configuration\Hrbp\HrbpService;
use Illuminate\Http\Request;

class HrbpController extends Controller
{
    public function __construct(private HrbpService $hrbpService) {}

    public function index(HrbpsDataTable $dataTable)
    {
        return $dataTable->renderInertia('configuration/hrbps/index', [
            'users' => User::select(['id', 'name', 'username', 'email'])->get(),
        ]);
    }

    public function create() {}

    public function store(StoreHrbpRequest $request)
    {
        $this->hrbpService->create($request->validated());
        return redirect()->back()->with('success', 'Hrbp created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(UpdateHrbpRequest $request, string $id)
    {
        $this->hrbpService->update($request->validated(), $id);
        return redirect()->route('configuration.hrbps.index')->with([
            'success' => __('Hrbp updated successfully.'),
        ]);
    }

    public function destroy(string $id)
    {
        $this->hrbpService->delete($id);
        return redirect()->route('configuration.hrbps.index')->with([
            'success' => __('Hrbp deleted successfully.'),
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'required'])['ids'];
        $this->hrbpService->bulkDelete($ids);

        return response()->json(['message' => 'Hrbps deleted successfully.']);
    }
}
