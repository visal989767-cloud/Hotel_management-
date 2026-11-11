<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_login_shows_loading_screen_before_dashboard(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $this->post(route('login.submit'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('id="loginLoadingScreen"', false)
            ->assertSee('Preparing your dashboard...');
    }

    public function test_failed_login_does_not_show_dashboard_loading_screen(): void
    {
        $user = User::factory()->create();

        $this->from(route('login'))
            ->post(route('login.submit'), [
                'email' => $user->email,
                'password' => 'incorrect-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
    }
}
