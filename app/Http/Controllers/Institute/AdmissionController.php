<?php

namespace App\Http\Controllers\Institute;

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
            ->with(['sections:id,academy_class_id,name', 'subjectGroups:id,academy_class_id,name'])
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('institute.admissions.create', [
            'source' => $source,
            'classes' => $classes,
        ]);
    }

    public function store(StoreAdmissionRequest $request, ?string $inquiry = null): RedirectResponse
    {
        $user = $request->user();
        $academy = $user->academy()->firstOrFail();
        $source = $this->findInquiry($academy, $inquiry);
        $data = $request->validated();

        $class = $academy->classes()->find($data['academy_class_id']);

        if ($class === null) {
            throw ValidationException::withMessages([
                'academy_class_id' => 'Select a class from your academy.',
            ]);
        }

        if (! $class->sections()->whereKey($data['section_id'])->exists()) {
            throw ValidationException::withMessages([
                'section_id' => 'Select a section from the chosen class.',
            ]);
        }

        $groupId = $data['subject_group_id'] ?? null;

        if ($groupId !== null && ! $class->subjectGroups()->whereKey($groupId)->exists()) {
            throw ValidationException::withMessages([
                'subject_group_id' => 'Select a subject group from the chosen class.',
            ]);
        }

        try {
            $student = DB::transaction(function () use ($academy, $user, $source, $data, $groupId): Student {
                $student = new Student([
                    'name' => $data['student_name'],
                    'guardian_name' => $data['guardian_name'] ?? null,
                    'contact_phone' => $data['contact_phone'],
                ]);
                $student->academy_id = $academy->id;
                $student->save();

                $enrollment = new Enrollment();
                $enrollment->section_id = $data['section_id'];
                $enrollment->subject_group_id = $groupId;
                $enrollment->admission_inquiry_id = $source?->id;
                $enrollment->created_by = $user->id;
                $enrollment->admitted_on = $data['admitted_on'];

                $student->enrollments()->save($enrollment);

                return $student;
            });
        } catch (UniqueConstraintViolationException $exception) {
            if (! $source?->enrollment()->exists()) {
                throw $exception;
            }

            return redirect()->route('institute.admission-inquiries.index')->with('status', 'This inquiry has already been admitted.');
        }

        return redirect()->route('institute.admission-inquiries.index')->with('status', "Admission saved. Student ID: {$student->id}.");
    }

    private function findInquiry(Academy $academy, ?string $inquiry): ?AdmissionInquiry
    {
        if ($inquiry === null) {
            return null;
        }

        return $academy->admissionInquiries()->findOrFail($inquiry);
    }
}