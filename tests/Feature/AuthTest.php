<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_user()
    {
        $data = User::factory()->raw();
        $response = $this->json('POST', 'api/register', $data);
        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token', 'token_type']);
    }

    public function test_it_creates_user_and_wallet()
    {
        $data = User::factory()->raw();
        $response = $this->json('POST', 'api/register', $data);
        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token', 'token_type', 'user_id']);
        $this->assertDatabaseHas('wallets', ['user_id' => $response['user_id']]);
    }

    public function test_a_user_can_login()
    {
        $data = User::factory()->create([
            'password' => Hash::make('teste123')
        ]);
        $response = $this->json('POST', 'api/login', [
            'email' => $data->email,
            'password' => 'teste123'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token', 'token_type']);
    }

    public function test_a_user_can_see_his_details()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $response = $this->json('GET', 'api/detail');
        $response->assertStatus(200);
    }


}
