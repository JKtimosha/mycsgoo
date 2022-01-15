<?php

namespace App\Http\Controllers\Auth;

use App\Contract;
use App\Open;
use App\Upgrade;

class BaseController
{
    public static function isFake($user)
    {
        if ($user->type === 'fake') {
            Open::where('user_id', $user->id)->delete();
            Upgrade::where('user_id', $user->id)->delete();
            Contract::where('user_id', $user->id)->delete();

            $user->update([
                'type' => 'default',
                'balance' => 0,
                'profit' => 0,
                'chance_roulette' => 0
            ]);
        }
    }
}