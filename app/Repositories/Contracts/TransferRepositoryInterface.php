<?php

namespace App\Repositories\Contracts;

interface TransferRepositoryInterface
{
    public function all();

    public function allPerUser($user, int $paginate = 15);

    public function store(array $data);

    public function show(int $id);

    public function verifiesUserHasBalance($user, float $amount);

}
