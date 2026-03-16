<?php

namespace Tests\Feature\Landing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_loads_correctly(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Zarządzaj swoimi finansami w prosty sposób');
    }

    public function test_landing_page_contains_login_link(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('href="/login"', false);
    }

    public function test_landing_page_contains_register_link(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('href="/register"', false);
    }

    public function test_landing_page_is_available_for_guests(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }
}
