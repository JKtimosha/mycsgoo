<?php

namespace App\Http\Controllers\Admin;

use App\Seo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SeoController extends Controller
{
    public function load()
    {
        return Seo::orderBy('id', 'asc')->get();
    }

    public function get(Request $r)
    {
        $item = Seo::find($r->id);

        if ($item) {
            return ['success' => true, 'seo' => $item];
        } else {
            return ['success' => false];
        }
    }

    public function edit(Request $r)
    {
        $item = Seo::find($r->seo['id']);

        if ($item) {
            $item->update($r->seo);
            return ['type' => 'success', 'message' => 'Seo изменен'];
        } else {
            return ['type' => 'error', 'message' => 'Seo не найден'];
        }
    }

    public function getterSeo(Request $r)
    {
        $name = $r->name;

        if (strrpos($name, 'battles') !== false) $name = 'battles';
        $default = Seo::where('name', 'default')->first();
        $seo = Seo::where('name', $name)->first();

        if (!$seo || is_null($seo->title)) {
            $seo->title = $this->config->title;
        }

        if (!$seo || is_null($seo->description)) {
            $seo->description = $this->config->description;
        }

        if (!$seo || is_null($seo->keywords)) {
            $seo->keywords = $this->config->keywords;
        }

        return $seo;
    }
}
