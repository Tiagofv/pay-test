<?php

namespace App\Repositories\Contracts;

interface AuthRepositoryInterface
{
    public function all();

    public function store(array $data);

    public function show(int $id);

    public function findByEmail(string $email);
}
