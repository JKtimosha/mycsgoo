<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    protected $fillable = [
        'name', 'percent'
    ];

    public function use()
    {
        return $this->belongsTo('App\Payment', 'id', 'promocode_id');
    }
}
