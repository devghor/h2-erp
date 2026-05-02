<?php

namespace App\Http\Controllers\Uam\User;

use App\Http\Controllers\Controller;
use App\Models\Uam\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    public function impersonate(Request $request, int $id)
    {
        $currentUser = $request->user();

        abort_unless($currentUser->isSuperAdmin() || $currentUser->isAdmin(), 403);
        abort_if($currentUser->id === $id, 403, 'Cannot impersonate yourself.');
        abort_if($request->session()->has('impersonator_id'), 403, 'Already impersonating a user.');

        $target = User::findOrFail($id);

        $request->session()->put('impersonator_id', $currentUser->id);

        Auth::login($target);

        return redirect()->route('dashboard');
    }

    public function leave(Request $request)
    {
        $impersonatorId = $request->session()->pull('impersonator_id');

        abort_unless($impersonatorId, 403, 'Not impersonating anyone.');

        $originalUser = User::findOrFail($impersonatorId);

        Auth::login($originalUser);

        return redirect()->route('dashboard');
    }
}
