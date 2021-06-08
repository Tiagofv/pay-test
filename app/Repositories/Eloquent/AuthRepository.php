<?php


namespace App\Repositories\Eloquent;


use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;

class AuthRepository extends BaseRepository implements AuthRepositoryInterface
{
    protected $model = User::class;


    public function findByEmail(string $email) : User
    {
        return $this->model->where('email', $email)->firstOrFail();
    }
}
