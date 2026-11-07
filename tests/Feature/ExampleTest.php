<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_redirects_guests_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_opening_the_homepage_signs_out_authenticated_users_and_redirects_to_login(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/')
            ->assertRedirect('/login');

        $this->assertGuest();
        $this->get('/login')->assertOk()->assertSee('Sign in');
    }
}
