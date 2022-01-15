<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    protected $fillable = [
        'type', 'style', 'number'
    ];

    public static function getStyle($type) {
        $styles = Style::where('type', $type)->orWhere(['style' => $type, 'number' => $type])->first();

        if ($styles) return $styles;
        return Style::orderBy('number', 'asc')->first();
    }
}
