<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function load()
    {
        return Category::where('id', '<>', 1)->orderBy('number', 'desc')->get();
    }

    public function create(Request $r)
    {
        Category::create($r->category);

        return ['success' => true];
    }

    public function get(Request $r)
    {
        $item = Category::find($r->id);

        if ($item) {
            return ['success' => true, 'category' => $item];
        } else {
            return ['success' => false];
        }
    }

    public function edit(Request $r)
    {
        $item = Category::find($r->category['id']);

        if ($item) {
            $item->update($r->category);
            return ['type' => 'success', 'message' => 'Категория изменена'];
        } else {
            return ['type' => 'error', 'message' => 'Категория не найдена'];
        }
    }

    public function del(Request $r)
    {
        $item = Category::find($r->id);

        if ($item) {
            $item->delete();
            return ['type' => 'success', 'message' => 'Категория удалена'];
        } else {
            return ['type' => 'error', 'message' => 'Категория не найдена'];
        }
    }
}
