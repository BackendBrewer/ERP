<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use App\Http\Requests\Institute\StoreAdmissionInquiryRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdmissionInquiryController extends Controller
{
    public function index(Request $request): View
    {
        $academy = $request->user()->academy()->firstOrFail();

        $classes = $academy->classes()
            ->orderBy('name')
            ->get(['id', 'name']);

        $inquiries = $academy->admissionInquiries()
            ->select([
                'id',
                'academy_class_id',
                'student_name',
                'contact_phone',
                'follow_up_on',
            ])
            ->withExists('enrollment')
            ->with('academyClass:id,name')
            ->orderByDesc('id')
            ->simplePaginate(15);

        return view('institute.admission-inquiries.index', compact('classes', 'inquiries',));
    }

    public function store(StoreAdmissionInquiryRequest $request): RedirectResponse
    {
        $user = $request->user();
        $academy = $user->academy()->firstOrFail();

        $inquiry = $academy->admissionInquiries()->make($request->validated());
        $inquiry->created_by = $user->id;
        $inquiry->save();

        return redirect()->route('institute.admission-inquiries.index')->with('status', 'Admission inquiry saved.');
    }
}