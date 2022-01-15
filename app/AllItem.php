<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllItem extends Model
{
    protected $fillable = [
        'appID', 'market_hash_name', 'market_hash_name_en', 'classid', 'image', 'price', 'price_en', 'type'
    ];
}
