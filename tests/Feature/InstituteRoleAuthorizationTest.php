<?php

namespace Tests\Feature;

use App\Models\Institute;
use App\Models\InstituteRole;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstituteRoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\PermissionSeeder::class);
    }

    public function test_institute_admin_can_create_a_scoped_staff_account_and_it_is_audited(): void
    {
        [$institute, $admin] = $this->createInstituteAndAdmin();
        $teacherRole = $institute->roles()->where('slug', 'teacher')->firstOrFail();

        $this->actingAs($admin)
            ->withSession(['current_institute_id' => $institute->id])
            ->post(route('team.store'), [
                'name' => 'Teacher One',
                'email' => 'teacher@example.test',
                'password' => 'TeacherPass123',
                'password_confirmation' => 'TeacherPass123',
                'role_id' => $teacherRole->id,
            ])
            ->assertRedirect(route('team.index'));

        $teacher = User::where('email', 'teacher@example.test')->firstOrFail();

        $this->assertDatabaseHas('institute_user', [
            'institute_id' => $institute->id,
            'user_id' => $teacher->id,
            'role_id' => $teacherRole->id,
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'institute_id' => $institute->id,
            'user_id' => $admin->id,
            'action' => 'staff.created',
            'subject_id' => (string) $teacher->id,
        ]);
    }

    public function test_teacher_cannot_access_staff_management_or_roles(): void
    {
        [$institute] = $this->createInstituteAndAdmin();
        $teacherRole = $institute->roles()->where('slug', 'teacher')->firstOrFail();
        $teacher = User::factory()->create();
        $teacher->institutes()->attach($institute->id, [
            'status' => 'active',
            'role_id' => $teacherRole->id,
        ]);

        $this->actingAs($teacher)
            ->withSession(['current_institute_id' => $institute->id])
            ->get(route('team.index'))
            ->assertForbidden();

        $this->get(route('roles.index'))->assertForbidden();
    }

    public function test_super_admin_can_manage_platform_but_has_no_implicit_institute_access(): void
    {
        $institute = $this->createInstitute('Private Academy');
        $superAdmin = User::factory()->create();
        $superAdmin->is_super_admin = true;
        $superAdmin->save();

        $this->actingAs($superAdmin)
            ->get(route('platform.dashboard'))
            ->assertOk();

        $this->withSession(['current_institute_id' => $institute->id])
            ->get(route('workspace'))
            ->assertForbidden();
    }

    private function createInstituteAndAdmin(): array
    {
        $institute = $this->createInstitute('Role Test Academy');
        $adminRole = $this->createRole($institute, 'institute-admin', 'Institute Admin');
        $this->createRole($institute, 'teacher', 'Teacher');
        $this->createRole($institute, 'coordinator', 'Coordinator');
        $this->createRole($institute, 'receptionist', 'Receptionist');

        $adminRole->permissions()->sync(
            Permission::query()->whereNotNull('parent_id')->pluck('id')->all()
        );

        $teacherRole = $institute->roles()->where('slug', 'teacher')->firstOrFail();
        $teacherRole->permissions()->sync(
            Permission::query()->whereIn('slug', config('permissions.roles.teacher'))->pluck('id')->all()
        );

        $admin = User::factory()->create();
        $admin->institutes()->attach($institute->id, [
            'status' => 'active',
            'role_id' => $adminRole->id,
        ]);

        return [$institute, $admin];
    }

    private function createInstitute(string $name): Institute
    {
        $institute = new Institute;
        $institute->name = $name;
        $institute->slug = str($name)->slug();
        $institute->status = 'active';
        $institute->save();

        return $institute;
    }

    private function createRole(Institute $institute, string $slug, string $name): InstituteRole
    {
        $role = new InstituteRole;
        $role->institute_id = $institute->id;
        $role->slug = $slug;
        $role->name = $name;
        $role->save();

        return $role;
    }
}
