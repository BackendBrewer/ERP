<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreAcademyRequest;
use App\Models\Academy;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AcademyController extends Controller
{
    public function index(): View
    {
        $academies = Academy::query()
            ->select(['id', 'name', 'slug'])
            ->orderByDesc('id')
            ->simplePaginate(15);

        return view('super-admin.academies.index', compact('academies'));
    }

    public function create(): View
    {
        return view('super-admin.academies.create');
    }

    public function store(StoreAcademyRequest $request): RedirectResponse
    {
        try {
            Academy::create($request->validated());
        } catch (UniqueConstraintViolationException $exception) {
            throw ValidationException::withMessages([
                'slug' => 'An academy with this slug already exists.',
            ]);
        }

        return redirect()->route('super-admin.academies.index')->with('status', 'Academy created successfully.');
    }
}