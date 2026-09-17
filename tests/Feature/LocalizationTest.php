<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_locale_is_indonesian(): void
    {
        $this->app->setLocale('id');

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Beranda');
        $response->assertSee('Keranjang');
    }

    public function test_can_switch_to_english_via_lang_route(): void
    {
        $this->get('/lang/en')->assertRedirect();
    }

    public function test_english_locale_renders_english_ui(): void
    {
        $this->withSession(['locale' => 'en']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Home');
    }
}
