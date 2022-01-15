<?php

namespace App\Http\Controllers\Admin;

ini_set('memory_limit', '256M');
ini_set('max_execution_time', '0');

use App\AllItem;
use App\Battle;
use App\CaseItem;
use App\Open;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemsController extends Controller
{
    public function load()
    {
        if ($this->config->game_id) return datatables(AllItem::query()->where('appID', $this->config->game_id))->toJson();
        return datatables(AllItem::query())->toJson();
    }

    public function get(Request $r)
    {
        $item = AllItem::find($r->id);

        if ($item) {
            return ['success' => true, 'item' => $item];
        } else {
            return ['success' => false];
        }
    }

    public function edit(Request $r)
    {
        $item = AllItem::find($r->item['id']);

        if ($item) {
            $item->update($r->item);
            return ['type' => 'success', 'message' => 'Предмет изменен'];
        } else {
            return ['type' => 'error', 'message' => 'Предмет не найден'];
        }
    }

    public function del(Request $r)
    {
        $item = AllItem::find($r->id);

        if ($item) {
            $games = Battle::where('status', 2)->orderBy('id', 'desc')->get();

            foreach ($games as $game) {
                $rounds = json_decode($game->rounds_history, true);

                foreach ($rounds as $round) {
                    if ($round['item']->id === $item->id) {
                        $game->delete();
                    }
                }
            }

            Open::where('case_id', $item->id)->delete();

            $item->delete();
            return ['type' => 'success', 'message' => 'Предмет удален'];
        } else {
            return ['type' => 'error', 'message' => 'Предмет не найден'];
        }
    }

    public function create(Request $r)
    {
        $classid = $r->classid;
        $appId = $r->appId;

        $url = json_decode(file_get_contents('http://api.steampowered.com/ISteamEconomy/GetAssetClassInfo/v0001/?key=4889188262C2B80D2EB8133EF5FC69F2&appid='.$appId.'&class_count=1&classid0='.$classid.'&language=ru_RU'), true);
        $prices = json_decode(file_get_contents('http://steamp.ru/v2/?key=a76e13efc9b3c8262704d78ba820a210&appid='.$this->config->game_id), true);

        if (!$prices['success']) return ['type' => 'error', 'message' => 'Не удалось загрузить цены предметов'];

        $result = $url['result'];

        if ($result['success']) {
            $item = $result[$classid];
            $price = 0;

            if (isset($prices['items'][$item['market_hash_name']])) $price = ceil($prices['items'][$item['market_hash_name']]['price']);

            if ($price === 0) return ['type' => 'error', 'message' => 'Для предмета не определена цена'];

            $rarities = $item['type'];
            $rarity = explode(", ", $rarities);

            $db = AllItem::create([
                'appID' => $appId,
                'market_hash_name' => $item['name'],
                'classid' => $classid,
                'image' => $item['icon_url'],
                'price' => $price,
                'type' => $rarity[1]
            ]);

            return ['type' => 'success', 'message' => 'Предмет '.$db->market_hash_name.' добавлен'];
        } else {
            return ['type' => 'error', 'message' => 'Предмет не найден'];
        }
    }

    public function updatePrices(Request $r)
    {
        $appId = $r->appId;
        $prices = json_decode(file_get_contents('http://steamp.ru/v2/?key=a76e13efc9b3c8262704d78ba820a210&classid=true&appid='.$appId), true);

        if (!$prices['success']) return ['type' => 'error', 'message' => 'Не удалось загрузить цены предметов'];

        if ($prices['success']) {
            $prices = $prices['items'];

            foreach ($prices as $item) {
                AllItem::where('classid', $item['classid'])->update([
                    'price' => ceil($item['price'])
                ]);
            }

            return ['type' => 'success', 'message' => 'Цены обновлены'];
        }
    }
}
