<?php

namespace App\Http\Controllers\Api\Uam;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Uam\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::paginate();
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

    /**
     * Display a listing of the resource.
     */
    public function me()
    {
        $details = auth()->user()->toArray();
        $details['roles'] = auth()->user()->roles;
        return ApiResponseHelper::success($details, 'User details');
    }
}
