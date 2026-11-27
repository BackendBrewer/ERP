<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use App\Http\Requests\Institute\StoreSubjectRequest;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(Request $request): View
    {
        $academy = $request->user()->academy()->firstOrFail();

        $subjects = $academy->subjects()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->simplePaginate(15);

        return view('institute.subjects.index', compact('subjects'));
    }

    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        $academy = $request->user()->academy()->firstOrFail();

        try {
            $academy->subjects()->create($request->validated());
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'name' => 'A subject with this name already exists in your academy.',
            ]);
        }

        return redirect()->route('institute.subjects.index')->with('status', 'Subject created.');
    }
}