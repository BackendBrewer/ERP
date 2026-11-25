<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Institute;
use App\Models\InstituteRole;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InstituteTeamController extends Controller
{
    private const ASSIGNABLE_ROLE_SLUGS = ['teacher', 'coordinator', 'receptionist'];

    public function index(Request $request): View
    {
        /** @var Institute $institute */
        $institute = $request->attributes->get('currentInstitute');
        $roles = $institute->roles()
            ->whereIn('slug', self::ASSIGNABLE_ROLE_SLUGS)
            ->orderBy('name')
            ->get();
        $allRoles = $institute->roles()->get()->keyBy('id');
        $members = $institute->users()
            ->where('users.is_super_admin', false)
            ->wherePivot('status', 'active')
            ->orderBy('users.name')
            ->get();

        foreach ($members as $member) {
            $member->assignedRole = $allRoles->get($member->pivot->role_id);
        }

        return view('team.index', compact('institute', 'roles', 'members'));
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var Institute $institute */
        $institute = $request->attributes->get('currentInstitute');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => [
                'required',
                'integer',
                Rule::exists('institute_roles', 'id')->where('institute_id', $institute->id),
            ],
        ]);

        $role = $institute->roles()
            ->whereKey($validated['role_id'])
            ->whereIn('slug', self::ASSIGNABLE_ROLE_SLUGS)
            ->firstOrFail();

        DB::transaction(function () use ($request, $institute, $role, $validated): void {
            $staffMember = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            $institute->users()->attach($staffMember->id, [
                'status' => 'active',
                'role_id' => $role->id,
            ]);

            AuditLog::create([
                'institute_id' => $institute->id,
                'user_id' => $request->user()->id,
                'action' => 'staff.created',
                'subject_type' => User::class,
                'subject_id' => (string) $staffMember->id,
                'metadata' => ['role' => $role->slug],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        });

        return redirect()->route('team.index')->with('status', 'Staff account created. Share the login details with the staff member securely.');
    }

    public function updateRole(Request $request, User $staffMember): RedirectResponse
    {
        /** @var Institute $institute */
        $institute = $request->attributes->get('currentInstitute');
        $validated = $request->validate([
            'role_id' => [
                'required',
                'integer',
                Rule::exists('institute_roles', 'id')->where('institute_id', $institute->id),
            ],
        ]);

        $role = $institute->roles()
            ->whereKey($validated['role_id'])
            ->whereIn('slug', self::ASSIGNABLE_ROLE_SLUGS)
            ->firstOrFail();

        DB::transaction(function () use ($request, $institute, $role, $staffMember): void {
            $membership = DB::table('institute_user')
                ->where('institute_id', $institute->id)
                ->where('user_id', $staffMember->id)
                ->where('status', 'active')
                ->lockForUpdate()
                ->first();

            abort_unless($membership !== null, 404, 'Active staff membership not found.');

            abort_unless($staffMember->id !== $request->user()->id, 403, 'You cannot change your own institute role.');

            $currentRole = $membership->role_id
                ? $institute->roles()->whereKey($membership->role_id)->first()
                : null;

            abort_unless($currentRole?->slug !== 'institute-admin', 403, 'The institute admin role is managed by the platform.');

            DB::table('institute_user')
                ->where('id', $membership->id)
                ->update(['role_id' => $role->id, 'updated_at' => now()]);

            AuditLog::create([
                'institute_id' => $institute->id,
                'user_id' => $request->user()->id,
                'action' => 'staff.role_assigned',
                'subject_type' => User::class,
                'subject_id' => (string) $staffMember->id,
                'metadata' => ['role' => $role->slug],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        });

        return redirect()->route('team.index')->with('status', 'Staff role updated.');
    }
}
