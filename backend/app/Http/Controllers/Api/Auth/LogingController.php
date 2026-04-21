<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        $input = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($input)) {
            $user = auth()->user();

            $token = $user->createToken('authToken')->accessToken;

            return ApiResponseHelper::success([
                ...$user->toArray(),
                'access_token' => $token,
            ], 'Login successful');
        } else {
            return ApiResponseHelper::error('Invalid email or password', 401);
        }
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
