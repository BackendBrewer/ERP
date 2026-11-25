<?php

namespace App\Http\Middleware;

use App\Models\Institute;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInstitutePermission
{
    public function handle(Request $request, Closure $next, string $permissionSlug): Response
    {
        /** @var Institute|null $institute */
        $institute = $request->attributes->get('currentInstitute');
        $user = $request->user();

        abort_unless(
            $user !== null
                && $institute !== null
                && $user->hasInstitutePermission($permissionSlug, $institute),
            403,
            'You are not authorized to perform this action.'
        );

        return $next($request);
    }
}
