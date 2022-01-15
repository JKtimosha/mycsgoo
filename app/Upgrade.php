<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upgrade extends Model
{
    protected $fillable = [
        'user_id', 'upgrade_id', 'take_id', 'is_win', 'percent', 'rand'
    ];
}
