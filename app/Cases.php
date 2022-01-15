<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    protected $table = 'cases';

    protected $fillable = [
        'name', 'name_en', 'name_url', 'category_id', 'image', 'old_price', 'old_price_en', 'price', 'price_en', 'type', 'max_open', 'open', 'appId'
    ];

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }
}
