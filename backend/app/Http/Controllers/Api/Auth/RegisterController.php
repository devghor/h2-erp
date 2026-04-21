<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Configuration\Company;
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
            'company_name' => 'required|string|max:255',
            'company_short_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:255',
            'company_phone' => 'required|string|max:255',
            'company_email' => 'required|string|email|max:255',
        ]);

        try {
            DB::beginTransaction();
            $tenant = Company::create([
                'name' => $input['company_name'],
                'short_name' => $input['company_short_name'],
                'email' => $input['company_email'],
                'address' => $input['company_address'],
                'phone' => $input['company_phone'],
            ]);
            $user = User::create([
                'name' => $input['name'],
                'comapany_id' => $tenant->id,
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
