<?php

namespace App\Http\Controllers;

use App\Open;
use App\User;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function admin()
    {
        return view('admin');
    }

    public function top()
    {
        $top = [];
        $users = User::orderBy('profit', 'desc')->limit(50)->get();

        foreach ($users as $user) {
            $top[] = (array) [
                'case_count' => Open::where('user_id', $user->id)->count('id'),
                'id' => $user->id,
                'name' => $user->username,
                'photo' => $user->avatar,
                'profit' => $user->profit
            ];
        }

        $topUsers = [];
        if (isset($top[1])) $topUsers[0] = $top[1];
        if (isset($top[0])) $topUsers[1] = $top[0];
        if (isset($top[2])) $topUsers[2] = $top[2];

        return [
            'users' => $top,
            'topUsers' => $topUsers
        ];
    }
}
