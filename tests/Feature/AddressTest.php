<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_addresses(): void
    {
        $this->getJson('/addresses')->assertStatus(401);
        $this->postJson('/addresses', [])->assertStatus(401);
    }

    public function test_user_can_create_first_address_as_default(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/addresses', [
            'label' => 'Rumah',
            'recipient_name' => 'Budi',
            'recipient_phone' => '08123456789',
            'address_line' => 'Jl. Merdeka No. 1',
            'city' => 'Bandung',
            'postal' => '40111',
        ]);

        $response->assertOk()->assertJsonPath('ok', true);

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'recipient_name' => 'Budi',
            'city' => 'Bandung',
            'is_default' => true,
        ]);
    }

    public function test_user_can_update_and_set_default_address(): void
    {
        $user = User::factory()->create();

        $first = $user->addresses()->create([
            'recipient_name' => 'A', 'recipient_phone' => '081',
            'address_line' => 'Jl A', 'city' => 'Jakarta', 'postal' => '11111', 'is_default' => true,
        ]);
        $second = $user->addresses()->create([
            'recipient_name' => 'B', 'recipient_phone' => '082',
            'address_line' => 'Jl B', 'city' => 'Surabaya', 'postal' => '22222', 'is_default' => false,
        ]);

        $this->actingAs($user)->patchJson("/addresses/{$second->id}/default")
            ->assertOk();

        $this->assertFalse($first->fresh()->is_default);
        $this->assertTrue($second->fresh()->is_default);

        $this->actingAs($user)->putJson("/addresses/{$second->id}", [
            'recipient_name' => 'B Edited',
            'recipient_phone' => '082',
            'address_line' => 'Jl B Baru',
            'city' => 'Surabaya',
            'postal' => '22223',
        ])->assertOk();

        $this->assertDatabaseHas('addresses', ['id' => $second->id, 'address_line' => 'Jl B Baru']);
    }

    public function test_user_cannot_modify_other_users_address(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $address = $owner->addresses()->create([
            'recipient_name' => 'A', 'recipient_phone' => '081',
            'address_line' => 'Jl A', 'city' => 'Jakarta', 'postal' => '11111', 'is_default' => true,
        ]);

        $this->actingAs($other)->putJson("/addresses/{$address->id}", [
            'recipient_name' => 'Hack', 'recipient_phone' => '0', 'address_line' => 'x', 'city' => 'y', 'postal' => 'z',
        ])->assertStatus(403);

        $this->actingAs($other)->deleteJson("/addresses/{$address->id}")->assertStatus(403);
    }

    public function test_deleting_default_promotes_another_address(): void
    {
        $user = User::factory()->create();

        $default = $user->addresses()->create([
            'recipient_name' => 'A', 'recipient_phone' => '081',
            'address_line' => 'Jl A', 'city' => 'Jakarta', 'postal' => '11111', 'is_default' => true,
        ]);
        $next = $user->addresses()->create([
            'recipient_name' => 'B', 'recipient_phone' => '082',
            'address_line' => 'Jl B', 'city' => 'Bogor', 'postal' => '33333', 'is_default' => false,
        ]);

        $this->actingAs($user)->deleteJson("/addresses/{$default->id}")->assertOk();

        $this->assertDatabaseMissing('addresses', ['id' => $default->id]);
        $this->assertTrue($next->fresh()->is_default);
    }
}