<?php

namespace App\Http\Controllers;

use App\AllItem;
use App\CaseItem;
use App\Open;
use App\Style;
use App\Upgrade;
use Illuminate\Http\Request;
use Auth;

class UpgradeController extends Controller
{
    public function getMyItems(Request $r)
    {
        if (Auth::guest()) return ['success' => false];

        $opens = Open::
            where('user_id', $this->user->id)
            ->where('status', 0)
            ->orderBy('price', $r->myFilter)
            ->paginate(12);

        $items = [];

        foreach ($opens->items() as $open) {
            $open->item = Open::find($open->id)->item;

            $items[] = [
                'id' => $open->id,
                'item' => [
                    'name_first' => CaseItem::name_first($open->item->market_hash_name),
                    'name_second' => CaseItem::name_second($open->item->market_hash_name),
                    'name_first_en' => CaseItem::name_first($open->item->market_hash_name_en),
                    'name_second_en' => CaseItem::name_second($open->item->market_hash_name_en),
                    'style' => Style::getStyle($open->item->type)->style,
                    'price' => $open->item->price,
                    'price_en' => $open->item->price_en,
                    'image' => 'https://steamcdn.io/economy/image/'.$open->item->image.'/300fx300f/image.png'
                ]
            ];
        }

        return ['success' => true, 'items' => $items, 'current' => $opens->currentPage(), 'last' => $opens->lastPage()];
    }

    public function getSiteItems(Request $r)
    {
        $from = intval($r->siteFrom);
        $to = intval($r->siteTo);

        if ($r->siteRarity === 'all') {
            $opens = AllItem::where('appID', $this->config->game_id)->where('market_hash_name', 'like', '%'.$r->siteSearch.'%')->where('price', '>=', $from)->where('price', '<=', $to)->orderBy('price', $r->siteFilter)->paginate(12);
        } else {
            $opens = AllItem::where('appID', $this->config->game_id)->where('market_hash_name', 'like', '%'.$r->siteSearch.'%')->where('price', '>=', $from)->where('price', '<=', $to)->where('type', $r->siteRarity)->orderBy('price', $r->siteFilter)->paginate(12);
        }

        $items = [];

        foreach ($opens->items() as $open) {
            $items[] = [
                'id' => $open->id,
                'item' => [
                    'name_first' => CaseItem::name_first($open->market_hash_name),
                    'name_second' => CaseItem::name_second($open->market_hash_name),
                    'name_first_en' => CaseItem::name_first($open->market_hash_name_en),
                    'name_second_en' => CaseItem::name_second($open->market_hash_name_en),
                    'style' => Style::getStyle($open->type)->style,
                    'price' => $open->price,
                    'price_en' => $open->price_en,
                    'image' => 'https://steamcdn.io/economy/image/'.$open->image.'/300fx300f/image.png'
                ]
            ];
        }

        return ['success' => true, 'items' => $items, 'current' => $opens->currentPage(), 'last' => $opens->lastPage()];
    }

    public function getOneItem(Request $r)
    {
        $price = $r->price;
        $multiple = $r->multiple;
        $newPrice = ceil($price * $multiple);
        $from = $newPrice - ceil($newPrice * 0.1);
        $to = $newPrice + ceil($newPrice * 0.1);

        $item = AllItem::where('appID', $this->config->game_id)->where('price', '>=', $from)->where('price', '<=', $to)->inRandomOrder()->first();

        if (!$item) return ['success' => false];

        return [
            'success' => true,
            'item' => [
                'id' => $item->id,
                'item' => [
                    'name_first' => CaseItem::name_first($item->market_hash_name),
                    'name_second' => CaseItem::name_second($item->market_hash_name),
                    'name_first_en' => CaseItem::name_first($item->market_hash_name_en),
                    'name_second_en' => CaseItem::name_second($item->market_hash_name_en),
                    'style' => Style::getStyle($item->type)->style,
                    'price' => $item->price,
                    'price_en' => $item->price_en,
                    'image' => 'https://steamcdn.io/economy/image/'.$item->image.'/300fx300f/image.png',
                    'images' => 'https://steamcdn.io/economy/image/'.$item->image.'/300fx300f/image.png'
                ]
            ]
        ];
    }

    public function upgrade(Request $r)
    {
        if (Auth::guest()) return ['success' => false, 'message' => 'auth'];

        $myItem = $r->myItem;
        $siteItem = $r->siteItem;

        $open = Open::where('user_id', $this->user->id)
            ->where('status', 0)
            ->where('id', $myItem['id'])
            ->first();
        $site = AllItem::find($siteItem['id']);

        if (!$open || !$site)  return ['success' => false, 'message' => 'error'];

        $item = Open::find($open->id)->item;

        $percent = round($item->price / $site->price * 100, 2);

        if ($percent > 80) $percent = 80;
        if ($percent <= 0.00) return ['success' => false, 'message' => 'chance'];

        $drop = null;
        $is_win = false;
        $rand = $this->random_float(1, 100);

        $open->update([
            'status' => 3
        ]);

        if ($percent > round($rand, 2)) {
            $is_win = true;

            $winItem = $site;

            $newOpen = Open::create([
                'user_id' => $this->user->id,
                'case_id' => 2,
                'item_id' => $winItem->id,
                'price' => $winItem->price
            ]);

            $this->redis->publish('liveDrop', json_encode([
                'type' => 'upgrade',
                'live' => CasesController::liveDropStatic()
            ]));

            $drop = [
                'id' => $newOpen->id,
                'item' => [
                    'name_first' => CaseItem::name_first($winItem->market_hash_name),
                    'name_second' => CaseItem::name_second($winItem->market_hash_name),
                    'name_first_en' => CaseItem::name_first($winItem->market_hash_name_en),
                    'name_second_en' => CaseItem::name_second($winItem->market_hash_name_en),
                    'style' => Style::getStyle($winItem->type)->style,
                    'price' => $winItem->price,
                    'price_en' => $winItem->price_en,
                    'image' => 'https://steamcdn.io/economy/image/'.$winItem->image.'/300fx300f/image.png',
                    'images' => 'https://steamcdn.io/economy/image/'.$winItem->image.'/300fx300f/image.png'
                ]
            ];
        }

        Upgrade::create([
            'user_id' => $this->user->id,
            'upgrade_id' => $open->id,
            'take_id' => $site->id,
            'is_win' => $is_win,
            'percent' => round($percent, 2),
            'rand' => round($rand, 2)
        ]);

        return [
            'drop' => $drop,
            'is_win' => $is_win,
            'rand' => $rand,
            'success' => true
        ];
    }

    public function random_float($min, $max) {
        return random_int($min, $max - 1) + (random_int(0, PHP_INT_MAX - 1) / PHP_INT_MAX );
    }
}