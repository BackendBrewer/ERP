<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        if ($request->user()->can('access-super-admin')) {
            return redirect()->route('super-admin.dashboard');
        }

        Gate::authorize('access-institute-admin');

        return redirect()->route('institute.dashboard');
    }

    public function institute(Request $request): View
    {
        $academy = $request->user()->academy()->firstOrFail();

        return view('institute.dashboard', compact('academy'));
    }
}