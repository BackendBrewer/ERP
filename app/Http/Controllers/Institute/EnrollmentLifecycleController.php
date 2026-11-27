<?php

namespace App\Http\Controllers\Institute;

use App\Actions\Institute\ResolveEnrollmentPlacement;
use App\Http\Controllers\Controller;
use App\Http\Requests\Institute\ChangeEnrollmentRequest;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EnrollmentLifecycleController extends Controller
{
    public function create(Request $request, string $student, string $action): RedirectResponse
    {
        $academy = $request->user()->academy()->firstOrFail();
        $record = $academy->students()->findOrFail($student);

        return redirect()->route('institute.students.show', [
            'student' => $record->id,
            'action' => $action,
        ], 302, ['Cache-Control' => 'no-store']);
    }

    public function store(
        ChangeEnrollmentRequest $request,
        ResolveEnrollmentPlacement $resolvePlacement,
        string $student,
        string $action,
    ): RedirectResponse {
        $user = $request->user();
        $academy = $user->academy()->firstOrFail();
        $data = $request->validated();
        $effectiveOn = today('Asia/Karachi')->toDateString();

        $changed = DB::transaction(function () use ($academy, $student, $action, $data, $user, $effectiveOn, $resolvePlacement): bool {
            $record = $academy->students()
                ->lockForUpdate()
                ->findOrFail($student);

            $latest = $record->latestEnrollment()->firstOrFail();
            $isOpen = $latest->ended_on === null;

            if ((int) $data['enrollment_id'] !== (int) $latest->id
                || ($action === 'rejoin') === $isOpen) {
                return false;
            }

            $earliestDate = ($latest->ended_on ?? $latest->admitted_on)->toDateString();

            if ($effectiveOn < $earliestDate) {
                throw ValidationException::withMessages([
                    'enrollment_id' => 'This action cannot take effect before the latest enrollment date.',
                ]);
            }

            if ($action !== 'withdraw') {
                $placement = $resolvePlacement($academy, $data);
                $groupId = $placement['subject_group_id'];
                $previousGroupId = $latest->subject_group_id === null
                    ? null
                    : (int) $latest->subject_group_id;

                if ($action === 'transfer'
                    && (int) $latest->section_id === $placement['section_id']
                    && $previousGroupId === $groupId) {
                    throw ValidationException::withMessages([
                        'section_id' => 'Choose a different section or subject group.',
                    ]);
                }
            }

            if ($isOpen) {
                $latest->ended_on = $effectiveOn;
                $latest->end_reason = $action === 'transfer' ? 'transferred' : 'withdrawn';
                $latest->ended_by = $user->id;
                $latest->save();
            }

            if ($action !== 'withdraw') {
                $next = new Enrollment();
                $next->section_id = $placement['section_id'];
                $next->subject_group_id = $groupId;
                $next->created_by = $user->id;
                $next->admitted_on = $effectiveOn;
                $next->attendance_starts_on = $effectiveOn;
                $sameClassAndGroup = (int) $latest->section->academy_class_id === (int) $data['academy_class_id']
                    && $previousGroupId === $groupId;
                $previousSubjects = $sameClassAndGroup
                    ? $latest->subjects()->pluck('subjects.id')->all()
                    : [];

                $record->enrollments()->save($next);
                $next->subjects()->attach($previousSubjects ?: $placement['subject_ids']);
            }
            return true;
        });

        if (! $changed) {
            return redirect()->route('institute.students.show', $student)
                ->with('status', 'The enrollment has changed. Review the latest history before applying another action.');
        }

        $message = match ($action) {
            'transfer' => 'Student transferred.',
            'withdraw' => 'Student withdrawn.',
            'rejoin' => 'Student rejoined.',
        };

        return redirect()->route('institute.students.show', $student)->with('status', $message);
    }
}
