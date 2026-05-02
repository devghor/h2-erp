<?php

namespace App\Http\Middleware;

use App\Models\Configuration\Company\Company;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        $unreadNotificationsCount = 0;

        $permissions = [];

        $companies = [];

        $user = $request->user() ?? [];

        $selectedCompany = tenant();

        if ($user) {
            $unreadNotificationsCount = $user->unreadNotifications()->count();

            $companies = $user->companies;

            if ($user->isSuperAdmin() || $user->isAdmin()) {
                $permissions =  config('global-permission')[$user->global_role] ?? [];
            } else {
                $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
            }
        }

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $user,
                'companies' => $companies,
                'company' => $selectedCompany,
                'permissions' => $permissions,
                'unread_notifications_count' => $unreadNotificationsCount,
                'impersonating' => $request->session()->has('impersonator_id'),
            ],
            'ziggy' => fn(): array => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
