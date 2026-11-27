<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreInstituteAdminRequest;
use App\Models\Academy;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InstituteAdminController extends Controller
{
    public function create(Academy $academy): View
    {
        return view('super-admin.institute-admins.create', compact('academy'));
    }

    public function store(
        StoreInstituteAdminRequest $request,
        Academy $academy
    ): RedirectResponse {
        $data = $request->validated();

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->academy()->associate($academy);
        $user->academy_role = User::ROLE_INSTITUTE_ADMIN;

        try {
            $user->save();
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'email' => 'An account with this email already exists.',
            ]);
        }

        return redirect()->route('super-admin.academies.index')->with('status', "Institute Admin created for {$academy->name}.");
    }
}