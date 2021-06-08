<?php

namespace Tests\Unit;

use App\Models\Transfer;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers;

class TransferTest extends \Tests\TestCase
{
    use RefreshDatabase;

    /**
     * tests the observer.
     *
     * @return void
     */
    public function test_it_syncs_after_transfer()
    {
        $payer = (new Helpers())->createUser(['type' => 'common'], 101);
        $payee = (new Helpers())->createUser(['type' => 'seller'], 0);
        Transfer::factory()->create([
            'payer_id' => $payer->id,
            'amount' => 100,
            'payee_id' => $payee->id
        ]);
        $this->assertTrue(Wallet::where('id', $payee->id)->first()->value === 100.0);
        $this->assertTrue(Wallet::where('id', $payer->id)->first()->value === 1.0);
    }
}
