<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_landing_page_is_available_without_authentication(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Zarządzaj swoimi finansami w jednym miejscu')
            ->assertSee('href="' . route('login') . '"', false)
            ->assertSee('href="' . route('register') . '"', false)
            ->assertSee('href="' . route('landing.privacy-policy') . '"', false)
            ->assertSee('col-12 col-md-6 col-lg-4', false);
    }

    public function test_privacy_policy_page_is_available_without_authentication(): void
    {
        $response = $this->get('/landing/privacy-policy');

        $response
            ->assertOk()
            ->assertSee('Polityka prywatności')
            ->assertSee('contact@example.com', false);
    }
}
