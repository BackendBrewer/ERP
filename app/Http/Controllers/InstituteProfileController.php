<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class InstituteProfileController extends Controller
{
    public function show(Request $request): View
    {
        $institute = $request->attributes->get('currentInstitute');

        $institute->load('profile');

        return view('institutes.profile', [
            'institute' => $institute,
        ]);
    }
}
