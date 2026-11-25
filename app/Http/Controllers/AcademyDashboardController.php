<?php

namespace App\Http\Controllers;

use App\Models\Institute;
use App\Models\InstituteRole;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademyDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        /** @var Institute $institute */
        $institute = $request->attributes->get('currentInstitute');
        $institute->loadMissing('profile');

        $roleId = $institute->pivot?->role_id;
        $role = $roleId ? $institute->roles()->find($roleId) : null;
        $roleSlug = $role?->slug ?? 'member';
        $roleName = $role?->name ?? 'Institute Member';

        $dashboardCards = match ($roleSlug) {
            'institute-admin' => [
                ['label' => 'Active team members', 'value' => $institute->users()->wherePivot('status', 'active')->count(), 'detail' => 'People with active institute access'],
                ['label' => 'Workspace roles', 'value' => $institute->roles()->count(), 'detail' => 'Roles configured for this institute'],
                ['label' => 'Institute profile', 'value' => $institute->profile?->phone ? 'Ready' : 'Needs setup', 'detail' => 'Contact details and institute information'],
            ],
            'teacher' => [
                ['label' => 'Teaching assignments', 'value' => 'Not configured', 'detail' => 'Academic groups are part of the next setup phase'],
                ['label' => 'Attendance', 'value' => 'Not configured', 'detail' => 'Student attendance has not been set up yet'],
                ['label' => 'Results', 'value' => 'Not configured', 'detail' => 'Examinations and marks are not available yet'],
            ],
            'coordinator' => [
                ['label' => 'Academic groups', 'value' => 'Not configured', 'detail' => 'Classes, sections and subjects are not set up yet'],
                ['label' => 'Attendance follow-up', 'value' => 'Not configured', 'detail' => 'Attendance tools are part of a later phase'],
                ['label' => 'Examinations', 'value' => 'Not configured', 'detail' => 'Exam scheduling is not available yet'],
            ],
            'receptionist' => [
                ['label' => 'Admissions', 'value' => 'Not configured', 'detail' => 'Inquiry and admission workflows are not available yet'],
                ['label' => 'Student records', 'value' => 'Not configured', 'detail' => 'Student profiles are part of the next setup phase'],
                ['label' => 'Fee receipts', 'value' => 'Not configured', 'detail' => 'Fee collection tools are not available yet'],
            ],
            default => [
                ['label' => 'Institute access', 'value' => ucfirst($institute->status), 'detail' => 'Your membership is active'],
                ['label' => 'Workspace roles', 'value' => $institute->roles()->count(), 'detail' => 'Roles configured for this institute'],
                ['label' => 'Institute profile', 'value' => $institute->profile?->phone ? 'Ready' : 'Needs setup', 'detail' => 'Contact details and institute information'],
            ],
        };

        return view('academies.role-dashboard', [
            'institute' => $institute,
            'roleName' => $roleName,
            'roleSlug' => $roleSlug,
            'dashboardCards' => $dashboardCards,
        ]);
    }
}
