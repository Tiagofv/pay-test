<?php


namespace App\Repositories\Contracts;


interface WalletRepositoryInterface
{
    public function all();

    public function store(array $data);

    public function show($id);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function syncWallet($transfer);
}
