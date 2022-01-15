<?php


namespace App\Http\Controllers;

use App\AllItem;
use App\CaseItem;
use App\Contract;
use App\Open;
use App\Style;
use Illuminate\Http\Request;
use Auth;

class ContractsController extends Controller
{

    public function loadItems(Request $r)
    {
        if (Auth::guest()) return response()->json(['items' => [], 'more' => false]);

        $opens = Open::with(['box', 'item'])->where('user_id', $this->user->id)->where('status', 0)->orderBy('id', 'desc')->paginate(18);
        $more = true;
        $items = [];

        if ($opens->lastPage() === $opens->currentPage()) $more = false;

        foreach ($opens as $open) {
            $open->item->name_first = CaseItem::name_first($open->item->market_hash_name);
            $open->item->name_second = CaseItem::name_second($open->item->market_hash_name);

            $open->item->name_first_en = CaseItem::name_first($open->item->market_hash_name_en);
            $open->item->name_second_en = CaseItem::name_second($open->item->market_hash_name_en);

            $open->item->style = Style::getStyle($open->item->type)->style;

            $items[] = [
                'id' => $open->id,
                'box' => $open->box,
                'item' => $open->item,
                'added' => false
            ];
        }

        return [
            'items' => $items,
            'more' => $more
        ];
    }

    public function create(Request $r)
    {
        if (Auth::guest()) return ['success' => false, 'message' => 'auth'];

        $slots = $r->slots;
        $items = [];

        foreach ($slots as $slot) {
            if ($slot['type'] === 'slot') {
                $items[] = $slot['id'];
            }
        }

        if (count($items) < 3) return ['success' => false, 'message' => 'min'];

        $price = 0;

        foreach ($items as $id) {
            $open = Open::with(['item'])->where('status', 0)->where('id', $id)->first();
            if (!$open) return ['success' => false, 'message' => 'error_1'];
            $price += $open->item->price;
        }

        $min = ceil($price * 0.1);
        $max = $price * 3;
        $chance = mt_rand(0, 100);

        if ($chance >= 80) {
            $winItem = AllItem::where('appId', $this->config->game_id)->where('price', '>=', $price)->where('price', '<=', $max)->inRandomOrder()->first();
        } else {
            $winItem = AllItem::where('appId', $this->config->game_id)->where('price', '>=', $min)->where('price', '<=', $price)->inRandomOrder()->first();
        }

        if (!$winItem) return ['success' => false, 'message' => 'error_2'];

        foreach ($items as $id) {
            Open::where('id', $id)->update(['status' => Open::CONTRACTS]);
        }

        Contract::create([
            'user_id' => $this->user->id,
            'item_id' => $winItem->id,
            'items' => json_encode($items),
            'price' => $price
        ]);

        $open = Open::create([
            'user_id' => $this->user->id,
            'case_id' => 1,
            'item_id' => $winItem->id,
            'price' => $winItem->price
        ]);

        $this->redis->publish('liveDrop', json_encode([
            'type' => 'fast',
            'live' => CasesController::liveDropStatic()
        ]));

        return [
            'success' => true,
            'data' => [
                'id' => $open->id,
                'name' => CaseItem::name_first($winItem->market_hash_name).' | '.CaseItem::name_second($winItem->market_hash_name),
                'name_en' => CaseItem::name_first($winItem->market_hash_name_en).' | '.CaseItem::name_second($winItem->market_hash_name_en),
                'image' => '//steamcommunity-a.akamaihd.net/economy/image/' . $winItem->image . '/250fx115f',
                'price' => $winItem->price,
                'price_en' => $winItem->price_en
            ]
        ];
    }
}