<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertSee('हरिवंशी सेवा कुंज');
        $response->assertSee('Administrator Email');
        $response->assertSee('Password');
        $response->assertSee('id="toggle-password"', false);
        $response->assertSee('aria-label="Show password"', false);
    }

    public function test_admin_login_screen_renders_password_view_toggle(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertSee('admin-password-wrap');
        $response->assertSee('id="toggle-password"', false);
        $response->assertSee('aria-label="Show password"', false);
        $response->assertSee('aria-controls="admin-password"', false);
        $response->assertSee('admin-password-icon--show');
        $response->assertSee('admin-password-icon--hide');
    }

    public function test_unauthenticated_visitor_cannot_access_admin_dashboard_and_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_unauthenticated_visitor_cannot_access_admin_root_and_is_redirected_to_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_authenticate_with_valid_credentials(): void
    {
        $admin = User::factory()->create([
            'email'     => 'admin@harivanshisevakunj.com',
            'password'  => bcrypt('password'),
            'is_admin'  => true,
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email'    => 'admin@harivanshisevakunj.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_cannot_authenticate_with_invalid_password(): void
    {
        User::factory()->create([
            'email'     => 'admin@harivanshisevakunj.com',
            'password'  => bcrypt('password'),
            'is_admin'  => true,
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email'    => 'admin@harivanshisevakunj.com',
            'password' => 'WrongPassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_non_admin_user_cannot_access_admin_dashboard_and_receives_403(): void
    {
        $regularUser = User::factory()->create([
            'email'    => 'devotee@example.com',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($regularUser)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_non_admin_user_cannot_login_through_admin_portal(): void
    {
        User::factory()->create([
            'email'    => 'devotee@example.com',
            'password' => bcrypt('UserPassword#123'),
            'is_admin' => false,
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email'    => 'devotee@example.com',
            'password' => 'UserPassword#123',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors('email');
    }

    public function test_admin_can_logout(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.logout'));

        $this->assertGuest();
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_dashboard_displays_honest_module_setup_states(): void
    {
        $admin = User::factory()->create([
            'name'     => 'Radha Sevak',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Radha Sevak');
        $response->assertSee('Total Products');
        $response->assertSee('Total Categories');
        $response->assertSee('Total Orders');
        $response->assertSee('Pending Orders');
        $response->assertSee('Recent Orders');
        $response->assertSee('Order Tracking Table Pending Setup');
    }
}
