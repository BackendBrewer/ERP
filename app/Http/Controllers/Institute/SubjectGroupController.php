<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use App\Http\Requests\Institute\StoreSubjectGroupRequest;
use App\Models\SubjectGroup;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SubjectGroupController extends Controller
{
    public function index(Request $request): View
    {
        $academy = $request->user()->academy()->firstOrFail();

        $classOptions = $academy->classes()
            ->orderBy('name')
            ->get(['id', 'name']);

        $subjectOptions = $academy->subjects()
            ->orderBy('name')
            ->get(['id', 'name']);

        $groups = SubjectGroup::query()
            ->whereHas('academyClass', fn ($query) => $query
                ->where('academy_id', $academy->id))
            ->with([
                'academyClass:id,name',
                'subjects' => fn ($query) => $query
                    ->select(['subjects.id', 'subjects.name'])
                    ->orderBy('subjects.name'),
            ])
            ->orderByDesc('id')
            ->simplePaginate(15);

        return view('institute.subject-groups.index', compact(
            'classOptions',
            'subjectOptions',
            'groups',
        ));
    }

    public function store(StoreSubjectGroupRequest $request): RedirectResponse
    {
        $academy = $request->user()->academy()->firstOrFail();
        $data = $request->validated();

        $class = $academy->classes()->find($data['academy_class_id']);

        if ($class === null) {
            throw ValidationException::withMessages([
                'academy_class_id' => 'Select a class from your academy.',
            ]);
        }

        $subjectIds = $academy->subjects()
            ->whereKey($data['subject_ids'])
            ->pluck('subjects.id')
            ->all();

        if (count($subjectIds) !== count($data['subject_ids'])) {
            throw ValidationException::withMessages([
                'subject_ids' => 'Select subjects from your academy.',
            ]);
        }

        try {
            DB::transaction(function () use ($class, $data, $subjectIds): void {
                $group = $class->subjectGroups()->create([
                    'name' => $data['name'],
                ]);

                $group->subjects()->attach($subjectIds);
            });
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'name' => 'A group with this name already exists in this class.',
            ]);
        }

        return redirect()->route('institute.subject-groups.index')->with('status', 'Subject group created.');
    }
}