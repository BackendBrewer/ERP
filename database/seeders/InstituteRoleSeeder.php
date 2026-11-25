<?php

namespace Database\Seeders;

use App\Models\Institute;
use App\Models\InstituteRole;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstituteRoleSeeder extends Seeder
{
    public function run(): void
    {
        $defaultRoles = [
            'institute-admin' => 'Institute Admin',
            'teacher' => 'Teacher',
            'coordinator' => 'Coordinator',
            'receptionist' => 'Receptionist',
        ];

        Institute::query()->each(function (Institute $institute) use ($defaultRoles): void {
            foreach ($defaultRoles as $slug => $name) {
                $role = $institute->roles()->where('slug', $slug)->first();

                if ($role === null) {
                    $role = new InstituteRole;
                    $role->institute_id = $institute->id;
                    $role->name = $name;
                    $role->slug = $slug;
                    $role->save();
                }

                $permissionSlugs = config("permissions.roles.{$slug}", []);
                $permissionIds = $permissionSlugs === '*'
                    ? Permission::query()->whereNotNull('parent_id')->pluck('id')->all()
                    : Permission::query()->whereIn('slug', $permissionSlugs)->pluck('id')->all();

                $role->permissions()->syncWithoutDetaching($permissionIds);
            }

            $membershipCount = DB::table('institute_user')
                ->where('institute_id', $institute->id)
                ->where('status', 'active')
                ->count();

            if ($membershipCount === 1) {
                $adminRole = $institute->roles()->where('slug', 'institute-admin')->first();

                DB::table('institute_user')
                    ->where('institute_id', $institute->id)
                    ->where('status', 'active')
                    ->whereNull('role_id')
                    ->update(['role_id' => $adminRole?->id]);
            }
        });
    }
}
