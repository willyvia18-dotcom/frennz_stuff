<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_has_post_forms_for_switching_account_and_logout(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/profile');

        $response->assertOk();

        $document = new \DOMDocument;
        @$document->loadHTML($response->getContent());
        $xpath = new \DOMXPath($document);

        foreach (['switch.account', 'logout'] as $route) {
            $forms = $xpath->query('//form[@method="POST" and @action="'.route($route).'"]');
            $this->assertCount(1, $forms);
            $this->assertCount(1, $xpath->query('.//input[@name="_token" and @value!=""]', $forms->item(0)));
            $this->assertCount(1, $xpath->query('.//button[@type="submit"]', $forms->item(0)));
        }
    }

    public function test_logout_clears_session_and_returns_to_home(): void
    {
        $user = User::factory()->create();
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);

        $oldSessionId = session()->getId();
        $oldToken = session()->token();

        $this->withSession(['account_marker' => 'old-account'])->post('/logout')
            ->assertRedirect(route('home'))
            ->assertSessionMissing('account_marker');

        $this->assertGuest();
        $this->assertNotSame($oldSessionId, session()->getId());
        $this->assertNotSame($oldToken, session()->token());
        $this->get('/profile')->assertRedirect(route('login'));
        $this->get('/')->assertOk();
    }

    public function test_homepage_after_logout_renders_guest_state(): void
    {
        $user = User::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('home'));

        // Sebelum logout: beranda menandai pengunjung sebagai terautentikasi.
        $this->get('/')->assertOk()
            ->assertSee('IS_AUTHENTICATED = true', false)
            ->assertSee('href="'.route('profile.index').'"', false);

        $this->post('/logout')->assertRedirect(route('home'));

        // Setelah logout: beranda dirender ulang sebagai pengunjung
        // yang belum memiliki akun — ikon akun mengarah ke halaman login.
        $this->get('/')->assertOk()
            ->assertSee('IS_AUTHENTICATED = false', false)
            ->assertSee('href="'.route('login').'"', false)
            ->assertDontSee('href="'.route('profile.index').'"', false);
    }

    public function test_switch_account_clears_session_and_allows_another_user_to_login(): void
    {
        $first = User::factory()->create();
        $second = User::factory()->create();
        $this->post('/login', ['email' => $first->email, 'password' => 'password'])
            ->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($first);

        $oldSessionId = session()->getId();
        $oldToken = session()->token();

        $this->withSession(['account_marker' => 'old-account'])->post('/switch-account')
            ->assertRedirect(route('login'))
            ->assertSessionMissing('account_marker');

        $this->assertGuest();
        $this->assertNotSame($oldSessionId, session()->getId());
        $this->assertNotSame($oldToken, session()->token());
        $this->get('/login')->assertOk();
        $this->post('/login', ['email' => $second->email, 'password' => 'password'])
            ->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($second);
        $this->get('/profile')->assertOk()->assertSee($second->email)->assertDontSee($first->email);
    }

    public function test_failed_login_after_switching_does_not_restore_previous_account(): void
    {
        $this->actingAs(User::factory()->create())->post('/switch-account')
            ->assertRedirect(route('login'));

        $this->from('/login')->post('/login', [
            'email' => 'missing@example.test',
            'password' => 'incorrect-password',
        ])->assertRedirect('/login')->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->get('/profile')->assertRedirect(route('login'));
    }
}
