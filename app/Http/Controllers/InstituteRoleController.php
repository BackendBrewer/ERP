<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class InstituteRoleController extends Controller
{
    public function index(Request $request): View
    {
        $institute = $request->attributes->get('currentInstitute');

        $roles = $institute->roles()
            ->orderBy('name')
            ->paginate(15);

        return view('roles.index', [
            'institute' => $institute,
            'roles' => $roles,
        ]);
    }
}
