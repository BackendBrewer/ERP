<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveCurrentInstitute
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user, 401, 'Unauthenticated.');

        $instituteId = $request->session()->get('current_institute_id');

        abort_unless(filter_var($instituteId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false,
            403,
            'No institute selected.'
        );

        $institute = $user->institutes()
            ->where('institutes.id', $instituteId)
            ->where('institutes.status', 'active')
            ->wherePivot('status', 'active')
            ->first();
        
        // dd([
        //     'authenticated_user_id' => $user->id,
        //     'selected_institute_id' => $instituteId,
        //     'session_has_institute' => $request->session()->has('current_institute_id'),
        //     'authorized_institute_found' => $institute !== null,
        //     'memberships' => $user->institutes()
        //         ->get()
        //         ->map(fn ($institute) => [
        //             'id' => $institute->id,
        //             'status' => $institute->status,
        //             'membership_status' => $institute->pivot->status,
        //         ])
        //         ->toArray(),
        // ]);


        abort_unless($institute !== null, 403, 'Institute access denied.');

        $request->attributes->set('currentInstitute', $institute);

        return $next($request);
    }
}
