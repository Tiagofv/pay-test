<?php

namespace Tests\Feature;

use App\Models\Transfer;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers;
use Tests\TestCase;

class TransferTest extends TestCase
{
    use RefreshDatabase;

    private $baseUrl = 'api/transfers';


    public function test_if_a_seller_user_can_see_his_transfers()
    {
        $user = (new Helpers())->createUser(['type' => 'seller']);
        $this->actingAs($user, 'api');
        Transfer::factory()->create(['payee_id' => $user->id]);
        $response = $this->json('GET', $this->baseUrl);
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    public function test_if_a_common_user_can_see_his_transfers()
    {
        $user = (new Helpers())->createUser();
        $this->actingAs($user, 'api');
        Transfer::factory()->create(['payer_id' => $user->id]);
        $response = $this->json('GET', $this->baseUrl);
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    public function test_user_cant_make_transfer_without_balance()
    {
        $user = (new Helpers())->createUser(['type' => 'common']);
        $this->actingAs($user, 'api');
        $transferRaw = Transfer::factory()->raw(['payee_id' => $user->id]);
        $response = $this->json('POST', $this->baseUrl, $transferRaw);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['balance']);
    }

    public function test_user_can_make_a_transfer()
    {
        $this->withoutExceptionHandling();
        $user = (new Helpers())->createUser(['type' => 'common'], 101);
        $this->actingAs($user, 'api');
        $transferRaw = Transfer::factory()->raw(['payer_id' => $user->id, 'amount' => 100]);
        $response = $this->json('POST', $this->baseUrl, $transferRaw);
        $response->assertStatus(200);
    }

    public function test_user_can_make_a_transfer_and_wallets_are_synced()
    {
        $payer = (new Helpers())->createUser(['type' => 'common'], 101);
        $payee = (new Helpers())->createUser(['type' => 'seller'], 0);
        $this->actingAs($payer, 'api');
        $transferRaw = Transfer::factory()->raw([
            'payer_id' => $payer->id,
            'amount' => 100,
            'payee_id' => $payee->id
        ]);
        $response = $this->json('POST', $this->baseUrl, $transferRaw);
        $response->assertStatus(200);
        $this->assertTrue(Wallet::where('id', $payee->id)->first()->value === 100.0);
        $this->assertTrue(Wallet::where('id', $payer->id)->first()->value === 1.0);
    }

    public function test_seller_cant_make_a_transfer()
    {
        $user = (new Helpers())->createUser(['type' => 'seller'], 101);
        $this->actingAs($user, 'api');
        $transferRaw = Transfer::factory()->raw(['payer_id' => $user->id, 'amount' => 100]);
        $response = $this->json('POST', $this->baseUrl, $transferRaw);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['type']);
    }

    public function test_unauthenticated_user_cant_make_a_transfer()
    {
        $response = $this->json('POST', $this->baseUrl, []);
        $response->assertStatus(401);
    }

    public function test_transfer_required_fields()
    {
        $user = (new Helpers())->createUser(['type' => 'common']);
        $this->actingAs($user, 'api');
        $response = $this->json('POST', $this->baseUrl, []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['payee_id', 'amount']);
    }
}
