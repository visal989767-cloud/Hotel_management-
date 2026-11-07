<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestGenderFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_gender_filter_matches_case_insensitively(): void
    {
        Guest::create([
            'guest_name' => 'Male Guest',
            'email' => 'male@example.com',
            'gender' => 'Male',
        ]);
        Guest::create([
            'guest_name' => 'Female Guest',
            'email' => 'female@example.com',
            'gender' => 'Female',
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('guests.index', ['gender' => 'male']))
            ->assertOk()
            ->assertSee('Male Guest')
            ->assertDontSee('Female Guest');

        $this->actingAs(User::factory()->create())
            ->get(route('guests.index', ['gender' => 'female']))
            ->assertOk()
            ->assertSee('Female Guest')
            ->assertDontSee('Male Guest');
    }
}
