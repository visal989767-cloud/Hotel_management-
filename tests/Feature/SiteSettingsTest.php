<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_are_saved_to_the_database_and_displayed_again(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('settings.update'), [
                'site_name' => 'Harbor Hotel',
                'tagline' => 'A quieter stay',
                'email' => 'hello@harbor.example',
                'phone' => '+1 555 0100',
                'address' => '10 Harbor Road',
                'check_in' => '14:30',
                'check_out' => '10:30',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('site_settings', [
            'id' => 1,
            'site_name' => 'Harbor Hotel',
            'email' => 'hello@harbor.example',
            'check_in' => '14:30',
            'check_out' => '10:30',
        ]);

        $this->get(route('settings'))
            ->assertOk()
            ->assertSee('Harbor Hotel')
            ->assertSee('hello@harbor.example');
    }

    public function test_settings_page_shows_defaults_until_saved(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('settings'))
            ->assertOk()
            ->assertSee('Ocean Resort');

        $this->assertDatabaseCount('site_settings', 0);
    }
}
