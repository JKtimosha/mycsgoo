<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Open extends Model
{
    const SELL = 1;
    const SENDING = 2;
    const WAIT_SELLER = 3;
    const WAIT_ORDER = 4;
    const ORDER_READY = 5;
    const SEND = 6;
    const CONTRACTS = 7;

    protected $fillable = [
        'user_id', 'case_id', 'item_id', 'price', 'status', 'market_id', 'trade_id', 'custom_id', 'asset_id', 'type'
    ];

    public function box()
    {
        return $this->belongsTo('App\Cases', 'case_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\AllItem', 'item_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

}
