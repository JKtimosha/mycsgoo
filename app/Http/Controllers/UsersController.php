<?php

namespace App\Http\Controllers;

use App\CaseItem;
use App\Open;
use App\Style;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Cache;
use Psy\Util\Str;

class UsersController extends Controller
{
    public function getBalance()
    {
        if (Auth::check()) return $this->user->balance;
    }

    public function sell(Request $r)
    {
        if (Auth::guest()) return ['type' => 'error', 'message' => 'auth'];

        $ids = $r->id;

        if (!is_array($r->id)) $ids = [$r->id];

        foreach ($ids as $id) {
            $open = Open::with(['item'])->where('user_id', $this->user->id)->where('status', 0)->where('id', $id)->first();
            if (!$open) continue;
            $open->update([
                'status' => Open::SELL
            ]);
            $this->user->update([
                'balance' => $this->user->balance + $open->item->price
            ]);
        }

        if (count($ids) > 1) {
            return ['type' => 'success', 'message' => 'items_selled'];
        } else {
            return ['type' => 'success', 'message' => 'item_selled'];
        }
    }

    public function sellAll()
    {
        if (Auth::guest()) return ['type' => 'error', 'message' => 'auth'];

        $opens = Open::where('user_id', $this->user->id)->where('status', 0)->get();

        if (count($opens) === 0) return ['type' => 'error', 'message' => 'not_sell'];

        foreach ($opens as $open) {
            $open->update([
                'status' => Open::SELL
            ]);
            $this->user->update([
                'balance' => $this->user->balance + $open->item->price
            ]);
        }

        if (count($opens) > 1) {
            return ['type' => 'success', 'message' => 'items_selled'];
        } else {
            return ['type' => 'success', 'message' => 'item_selled'];
        }
    }

    public function buy(Request $r)
    {
        if (Auth::guest()) return ['type' => 'error', 'message' => 'auth'];
        if (empty($this->user->trade_link)) return ['type' => 'error', 'message' => 'needUrl'];

        if (Cache::has('send.' . $this->user->id)) return ['type' => 'error', 'message' => 'wait'];
        if (Cache::has('send.yes.' . $this->user->id)) return ['type' => 'error', 'message' => 'lastSelled'];
        Cache::put('send.' . $this->user->id, '', 10);

        $id = $r->id;

        $open = Open::with(['item', 'box'])->where('user_id', $this->user->id)->where('status', 0)->where('id', $id)->first();
        if (!$open) return ['type' => 'error', 'message' => 'isSelled'];

        $open->update([
            'status' => Open::SENDING
        ]);

        $inventory = $this->searchInventory($open->id, $open->item->classid, $this->user, $open->item->market_hash_name, $open->box->appId);

        if ($inventory['success']) {
            $open->update([
                'status' => Open::WAIT_ORDER,
                'asset_id' => $inventory['asset_id']
            ]);

            Cache::put('send.yes.' . $this->user->id, '', 60);

            return ['type' => 'success', 'message' => 'sendInBot', 'status' => $open->status];
        }

        if (intval($open->box->appId) === 730) {
            $site = 'https://market.csgo.com';
        } else {
            $site = 'https://market.dota2.net';
        }

        $url = json_decode(file_get_contents('http://api.steampowered.com/ISteamEconomy/GetAssetClassInfo/v0001/?key=4889188262C2B80D2EB8133EF5FC69F2&appid=' . $open->box->appId . '&class_count=1&classid0=' . $open->item->classid . '&language=ru_RU'), true);
        $result = $url['result'];

        if (!$result['success']) {
            $open->update([
                'status' => 0
            ]);
            return ['type' => 'error', 'message' => 'later'];
        }

        $item = $result[$open->item->classid];
        $market_hash_name = $item['market_hash_name'];

        $url = json_decode(file_get_contents($site . '/api/v2/search-item-by-hash-name?key=' . $this->config->bot_key . '&hash_name=' . $market_hash_name), true);

        if (!$url['success'] || !isset($url['data'][0])) {
            $open->update([
                'status' => 0
            ]);
            return ['type' => 'error', 'message' => 'notFound'];
        }

        $item = $url['data'][0];

        if (round(intval($item['price'] / 100) / intval($open->item->price), 2) > 1.1) {
            $open->update([
                'status' => 0
            ]);
            return ['type' => 'error', 'message' => 'notFound1'];
        }

        $custom_id = \Illuminate\Support\Str::random(50);

        if (intval($open->box->appId) === 730) {
            $token = $this->_parseToken($this->user->trade_link);
            $partner = $this->_parsePartner($this->user->trade_link);

            $url = json_decode(file_get_contents($site . '/api/v2/buy-for?key=' . $this->config->bot_key . '&hash_name=' . $item['market_hash_name'] . '&price=' . $item['price'] . '&partner=' . $partner . '&token=' . $token .'&custom_id='.$custom_id), true);
            $type = 'buy_for';
        } else {
            $url = json_decode(file_get_contents($site . '/api/v2/buy?key=' . $this->config->bot_key . '&hash_name=' . $item['market_hash_name'] . '&price=' . $item['price'].'&custom_id='.$custom_id), true);
            $type = 'buy';
        }

        if (!$url['success']) {
            $open->update([
                'status' => 0
            ]);
            return ['type' => 'error', 'message' => 'notFound', 'tp' => $url['error']];
        }

        Cache::put('send.yes.' . $this->user->id, '', 60);
        $open->update([
            'status' => Open::WAIT_SELLER,
            'market_id' => $url['id'],
            'custom_id' => $custom_id,
            'type' => $type
        ]);

        return ['type' => 'success', 'message' => 'requestSend', 'status' => $open->status];
    }

    private function searchInventory($id, $classid, $user, $market_hash_name, $appId)
    {
        try {
            $url = json_decode(file_get_contents('https://steamcommunity.com/profiles/' . $this->config->bot_steamid . '/inventory/json/' . $appId . '/2'), true);

            if ($url['success']) {
                $items = $url['rgInventory'];

                foreach ($items as $item) {
                    if (intval($item['classid']) === $classid) {
                    	if (Open::where('asset_id', $item['id'])->where('status', '>', 0)->where('status', '<', Open::CONTRACTS)->first()) continue;
                        $this->redis->publish('bot.send', json_encode([
                            'id' => $id,
                            'assetID' => $item['id'],
                            'user_id' => $user->id,
                            'username' => $user->username,
                            'market_hash_name' => $market_hash_name,
                            'trade_link' => $user->trade_link
                        ]));
                        return [
                            'success' => true,
                            'id' => $item['id']
                        ];
                    }
                }

                return [
                    'success' => false
                ];
            } else {
                return [
                    'success' => false
                ];
            }
        } catch (\Exception $ex) {
            return [
                'success' => false
            ];
        }
    }

    public function get(Request $r)
    {
        $id = intval($r->id);

        $user = User::find($id);
        if (!$user) return ['success' => false];

        if (Auth::check() && $user->id === $this->user->id) {
            $user->allPrice = Open::
                join('all_items', 'all_items.id', '=', 'opens.item_id')
                ->where('status', 0)
                ->where('user_id', $user->id)
                ->select(
                    \DB::raw('SUM(all_items.price) as myBet'),
                    \DB::raw('SUM(all_items.price_en) as myBet_en')
                )
                ->get();
            $type = 'my';
        } else {
            $user->trade_link = '';
            $type = 'other';
        }

        $opens = Open::where('user_id', $user->id)->count('id');
        $user->open = $opens;

        return [
            'user' => $user,
            'type' => $type,
            'success' => true
        ];
    }

    public function items(Request $r)
    {
        $opens = Open::with(['box', 'item'])->where('user_id', $r->id)->orderBy('id', 'desc')->paginate(18);
        $more = true;
        $items = [];

        if ($opens->lastPage() === $opens->currentPage()) $more = false;

        foreach ($opens as $open) {
            $open->item->name_first = CaseItem::name_first($open->item->market_hash_name);
            $open->item->name_second = CaseItem::name_second($open->item->market_hash_name);
            $open->item->name_first_en = CaseItem::name_first($open->item->market_hash_name_en);
            $open->item->name_second_en = CaseItem::name_second($open->item->market_hash_name_en);
            $open->item->style = Style::getStyle($open->item->type)->style;
            $hover = '';
            if ($open->status === 1 || $open->status === 6 || $open->status === Open::CONTRACTS) $hover = 'item--hover';

            $items[] = [
                'id' => $open->id,
                'hover' => $hover,
                'trade_id' => $open->trade_id,
                'status' => $open->status,
                'box' => $open->box,
                'item' => $open->item
            ];
        }

        return [
            'items' => $items,
            'more' => $more
        ];
    }

    public function saveLink(Request $r)
    {
        $link = $r->trade_link;

        if ($this->_parseTradeLink($link)) {
            $this->user->update([
                'trade_link' => $link
            ]);
            return response()->json(['type' => 'success', 'message' => 'linkSave']);
        } else {
            return response()->json(['type' => 'error', 'message' => 'linkNotSave']);
        }
    }

    private function _parseTradeLink($tradeLink)
    {
        $query_str = parse_url($tradeLink, PHP_URL_QUERY);
        parse_str($query_str, $query_params);
        return isset($query_params['token']) ? $query_params['token'] : false;
    }

    private function _parseToken($tradeLink)
    {
        $query_str = parse_url($tradeLink, PHP_URL_QUERY);
        parse_str($query_str, $query_params);
        return isset($query_params['token']) ? $query_params['token'] : false;
    }

    private function _parsePartner($tradeLink)
    {
        $query_str = parse_url($tradeLink, PHP_URL_QUERY);
        parse_str($query_str, $query_params);
        return isset($query_params['partner']) ? $query_params['partner'] : false;
    }
}
