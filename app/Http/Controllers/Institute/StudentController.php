<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $academy = $request->user()->academy()->firstOrFail();

        $students = $academy->students()
            ->select(['id', 'name', 'contact_phone'])
            ->with('latestEnrollment.section.academyClass')
            ->orderByDesc('students.id')
            ->simplePaginate(15);

        return view('institute.students.index', compact('students'));
    }

    public function show(Request $request, string $student): View
    {
        $academy = $request->user()->academy()->firstOrFail();
        $student = $academy->students()->with('latestEnrollment')->findOrFail($student);

        $enrollments = $student->enrollments()
            ->with(['section.academyClass', 'subjectGroup'])
            ->orderByDesc('admitted_on')
            ->orderByDesc('id')
            ->simplePaginate(15);

        return view('institute.students.show', compact('student', 'enrollments'));
    }
}