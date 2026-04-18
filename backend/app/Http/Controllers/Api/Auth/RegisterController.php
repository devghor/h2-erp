<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Configuration\Tenant;
use App\Models\Uam\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $input = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'tenant_name' => 'required|string|max:255',
            'tenant_short_name' => 'required|string|max:255',
            'tenant_address' => 'required|string|max:255',
            'tenant_phone' => 'required|string|max:255',
            'tenant_email' => 'required|string|email|max:255',
        ]);

        try {
            DB::beginTransaction();
            $tenant = Tenant::create([
                'name' => $input['tenant_name'],
                'short_name' => $input['tenant_short_name'],
                'email' => $input['tenant_email'],
                'address' => $input['tenant_address'],
                'phone' => $input['tenant_phone'],
            ]);
            $user = User::create([
                'name' => $input['name'],
                'tenant_id' => $tenant->id,
                'email' => $input['email'],
                'password' => bcrypt($input['password']),
            ]);
            DB::commit();
            return ApiResponseHelper::success($user, 'User registered successfully', 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error registering user: ' . $e->getMessage());
            return ApiResponseHelper::error($e->getMessage(), 400);
        }
    }
}
