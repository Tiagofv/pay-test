<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use DatabaseTransactions;

    private $baseUrl = 'api/wallets';

    public function test_it_show_all_wallets()
    {
        $this->actingAs((new Helpers())->createUser(), 'api');
        Wallet::factory(10)->create();

        $response = $this->json('GET', $this->baseUrl);

        $response->assertStatus(200);
    }

    public function test_it_show_a_specific_wallet()
    {
        $this->actingAs((new Helpers())->createUser(), 'api');
        $wallet = Wallet::factory()->create();
        $response = $this->json('GET', $this->baseUrl.'/'.$wallet->id);
        $response->assertStatus(200);
    }
}
