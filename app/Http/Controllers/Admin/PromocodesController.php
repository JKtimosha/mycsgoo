<?php

namespace App\Http\Controllers\Admin;

use App\Promocode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PromocodesController extends Controller
{
    public function load()
    {
        $promocodes = Promocode::get();

        foreach ($promocodes as $promo) {
            $promo->use = $promo->use()->count();
        }

        return $promocodes;
    }

    public function create(Request $r)
    {
        Promocode::create($r->promocode);

        return ['success' => true];
    }

    public function get(Request $r)
    {
        $item = Promocode::find($r->id);

        if ($item) {
            return ['success' => true, 'promocode' => $item];
        } else {
            return ['success' => false];
        }
    }

    public function edit(Request $r)
    {
        $item = Promocode::find($r->promocode['id']);

        if ($item) {
            $item->update($r->promocode);
            return ['type' => 'success', 'message' => 'Промокод изменен'];
        } else {
            return ['type' => 'error', 'message' => 'Промокод не найден'];
        }
    }

    public function del(Request $r)
    {
        $item = Promocode::find($r->id);

        if ($item) {
            $item->delete();
            return ['type' => 'success', 'message' => 'Промокод удален'];
        } else {
            return ['type' => 'error', 'message' => 'Промокод не найден'];
        }
    }

    public function checkPromocode(Request $r)
    {
        $promo = Promocode::where('name', $r->code)->first();

        if ($promo) {
            return ['success' => true, 'percent' => $promo->percent];
        } else {
            return ['success' => false];
        }
    }
}
