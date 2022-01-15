<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
    protected $fillable = [
        'id', 'user_id', 'user_win_id', 'price', 'win_price', 'players', 'rounds', 'rounds_history', 'type', 'cases', 'users', 'win_items', 'status'
    ];
}
