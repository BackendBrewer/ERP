<?php

namespace Tests\Feature;

use App\Models\Institute;
use App\Models\InstituteRole;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InstituteTenancyTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_platform_admin_can_create_an_institute_and_its_admin(): void
    {
        $platformAdmin = User::factory()->create();
        $platformAdmin->is_super_admin = true;
        $platformAdmin->save();

        $this->actingAs($platformAdmin)
            ->get(route('onboarding.create'))
            ->assertOk();

        $response = $this->post(route('onboarding.store'), [
            'institute_name' => 'Bright Future Academy',
            'institute_phone' => '+92 300 1112233',
            'institute_email' => 'info@brightfuture.example',
            'name' => 'Ayesha Khan',
            'email' => 'ayesha@example.test',
            'password' => 'StrongPass123',
            'password_confirmation' => 'StrongPass123',
        ]);

        $response->assertRedirect(route('platform.dashboard'));
        $this->assertDatabaseHas('institutes', ['name' => 'Bright Future Academy', 'slug' => 'bright-future-academy']);
        $instituteAdmin = User::where('email', 'ayesha@example.test')->firstOrFail();
        $institute = Institute::where('slug', 'bright-future-academy')->firstOrFail();
        $this->assertDatabaseHas('institute_user', [
            'institute_id' => $institute->id,
            'user_id' => $instituteAdmin->id,
            'status' => 'active',
        ]);
        $this->assertDatabaseMissing('institute_user', ['user_id' => $platformAdmin->id]);
        $this->assertDatabaseHas('institute_profiles', ['institute_id' => $institute->id]);
        $this->assertDatabaseHas('institute_profiles', [
            'institute_id' => $institute->id,
            'phone' => '+92 300 1112233',
            'email' => 'info@brightfuture.example',
        ]);
    }

    public function test_public_registration_is_not_available(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register')->assertNotFound();
    }

    public function test_non_platform_users_cannot_open_institute_onboarding(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('onboarding.create'))
            ->assertForbidden();

        $this->get(route('onboarding.create'))->assertRedirect(route('login'));
    }

    public function test_login_and_logout_work_with_session_rotation(): void
    {
        $user = User::factory()->create(['email' => 'user@example.test', 'password' => Hash::make('secret123')]);

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'secret123'])
            ->assertRedirect(route('account'));
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_user_cannot_select_or_open_another_institutes_workspace(): void
    {
        $user = User::factory()->create();
        $own = $this->createInstitute('My Academy');
        $other = $this->createInstitute('Other Academy');
        $adminRole = new InstituteRole;
        $adminRole->institute_id = $own->id;
        $adminRole->name = 'Institute Admin';
        $adminRole->slug = 'institute-admin';
        $adminRole->save();

        $dashboardPermission = new Permission;
        $dashboardPermission->module = 'dashboard';
        $dashboardPermission->name = 'View dashboard';
        $dashboardPermission->slug = 'dashboard.view';
        $dashboardPermission->save();
        $adminRole->permissions()->attach($dashboardPermission->id);

        $user->institutes()->attach($own->id, ['status' => 'active', 'role_id' => $adminRole->id]);

        $this->actingAs($user)
            ->post(route('institutes.select.store'), ['institute_id' => $other->id])
            ->assertForbidden();

        $this->withSession(['current_institute_id' => $other->id])
            ->get(route('workspace'))
            ->assertForbidden();

        $this->withSession(['current_institute_id' => $own->id])
            ->get(route('workspace'))
            ->assertOk()
            ->assertSee('My Academy');
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
}
