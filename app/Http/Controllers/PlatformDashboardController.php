<?php

namespace App\Http\Controllers;

use App\Models\Institute;
use Illuminate\View\View;

class PlatformDashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('platform.dashboard', [
            'instituteCount' => Institute::count(),
            'activeInstituteCount' => Institute::where('status', 'active')->count(),
            'suspendedInstituteCount' => Institute::where('status', 'suspended')->count(),
            'recentInstitutes' => Institute::query()
                ->withCount('users')
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }
}
