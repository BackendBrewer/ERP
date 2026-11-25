<?php

namespace App\Http\Middleware;

use App\Models\Institute;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInstituteRole
{
    public function handle(Request $request, Closure $next, string $requiredRoleSlug): Response
    {
        /** @var Institute|null $institute */
        $institute = $request->attributes->get('currentInstitute');
        $roleId = $institute?->pivot?->role_id;

        abort_unless(
            $institute !== null
                && $roleId !== null
                && $institute->roles()->whereKey($roleId)->where('slug', $requiredRoleSlug)->exists(),
            403,
            'This institute role is required to access this page.'
        );

        return $next($request);
    }
}
