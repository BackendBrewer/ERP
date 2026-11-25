<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Institute;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstituteAuditController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Institute $institute */
        $institute = $request->attributes->get('currentInstitute');
        $logs = AuditLog::query()
            ->with('actor')
            ->where('institute_id', $institute->id)
            ->latest('created_at')
            ->paginate(25);

        return view('audit.index', compact('institute', 'logs'));
    }
}
