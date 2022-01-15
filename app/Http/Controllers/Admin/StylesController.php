<?php

namespace App\Http\Controllers\Admin;

use App\Style;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StylesController extends Controller
{
    public function load()
    {
        return Style::orderBy('number', 'desc')->get();
    }

    public function create(Request $r)
    {
        Style::create($r->style);

        return ['success' => true];
    }

    public function get(Request $r)
    {
        $item = Style::find($r->id);

        if ($item) {
            return ['success' => true, 'style' => $item];
        } else {
            return ['success' => false];
        }
    }

    public function edit(Request $r)
    {
        $item = Style::find($r->style['id']);

        if ($item) {
            $item->update($r->style);
            return ['type' => 'success', 'message' => 'Стиль изменен'];
        } else {
            return ['type' => 'error', 'message' => 'Стиль не найден'];
        }
    }

    public function del(Request $r)
    {
        $item = Style::find($r->id);

        if ($item) {
            $item->delete();
            return ['type' => 'success', 'message' => 'Стиль удален'];
        } else {
            return ['type' => 'error', 'message' => 'Стиль не найден'];
        }
    }
}
