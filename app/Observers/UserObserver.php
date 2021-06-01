<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Creates the wallet automatically when the user is created
     * @param User $user
     */
    public function created(User $user)
    {
        $user->wallet()->create([
            'user_id' => $user->id,
            'value' => 0
        ]);
    }
}
