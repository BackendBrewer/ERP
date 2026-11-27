<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use App\Http\Requests\Institute\StudentDetailsRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'class_id' => ['nullable', 'integer', 'min:1'],
            'section_id' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', 'in:active,closed,scheduled'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', ...($request->filled('from') ? ['after_or_equal:from'] : [])],
        ]);

        $academy = $request->user()->academy()->firstOrFail();
        $today = today('Asia/Karachi')->toDateString();
        $query = $academy->students()
            ->select(['id', 'name', 'admission_number', 'contact_phone'])
            ->with('latestEnrollment.section.academyClass');

        $search = trim($filters['q'] ?? '');

        if ($search !== '') {
            $term = '%'.strtr($search, ['!' => '!!', '%' => '!%', '_' => '!_']).'%';
            $query->where(function (Builder $student) use ($term): void {
                foreach (['name', 'admission_number', 'contact_phone', 'student_phone', 'guardian_phone', 'whatsapp_phone'] as $column) {
                    $student->orWhereRaw("{$column} LIKE ? ESCAPE '!'", [$term]);
                }
            });
        }

        if (array_filter(array_diff_key($filters, ['q' => true]))) {
            $query->whereHas('latestEnrollment', function (Builder $enrollment) use ($filters, $today): void {
                $enrollment
                    ->when($filters['class_id'] ?? null, fn (Builder $rows, $classId) => $rows
                        ->whereHas('section', fn (Builder $section) => $section->where('academy_class_id', $classId)))
                    ->when($filters['section_id'] ?? null, fn (Builder $rows, $sectionId) => $rows->where('enrollments.section_id', $sectionId))
                    ->when($filters['from'] ?? null, fn (Builder $rows, $date) => $rows->where('enrollments.admitted_on', '>=', $date))
                    ->when($filters['to'] ?? null, fn (Builder $rows, $date) => $rows->where('enrollments.admitted_on', '<=', $date));

                match ($filters['status'] ?? null) {
                    'active' => $enrollment->whereNull('enrollments.ended_on')->where('enrollments.admitted_on', '<=', $today),
                    'closed' => $enrollment->whereNotNull('enrollments.ended_on'),
                    'scheduled' => $enrollment->whereNull('enrollments.ended_on')->where('enrollments.admitted_on', '>', $today),
                    default => null,
                };
            });
        }

        $students = $query->orderByDesc('students.id')->simplePaginate(15)->appends($filters);
        $classes = $academy->classes()->with('sections:id,academy_class_id,name')
            ->orderBy('name')->get(['id', 'name']);

        return view('institute.students.index', compact('students', 'classes', 'filters', 'today'));
    }

    public function show(Request $request, string $student): View
    {
        $academy = $request->user()->academy()->firstOrFail();
        $student = $academy->students()->with('latestEnrollment')->findOrFail($student);
        $latest = $student->latestEnrollment;
        $today = today('Asia/Karachi');
        $actions = ! $latest || $latest->admitted_on->toDateString() > $today->toDateString()
            ? []
            : ($latest->ended_on ? ['rejoin'] : ['transfer', 'withdraw']);
        $requestedAction = $request->query('action');
        $action = in_array($requestedAction, $actions, true) ? $requestedAction : null;
        $classes = in_array($action, ['transfer', 'rejoin'], true)
            ? $academy->classes()->with(['sections:id,academy_class_id,name', 'subjectGroups:id,academy_class_id,name'])
                ->orderBy('name')->get(['id', 'name'])
            : collect();

        $enrollments = $student->enrollments()
            ->with(['section.academyClass', 'subjectGroup.subjects', 'subjects'])
            ->orderByDesc('admitted_on')
            ->orderByDesc('id')
            ->simplePaginate(15);

        return view('institute.students.show', compact(
            'student', 'enrollments', 'latest', 'actions', 'action', 'classes', 'today',
        ));
    }

    public function update(StudentDetailsRequest $request, string $student): RedirectResponse
    {
        $academy = $request->user()->academy()->firstOrFail();
        $record = $academy->students()->findOrFail($student);
        $details = $request->studentAttributes();
        $firstAdmission = $record->enrollments()->min('admitted_on');

        if ($firstAdmission && isset($details['date_of_birth']) && $details['date_of_birth'] > $firstAdmission) {
            throw ValidationException::withMessages([
                'date_of_birth' => 'Date of birth must be on or before the first admission date.',
            ]);
        }

        try {
            $record->update($details);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'admission_number' => 'This admission number is already in use in your academy.',
            ]);
        }

        return redirect()->route('institute.students.show', $record->id)
            ->with('status', 'Student details updated.');
    }
}
