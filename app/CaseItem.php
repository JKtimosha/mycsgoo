<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseItem extends Model
{
    protected $fillable = [
        'case_id', 'item_id', 'chance'
    ];

    public function box()
    {
        return $this->belongsTo('App\Case', 'case_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\AllItem', 'item_id', 'id');
    }

    public static function name_first($name)
    {
        $names = explode('|', $name);
        if (isset($names[1])) {
            $name_first = trim($names[0]);
        } else {
            $name_first = '';
        }

        return $name_first;
    }

    public static function name_second($name) {
        $names = explode('|', $name);
        if (isset($names[1])) {
            $name_second = trim($names[1]);
        } else {
            $name_second = trim($names[0]);
        }

        return $name_second;
    }
}
