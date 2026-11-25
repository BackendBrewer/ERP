<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstituteSelectionController extends Controller
{
    public function index(Request $request): View
    {
        $institutes = $request->user()
            ->institutes()
            ->where('institutes.status', 'active')
            ->wherePivot('status', 'active')
            ->orderBy('institutes.name')
            ->get();

        return view('institutes.select', [
            'institutes' => $institutes,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'institute_id' => ['required', 'integer', 'min:1'],
        ]);

        $institute = $request->user()
            ->institutes()
            ->where('institutes.id', $validated['institute_id'])
            ->where('institutes.status', 'active')
            ->wherePivot('status', 'active')
            ->first();

        abort_unless($institute, 403, 'Institute access denied.');

        $request->session()->regenerate();
        $request->session()->put('current_institute_id', $institute->id);

        return redirect()->route('workspace');
    }
}
