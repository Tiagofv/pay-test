<?php


namespace Tests;


use App\Models\User;

class Helpers
{
    /**
     * Creates a user
     * .
     *
     * @param array $data
     * @return User
     */
    public function createUser($data = [], float $walletValue = 0): User
    {
        $user = User::factory()->create($data);
        $user->wallet()->update([
            'value' => $walletValue
        ]);
        return $user;
    }


}
