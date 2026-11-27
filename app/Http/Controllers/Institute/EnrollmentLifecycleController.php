<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use App\Http\Requests\Institute\ChangeEnrollmentRequest;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EnrollmentLifecycleController extends Controller
{
    public function create(
        Request $request,
        string $student,
        string $action,
    ): View|RedirectResponse {
        $academy = $request->user()->academy()->firstOrFail();
        $record = $academy->students()->findOrFail($student);
        $latest = $record->latestEnrollment()->firstOrFail();

        $isOpen = $latest->ended_on === null;

        if (($action === 'rejoin') === $isOpen) {
            return redirect()
                ->route('institute.students.show', $record->id)
                ->with('status', 'This action is unavailable for the current enrollment.');
        }

        $classes = $action === 'withdraw'
            ? collect()
            : $academy->classes()
                ->with([
                    'sections:id,academy_class_id,name',
                    'subjectGroups:id,academy_class_id,name',
                ])
                ->orderBy('name')
                ->get(['id', 'name']);

        return view('institute.students.enrollment-change', [
            'student' => $record,
            'latest' => $latest,
            'action' => $action,
            'classes' => $classes,
        ]);
    }

    public function store(
        ChangeEnrollmentRequest $request,
        string $student,
        string $action,
    ): RedirectResponse {
        $user = $request->user();
        $academy = $user->academy()->firstOrFail();
        $data = $request->validated();
        $effectiveOn = today('Asia/Karachi')->toDateString();

        DB::transaction(function () use ($academy, $student, $action, $data, $user, $effectiveOn): void {
            $record = $academy->students()
                ->lockForUpdate()
                ->findOrFail($student);

            $latest = $record->latestEnrollment()->firstOrFail();
            $isOpen = $latest->ended_on === null;

            if ((int) $data['enrollment_id'] !== (int) $latest->id
                || ($action === 'rejoin') === $isOpen) {
                throw ValidationException::withMessages([
                    'enrollment_id' => 'The enrollment has changed. Return to the student profile and reopen this action.',
                ]);
            }

            $earliestDate = ($latest->ended_on ?? $latest->admitted_on)->toDateString();

            if ($effectiveOn < $earliestDate) {
                throw ValidationException::withMessages([
                    'enrollment_id' => 'This action cannot take effect before the latest enrollment date.',
                ]);
            }

            if ($action !== 'withdraw') {
                $class = $academy->classes()->find($data['academy_class_id']);

                if ($class === null) {
                    throw ValidationException::withMessages([
                        'academy_class_id' => 'Select a class from your academy.',
                    ]);
                }

                $section = $class->sections()->find($data['section_id']);

                if ($section === null) {
                    throw ValidationException::withMessages([
                        'section_id' => 'Select a section from the chosen class.',
                    ]);
                }

                $groupId = isset($data['subject_group_id'])
                    ? (int) $data['subject_group_id']
                    : null;

                if ($groupId !== null && ! $class->subjectGroups()->whereKey($groupId)->exists()) {
                    throw ValidationException::withMessages([
                        'subject_group_id' => 'Select a subject group from the chosen class.',
                    ]);
                }

                $previousGroupId = $latest->subject_group_id === null
                    ? null
                    : (int) $latest->subject_group_id;

                if ($action === 'transfer'
                    && (int) $latest->section_id === (int) $section->id
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
                $next->section_id = $section->id;
                $next->subject_group_id = $groupId;
                $next->created_by = $user->id;
                $next->admitted_on = $effectiveOn;

                $record->enrollments()->save($next);
            }
        });

        $message = match ($action) {
            'transfer' => 'Student transferred.',
            'withdraw' => 'Student withdrawn.',
            'rejoin' => 'Student rejoined.',
        };

        return redirect()->route('institute.students.show', $student)->with('status', $message);
    }
}