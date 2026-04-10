<?php

namespace App\Http\Controllers\Api\Uam;

use App\Exports\Uam\UsersExport;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Uam\UserCollection;
use App\Http\Resources\Uam\UserResource;
use App\Models\Uam\User;
use App\Services\Uam\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\FormatService;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected FormatService $formatService
    ) {}

    public function index(Request $request): JsonResponse|UserCollection
    {
        $users = $this->userService->getAllUsers($request);

        return new UserCollection($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $this->userService->createUser($validated);

        return new UserResource($user);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        $user = $this->userService->updateUser($user, $validated);

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);

        return ApiResponse::noContent();
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ulids' => 'required|array|min:1',
            'ulids.*' => 'required|string|exists:users,ulid',
        ]);

        $deletedCount = $this->userService->bulkDeleteUsers($validated['ulids']);

        return ApiResponse::success(
            "{$deletedCount} user(s) deleted successfully",
            [
                'deleted_count' => $deletedCount,
            ]
        );
    }

    public function exportExcel(Request $request)
    {
        $query = $this->userService->getExportQuery($request->only(['name', 'email', 'from_date', 'to_date']));

        return Excel::download(new UsersExport($query), $this->formatService->excelFileName('users'));
    }
}
