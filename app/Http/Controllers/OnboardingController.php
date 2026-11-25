<?php

namespace App\Http\Controllers;

use App\Models\Institute;
use App\Models\InstituteProfile;
use App\Models\InstituteRole;
use App\Models\Permission;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        abort_unless($user?->is_super_admin, 403, 'Platform administrator access required.');

        if ($user && ! $user->is_super_admin && $user->institutes()->exists()) {
            return redirect()->route('institutes.select');
        }

        return view('institutes.onboarding-modern');
    }

    public function store(Request $request): RedirectResponse
    {
        $currentUser = $request->user();
        $isPlatformAdmin = (bool) $currentUser?->is_super_admin;

        abort_unless($isPlatformAdmin, 403, 'Platform administrator access required.');

        if ($currentUser && ! $isPlatformAdmin && $currentUser->institutes()->exists()) {
            return redirect()->route('institutes.select');
        }

        $validated = $request->validate([
            'institute_name' => ['required', 'string', 'max:255'],
            'institute_address' => ['nullable', 'string', 'max:255'],
            'institute_city' => ['nullable', 'string', 'max:120'],
            'institute_phone' => ['nullable', 'string', 'max:30'],
            'institute_alternate_phone' => ['nullable', 'string', 'max:30'],
            'institute_email' => ['nullable', 'email', 'max:255'],
            'institute_website' => ['nullable', 'url', 'max:255'],
            'name' => [$currentUser && ! $isPlatformAdmin ? 'nullable' : 'required', 'string', 'max:255'],
            'email' => [$currentUser && ! $isPlatformAdmin ? 'nullable' : 'required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => [$currentUser && ! $isPlatformAdmin ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ]);

        [$instituteAdmin, $institute] = DB::transaction(function () use ($request, $currentUser, $isPlatformAdmin, $validated): array {
            $instituteAdmin = $currentUser && ! $isPlatformAdmin
                ? $currentUser
                : User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                ]);

            $baseSlug = Str::slug($validated['institute_name']) ?: 'institute';
            $slug = $baseSlug;
            $suffix = 2;

            while (Institute::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$suffix++;
            }

            $institute = new Institute;
            $institute->name = $validated['institute_name'];
            $institute->slug = $slug;
            $institute->status = 'active';
            $institute->save();

            $profile = new InstituteProfile;
            $profile->address = $validated['institute_address'] ?? null;
            $profile->city = $validated['institute_city'] ?? null;
            $profile->phone = $validated['institute_phone'] ?? null;
            $profile->alternate_phone = $validated['institute_alternate_phone'] ?? null;
            $profile->email = $validated['institute_email'] ?? null;
            $profile->website = $validated['institute_website'] ?? null;
            $institute->profile()->save($profile);

            $adminRoleId = null;

            foreach ([
                'institute-admin' => 'Institute Admin',
                'teacher' => 'Teacher',
                'coordinator' => 'Coordinator',
                'receptionist' => 'Receptionist',
            ] as $roleSlug => $roleName) {
                $role = new InstituteRole;
                $role->institute_id = $institute->id;
                $role->name = $roleName;
                $role->slug = $roleSlug;
                $role->save();

                $permissionSlugs = config("permissions.roles.{$roleSlug}", []);
                $permissionIds = $permissionSlugs === '*'
                    ? Permission::query()->whereNotNull('parent_id')->pluck('id')->all()
                    : Permission::query()->whereIn('slug', $permissionSlugs)->pluck('id')->all();

                $role->permissions()->syncWithoutDetaching($permissionIds);

                if ($roleSlug === 'institute-admin') {
                    $adminRoleId = $role->id;
                }
            }

            $instituteAdmin->institutes()->attach($institute->id, [
                'status' => 'active',
                'role_id' => $adminRoleId,
            ]);

            AuditLog::create([
                'institute_id' => $institute->id,
                'user_id' => $currentUser->id,
                'action' => 'institute.created',
                'subject_type' => Institute::class,
                'subject_id' => (string) $institute->id,
                'metadata' => ['slug' => $institute->slug],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            AuditLog::create([
                'institute_id' => $institute->id,
                'user_id' => $currentUser->id,
                'action' => 'staff.created',
                'subject_type' => User::class,
                'subject_id' => (string) $instituteAdmin->id,
                'metadata' => ['role' => 'institute-admin'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return [$instituteAdmin, $institute];
        });

        if ($isPlatformAdmin) {
            return redirect()->route('platform.dashboard')->with('status', 'Institute and institute administrator created.');
        }

        if (! $currentUser) {
            Auth::login($instituteAdmin);
        }

        $request->session()->regenerate();
        $request->session()->put('current_institute_id', $institute->id);

        return redirect()->route('workspace');
    }
}
