<?php

namespace App\Http\Controllers\Api\Configuration;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\AuthUserResource;
use App\Models\Configuration\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = auth()->user();

        if ($authUser->isSuperAdmin()) {
            return ApiResponseHelper::success(Company::all(), 'Tenants list');
        } else {
            return ApiResponseHelper::success([tenant()], 'Tenant list');
        }
    }

    public function switch(Request $request)
    {
        $input = $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        $user = auth()->user();
        $user->company_id = $input['company_id'];

        return new AuthUserResource($user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
