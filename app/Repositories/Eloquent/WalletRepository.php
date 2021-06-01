<?php


namespace App\Repositories\Eloquent;


use App\Models\Wallet;
use App\Repositories\Contracts\WalletRepositoryInterface;

class WalletRepository extends BaseRepository implements WalletRepositoryInterface
{
    protected $model = Wallet::class;


    public function syncWallet($transfer){
        $payerWallet = $transfer->payer->wallet;
        $payerWallet->value = $payerWallet->value - $transfer->amount;
        $payerWallet->update();

        $payeeWallet = $transfer->payee->wallet;
        $payeeWallet->value = $payeeWallet->value + $transfer->amount;
        $payeeWallet->update();
        return $payerWallet;
    }
}
