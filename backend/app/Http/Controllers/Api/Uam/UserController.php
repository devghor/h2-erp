<?php

namespace App\Http\Controllers\Api\Uam;

use App\Exports\Uam\UsersExport;
use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Uam\BulkDeleteUserRequest;
use App\Http\Requests\Uam\StoreUserRequest;
use App\Http\Requests\Uam\UpdateUserRequest;
use App\Http\Resources\Uam\UserResource;
use App\Models\Uam\User;
use App\Services\FormatService;
use App\Services\Uam\UserService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly FormatService $formatService,
    ) {}

    public function index(Request $request)
    {
        return UserResource::collection($this->userService->list($request));
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());

        return ApiResponseHelper::success(new UserResource($user), 'User created', 201);
    }

    public function show(User $user)
    {
        $user->load('roles');

        return ApiResponseHelper::success(new UserResource($user), 'User details');
    }

    public function all()
    {
        return ApiResponseHelper::success(['users' => $this->userService->all()], 'All users');
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        if ($request->has('roles')) {
            $user = $this->userService->syncRoles($user, $request->validated()['roles']);

            return ApiResponseHelper::success(new UserResource($user), 'Roles updated');
        }

        $user = $this->userService->update($user, $request->validated());

        return ApiResponseHelper::success(new UserResource($user), 'User updated');
    }

    public function destroy(User $user)
    {
        $this->userService->delete($user);

        return ApiResponseHelper::success([], 'User deleted');
    }

    public function bulkDelete(BulkDeleteUserRequest $request)
    {
        $this->userService->bulkDelete($request->validated()['ids']);

        return ApiResponseHelper::success([], 'Users deleted');
    }

    public function export(Request $request)
    {
        $users = $this->userService->export($request);

        return Excel::download(new UsersExport($users), $this->formatService->exportFileName('users'));
    }

    public function me()
    {
        $details = auth()->user()->toArray();
        $details['company_id'] = tenant('id');
        $details['roles'] = auth()->user()->roles;

        return ApiResponseHelper::success($details, 'User details');
    }
}
