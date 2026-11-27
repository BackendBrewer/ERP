<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use App\Http\Requests\Institute\StoreAcademyClassRequest;
use App\Http\Requests\Institute\StoreSectionRequest;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AcademicSetupController extends Controller
{
    public function index(Request $request): View
    {
        $academy = $request->user()->academy()->firstOrFail();

        $classes = $academy->classes()
            ->select(['id', 'name'])
            ->with(['sections' => fn ($query) => $query
                ->select(['id', 'academy_class_id', 'name'])
                ->orderBy('name')])
            ->orderBy('name')
            ->orderBy('id')
            ->simplePaginate(15);

        return view('institute.academic-setup.index', compact('classes'));
    }

    public function storeClass(StoreAcademyClassRequest $request): RedirectResponse
    {
        $academy = $request->user()->academy()->firstOrFail();

        try {
            $academy->classes()->create([
                'name' => $request->validated('class_name'),
            ]);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'class_name' => 'A class with this name already exists in your academy.',
            ]);
        }

        return redirect()->route('institute.academic-setup.index')->with('status', 'Class created.');
    }

    public function storeSection(StoreSectionRequest $request): RedirectResponse
    {
        $academy = $request->user()->academy()->firstOrFail();

        $class = $academy->classes()->findOrFail($request->validated('academy_class_id'));

        try {
            $class->sections()->create([
                'name' => $request->validated('section_name'),
            ]);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'section_name' => 'A section with this name already exists in this class.',
            ]);
        }

        return back()->with('status', "Section added to {$class->name}.");
    }
}