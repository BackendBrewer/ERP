<?php

namespace App\Http\Controllers;

use App\Models\Institute;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstitutePermissionController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Institute $institute */
        $institute = $request->attributes->get('currentInstitute');
        $roles = $institute->roles()->with('permissions')->orderBy('name')->get();
        $permissionGroups = Permission::query()
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();
        $permissionIdsByRole = $roles->mapWithKeys(fn ($role) => [
            $role->id => $role->permissions->pluck('id')->all(),
        ]);

        return view('roles.permissions', compact('institute', 'roles', 'permissionGroups', 'permissionIdsByRole'));
    }
}
