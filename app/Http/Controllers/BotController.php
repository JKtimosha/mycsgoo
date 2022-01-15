<?php

namespace App\Http\Controllers;

use App\Open;
use App\User;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function checkItems()
    {
        $items = Open::with(['box'])->where('status', Open::WAIT_SELLER)->orWhere('status', Open::ORDER_READY)->orWhere('status', Open::WAIT_ORDER)->get();

        foreach ($items as $item) {
            if (intval($item->box->appId) === 730) {
                $site = 'https://market.csgo.com';
            } else {
                $site = 'https://market.dota2.net';
            }

            if (!$item->custom_id) continue;

            $url = json_decode(file_get_contents($site.'/api/v2/get-buy-info-by-custom-id?key='.$this->config->bot_key.'&custom_id='.$item->custom_id), true);
            if ($url['success']) {
                $data = $url['data'];
                if (intval($item->box->appId) === 730) {
                    if ($data['stage'] === "1" && $data['trade_id'] !== null && ($item->trade_id === null || $item->trade_id === 0)) {
                        $item->update([
                            'status' => Open::ORDER_READY,
                            'trade_id' => $data['trade_id'],
                            'user_id' => $item->user_id
                        ]);

                        $this->redis->publish('setItemStatus', json_encode([
                            'id' => $item->id,
                            'status' => $item->status,
                            'trade_id' => $data['trade_id']
                        ]));
                    }
                    if ($data['stage'] === "2") {
                        $item->update([
                            'status' => Open::SEND
                        ]);

                        $this->redis->publish('setItemStatus', json_encode([
                            'id' => $item->id,
                            'status' => $item->status,
                            'user_id' => $item->user_id
                        ]));
                    }
                    if ($data['stage'] === "5") {
                        $item->update([
                            'status' => 0,
                            'market_id' => NULL,
                            'custom_id' => NULL,
                            'trade_id' => NULL,
                            'asset_id' => NULL,
                            'type' => NULL
                        ]);

                        $this->redis->publish('setItemStatus', json_encode([
                            'id' => $item->id,
                            'status' => $item->status,
                            'user_id' => $item->user_id
                        ]));
                    }
                } else {
                    if ($data['stage'] === "5") {
                        $item->update([
                            'status' => 0,
                            'market_id' => NULL,
                            'custom_id' => NULL,
                            'trade_id' => NULL,
                            'asset_id' => NULL
                        ]);

                        $this->redis->publish('setItemStatus', json_encode([
                            'id' => $item->id,
                            'status' => $item->status,
                            'user_id' => $item->user_id
                        ]));
                    }
                }
            }
        }
    }

    public function getSettings()
    {
        return [
            'appId' => $this->config->game_id,
            'apiKey' => $this->config->bot_key,
            'steamid' => $this->config->bot_steamid,
            'username' => $this->config->bot_username,
            'password' => $this->config->bot_password,
            'shared_secret' => $this->config->bot_shared_secret,
            'identity_secret' => $this->config->bot_identity_secret
        ];
    }

    public function setStatus(Request $r)
    {
        if ($r->status === 0) {
            Open::where('id', $r->id)->update([
                'status' => 0,
                'market_id' => NULL,
                'custom_id' => NULL,
                'trade_id' => NULL,
                'asset_id' => NULL
            ]);
        } else {
            Open::where('id', $r->id)->update(['status' => $r->status, 'trade_id' => $r->trade_id]);
        }
    }

    public function getOrders()
    {
        $opens = Open::with(['item', 'user'])->where('status', Open::WAIT_SELLER)->where('type', 'buy')->get();

        return $opens;
    }

    public function getOrder(Request $r)
    {
        $classid = $r->classid;

        $opens = Open::with(['item', 'user'])
            ->where('status', Open::WAIT_SELLER)
            ->where('type', 'buy')
            ->get();

        $item = null;

        foreach ($opens as $open) {
            if (intval($open->item->classid) === intval($classid)) {
                $item = $open;
                break;
            }
        }

        return $item;
    }

    public function getUserFromOrder(Request $r)
    {
        $open = Open::find($r->id);
        if (!$open) return ['user_id' => 0];
        return ['user_id' => $open->user_id];
    }
}
