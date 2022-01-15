<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Auth;

class FbController extends Controller
{
    public function redirectToFb()
    {
        return Socialite::with('facebook')->redirect();
    }

    public function handle()
    {
        $user = Socialite::driver('facebook')->user();

        $userBD = User::where('fb_id', $user->id)->first();

        if (!is_null($userBD)) {
            BaseController::isFake($userBD);
            $userBD->update([
                'username' => $user->name,
                'avatar' => $user->avatar,
                'fb_id' => $user->id
            ]);
        } else {
            $userBD = User::create([
                'username' => $user->name,
                'avatar' => $user->avatar,
                'fb_id' => $user->id
            ]);
        }

        Auth::login($userBD, true);

        return redirect('/');
    }
}
