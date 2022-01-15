<?php

namespace App\Http\Controllers\Admin;

use App\Battle;
use App\Contract;
use App\Open;
use App\Payment;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function load()
    {
        return datatables(User::query())->toJson();
    }

    public function get(Request $r)
    {
        $user = User::find(intval($r->id));

        if (!$user) return ['success' => false];

        $info = (object) [];
        $info->case_count = Open::where('user_id', $user->id)->count('id');
        $info->payments = Payment::where('user_id', $user->id)->where('status', 1)->sum('sum');
        $info->chance = $user->chance_roulette;
        $info->contracts = Contract::where('user_id', $user->id)->count('id');

        $games = Battle::where('status', 2)->orderBy('id', 'desc')->get();
        $x = 0;
        foreach ($games as $game) {
            $users = json_decode($game->users, true);

            foreach ($users as $user1) {
                if ($user1['id'] === $user->id) {
                    $x++;
                }
            }
        }

        $info->battles = $x;

        return ['success' => true, 'user' => $user, 'info' => $info];
    }

    public function save(Request $r)
    {
        $user = User::find(intval($r->id));

        if (!$user) return ['type' => 'error', 'message' => 'Пользователь не найден'];

        $user->update($r->user);
        $user->update([
            'chance_roulette' => intval($r->chance)
        ]);

        return ['type' => 'success', 'message' => 'Пользователь обновлен'];
    }

    public function getSteamid(Request $r)
    {
        $url = json_decode(file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=4889188262C2B80D2EB8133EF5FC69F2&steamids='. $r->steamid), true);

        return $url['response']['players'][0];
    }

    public function create(Request $r)
    {
        if (User::where('steamid64', $r->user['steamid'])->first()) {
            return [
                'type' => 'error',
                'message' => 'Такой пользователь уже существует',
                'success' => false
            ];
        }

        User::create([
            'username' => $r->user['personaname'],
            'steamid64' => $r->user['steamid'],
            'avatar' => $r->user['avatarfull'],
            'type' => $r->type
        ]);

        return [
            'type' => 'success',
            'message' => 'Пользователь создан',
            'success' => true
        ];
    }
}
