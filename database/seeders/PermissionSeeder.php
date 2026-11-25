<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $sortOrder = 0;

        foreach (config('permissions.modules', []) as $moduleSlug => $module) {
            $parent = Permission::firstOrNew(['slug' => $moduleSlug]);
            $parent->parent_id = null;
            $parent->module = $moduleSlug;
            $parent->name = $module['name'];
            $parent->sort_order = $sortOrder++;
            $parent->save();

            foreach ($module['permissions'] as $permissionSlug => $permissionName) {
                $permission = Permission::firstOrNew(['slug' => $permissionSlug]);
                $permission->parent_id = $parent->id;
                $permission->module = $moduleSlug;
                $permission->name = $permissionName;
                $permission->sort_order = $sortOrder++;
                $permission->save();
            }
        }
    }
}
