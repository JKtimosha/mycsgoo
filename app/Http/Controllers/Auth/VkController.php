<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Auth;

class VkController extends Controller
{
    public function redirectToVK()
    {
        return Socialite::with('vkontakte')->redirect();
    }

    public function handle()
    {
        $user = Socialite::driver('vkontakte')->user();

        $userBD = User::where('vk_id', $user->id)->first();

        if (!is_null($userBD)) {
            BaseController::isFake($userBD);
            $userBD->update([
                'username' => $user->name,
                'avatar' => $user->avatar,
                'vk_id' => $user->id
            ]);
        } else {
            $userBD = User::create([
                'username' => $user->name,
                'avatar' => $user->avatar,
                'vk_id' => $user->id
            ]);
        }

        Auth::login($userBD, true);

        return redirect('/');
    }
}
