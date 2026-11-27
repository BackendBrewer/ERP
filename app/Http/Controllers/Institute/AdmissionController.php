<?php

namespace App\Http\Controllers\Institute;

use App\Actions\Institute\ResolveEnrollmentPlacement;
use App\Http\Controllers\Controller;
use App\Http\Requests\Institute\StoreAdmissionRequest;
use App\Models\Academy;
use App\Models\AdmissionInquiry;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdmissionController extends Controller
{
    public function create(Request $request, ?string $inquiry = null): View|RedirectResponse
    {
        $academy = $request->user()->academy()->firstOrFail();
        $source = $this->findInquiry($academy, $inquiry);

        if ($source?->enrollment()->exists()) {
            return redirect()
                ->route('institute.admission-inquiries.index')
                ->with('status', 'This inquiry has already been admitted.');
        }

        $classes = $academy->classes()
            ->with([
                'sections:id,academy_class_id,name',
                'subjectGroups:id,academy_class_id,name',
                'subjectGroups.subjects:id,name',
            ])
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('institute.admissions.create', [
            'source' => $source,
            'classes' => $classes,
            'subjects' => $academy->subjects()->orderBy('name')->get(['id', 'name']),
            'showGroupPreview' => true,
        ]);
    }

    public function store(StoreAdmissionRequest $request, ResolveEnrollmentPlacement $resolvePlacement, ?string $inquiry = null): RedirectResponse
    {
        $user = $request->user();
        $academy = $user->academy()->firstOrFail();
        $source = $this->findInquiry($academy, $inquiry);
        $data = $request->validated();

        $placement = $resolvePlacement($academy, $data);
        $studentDetails = $request->studentAttributes();

        try {
            $student = DB::transaction(function () use ($academy, $user, $source, $data, $placement, $studentDetails): Student {
                $student = $academy->students()->create($studentDetails);

                $enrollment = new Enrollment();
                $enrollment->section_id = $placement['section_id'];
                $enrollment->subject_group_id = $placement['subject_group_id'];
                $enrollment->admission_inquiry_id = $source?->id;
                $enrollment->created_by = $user->id;
                $enrollment->admitted_on = $data['admitted_on'];

                $enrollment->roll_number = $data['roll_number'] ?? null;
                $enrollment->attendance_starts_on = $data['attendance_starts_on'] ?? $data['admitted_on'];
                $student->enrollments()->save($enrollment);
                $enrollment->subjects()->attach($placement['subject_ids']);

                return $student;
            });
        } catch (UniqueConstraintViolationException $exception) {
            if ($source?->enrollment()->exists()) {
                return redirect()->route('institute.admission-inquiries.index')
                    ->with('status', 'This inquiry has already been admitted.');
            }

            $number = $data['admission_number'] ?? null;

            if ($number !== null && $academy->students()->where('admission_number', $number)->exists()) {
                throw ValidationException::withMessages([
                    'admission_number' => 'This admission number is already in use in your academy.',
                ]);
            }

            throw $exception;
        }

        return redirect()->route('institute.students.show', $student->id)
            ->with('status', 'Admission saved.');
    }

    private function findInquiry(Academy $academy, ?string $inquiry): ?AdmissionInquiry
    {
        if ($inquiry === null) {
            return null;
        }

        return $academy->admissionInquiries()->findOrFail($inquiry);
    }
}
