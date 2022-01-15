<?php

namespace App\Http\Controllers;

use App\AllItem;
use App\Battle;
use App\CaseItem;
use App\Cases;
use App\Category;
use App\Contract;
use App\Open;
use App\Payment;
use App\Style;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

class CasesController extends Controller
{
    public function get(Request $r)
    {
        $cases = [];

        foreach (Category::where('id', '<>', 1)->orderBy('number', 'asc')->get() as $cat) {
            $boxs = [];
            $boxes = Cases::where('category_id', $cat->id)->where('appId', $r->appId)->get();

            foreach ($boxes as $box) {
                if ($box->max_open) {
                    $box->progress = ($box->max_open - $box->open) / $box->max_open * 100;

                    $class_name = 'limit-case__progress';
                    if ($box->progress < 85 && $box->progress > 35) {
                        $class_name .= ' limit-case__progress--warning';
                    } else if ($box->progress < 35) {
                        $class_name .= ' limit-case__progress--danger';
                    }

                    $box->class_name = $class_name;
                }
                if ($box->type === 'free') {
                    array_unshift($boxs, $box);
                } else {
                    $boxs[] = $box;
                }
            }

            if (empty($boxs)) continue;

            $cases[] = [
                'category_name_ru' => $cat->name,
                'category_name_en' => $cat->name_en,
                'cases' => $boxs
            ];
        }

        return $cases;
    }

    public function one(Request $r)
    {
        if ($r->name_url === 'contracts') return ['success' => false];
        $case = Cases::where('name_url', $r->name_url)->first();

        if (!$case) return ['success' => false];

        $case->active = true;

        if ($case->open === $case->open_max) $case->active = false;

        $itemsStyles = [];
        $items = [];
        $itemsAll = CaseItem::with(['item'])->where('case_id', $case->id)->get();

        foreach ($itemsAll as $item) {
            $style = Style::getStyle($item->item->type);
            $item->item->style = $style->style;
            $item->item->name_first = CaseItem::name_first($item->item->market_hash_name);
            $item->item->name_second = CaseItem::name_second($item->item->market_hash_name);
            $item->item->name_first_en = CaseItem::name_first($item->item->market_hash_name_en);
            $item->item->name_second_en = CaseItem::name_second($item->item->market_hash_name_en);
            $itemsStyles[$style->number][] = $item->item;
        }

        for ($i = 1; $i <= Style::orderBy('number', 'asc')->count('id'); $i++) {
            if (isset($itemsStyles[$i])) {
                foreach ($itemsStyles[$i] as $d) {
                    $items[] = $d;
                }
            }
        }

        return [
            'success' => true,
            'box' => $case,
            'items' => $items
        ];
    }

    public function open(Request $r)
    {
        $id = intval($r->id);
        $count = intval($r->count);
        $type = $r->type;

        if (Auth::guest()) return ['success' => false, 'message' => 'Авторизуйтесь'];

        $case = Cases::find($id);

        if (!$case) return ['success' => false, 'message' => 'refresh'];
        if ($case->id === 1 || $case->id === 2) return ['success' => false, 'message' => 'refresh'];
        if ($count !== 1 && $count !== 2 && $count !== 3 && $count !== 4 && $count !== 5 && $count !== 10) return ['success' => false, 'message' => 'count'];
        if ($type !== 'default' && $type !== 'fast') return ['success' => false, 'message' => 'type'];
        if ($this->user->balance < ($case->price * $count)) return ['success' => false, 'message' => 'balance'];
        if ($case->type === 'limited' && ($case->open >= $case->max_open)) return ['success' => false, 'message' => 'unavailable'];
        if ($case->type === 'limited' && $count > ($case->max_open - $case->open)) return ['success' => false, 'message' => 'rest'];

        if ($case->type === 'free') {
            $free = $this->statusFreeCase($this->user, $case);
            $count = 1;
            if (!$free['type']) return $free;
        }

        $winItems = [];
        $allPrice = 0;

        for ($i = 0; $i < $count; $i++) {
            $winItem = $this->winItem($this->user, $case);
            if (is_null($winItem)) return ['success' => false, 'message' => 'refresh'];

            $winItem->name_first = CaseItem::name_first($winItem->market_hash_name);
            $winItem->name_second = CaseItem::name_second($winItem->market_hash_name);

            $winItem->name_first_en = CaseItem::name_first($winItem->market_hash_name_en);
            $winItem->name_second_en = CaseItem::name_second($winItem->market_hash_name_en);

            $winItem->style = Style::getStyle($winItem->type)->style;

            $allPrice += $winItem->price;

            $winItems[] = [
                'id' => 0,
                'item' => $winItem
            ];
        }

        $this->user->update([
            'balance' => $this->user->balance - ($case->price * $count),
            'profit' => $this->user->profit + ($allPrice - ($case->price * $count))
        ]);

        $case->update([
            'open' => $case->open + ($count)
        ]);

        for ($i = 0; $i < count($winItems); $i++) {
            $open = Open::create([
                'user_id' => $this->user->id,
                'case_id' => $case->id,
                'item_id' => $winItems[$i]['item']['id'],
                'price' => $winItems[$i]['item']['price']
            ]);

            $winItems[$i]['id'] = $open->id;
        }

        $this->redis->publish('liveDrop', json_encode([
            'type' => $type,
            'live' => $this->liveDrop()
        ]));

        return [
            'success' => true,
            'data' => $winItems
        ];
    }

    public function liveDrop()
    {
        $opens = Open::with(['box', 'item', 'user'])->where('user_id', '<>', 0)->orderBy('id', 'desc')->limit(21)->get();
        $drops = [];

        foreach ($opens as $open) {
            $drops[] = [
                'user' => [
                    'id' => $open->user->id,
                    'username' => $open->user->username
                ],
                'box' => [
                    'image' => $open->box->image
                ],
                'item' => [
                    'market_hash_name' => $open->item->market_hash_name,
                    'image' => $open->item->image,
                    'style' => Style::getStyle($open->item->type)->style
                ]
            ];
        }

        return [
            'stats' => [
                'live' => $drops, 'opens' => Open::count('id'), 'users' => User::count('id'), 'contracts' => Contract::count('id'), 'battles' => Battle::where('status', 2)->count('id')
            ]
        ];
    }

    public static function liveDropStatic()
    {
        $opens = Open::with(['box', 'item', 'user'])->where('user_id', '<>', 0)->orderBy('id', 'desc')->limit(21)->get();
        $drops = [];

        foreach ($opens as $open) {
            $drops[] = [
                'user' => [
                    'id' => $open->user->id,
                    'username' => $open->user->username
                ],
                'box' => [
                    'image' => $open->box->image
                ],
                'item' => [
                    'market_hash_name' => $open->item->market_hash_name,
                    'image' => $open->item->image,
                    'style' => Style::getStyle($open->item->type)->style
                ]
            ];
        }

        return [
            'stats' => [
                'live' => $drops, 'opens' => Open::count('id'), 'users' => User::count('id'), 'contracts' => Contract::count('id'), 'battles' => Battle::where('status', 2)->count('id')
            ]
        ];
    }

    public function statusFreeCase($user, $box)
    {
        $payments = Payment::where('user_id', $user->id)->where('updated_at', '>', Carbon::now()->subDays(7))->sum('sum');
        if ($payments < $this->config->bonus_free) {
            $different = $this->config->bonus_free - $payments;
            return ['type' => false, 'message' => 'Вы должны пополнить баланс на ' . $different . 'Р'];
        }

        $lastOpen = Open::where('user_id', $user->id)->where('case_id', $box->id)->where('created_at', '>', Carbon::now()->subHours(24))->first();
        if ($lastOpen) {
            $lastOpenTime = Carbon::parse($lastOpen->created_at)->timestamp;
            $now = Carbon::now()->timestamp;
            $different = ($now + 10800) - ($lastOpenTime + 10800);
            $oldTime = 86400 - $different;

            $hour = floor($oldTime / 3600);
            $sec = $oldTime - ($hour * 3600);
            $min = floor($sec / 60);

            if (intval($min) === 0) $min = 1;

            return ['type' => false, 'message' => 'До открытия нового кейса ' . $hour . 'ч. ' . $min . ' мин.'];
        }

        return ['type' => true];
    }

    public function fakeOpen()
    {
        $count = 1;
        $type = 'fast';

        $case = Cases::where('id', '<>', 1)->where('id', '<>', 2)->where('type', '<>', 'free')->inRandomOrder()->first();
        $user = User::where('type', 'fake')->inRandomOrder()->first();

        if (!$user) return;

        if (!$case) return ['success' => false, 'message' => 'Кейс не найден, обновите страницу'];
        if ($case->id === 1 || $case->id === 2) return ['success' => false, 'message' => 'Кейс не найден, обновите страницу'];
        if ($count !== 1 && $count !== 2 && $count !== 3 && $count !== 4 && $count !== 5 && $count !== 10) return ['success' => false, 'message' => 'Выберите, сколько кейсов Вы хотите открыть'];
        if ($type !== 'default' && $type !== 'fast') return ['success' => false, 'message' => 'Вы не выбрали как хотите открыть кейс'];
        if ($case->type === 'limited' && ($case->open >= $case->max_open)) return ['success' => false, 'message' => 'Кейс временно недоступен'];
        if ($case->type === 'limited' && $count > ($case->max_open - $case->open)) return ['success' => false, 'message' => 'Осталось '.($case->max_open - $case->open).' кейсов'];

        if ($case->type === 'free') {
            $free = $this->statusFreeCase($user, $case);
            $count = 1;
            if (!$free['type']) return $free;
        }

        $winItems = [];
        $allPrice = 0;

        for ($i = 0; $i < $count; $i++) {
            $winItem = $this->winItem($user, $case);
            if (is_null($winItem)) return ['success' => false, 'message' => 'Попробуйте еще раз'];

            $winItem->name_first = CaseItem::name_first($winItem->market_hash_name);
            $winItem->name_second = CaseItem::name_second($winItem->market_hash_name);
            $winItem->style = Style::getStyle($winItem->style)->style;

            $allPrice += $winItem->price;

            $winItems[] = [
                'id' => 0,
                'item' => $winItem
            ];
        }

        $user->update([
            'profit' => $user->profit + ($allPrice - ($case->price * $count))
        ]);

        $case->update([
            'open' => $case->open + ($count)
        ]);

        for ($i = 0; $i < count($winItems); $i++) {
            $open = Open::create([
                'user_id' => $user->id,
                'case_id' => $case->id,
                'item_id' => $winItems[$i]['item']['id'],
                'status' => 1,
                'price' => $winItems[$i]['item']['price']
            ]);

            $winItems[$i]['id'] = $open->id;
        }

        $this->redis->publish('liveDrop', json_encode([
            'type' => $type,
            'live' => $this->liveDrop()
        ]));

        return [
            'success' => true,
            'data' => $winItems
        ];
    }

    public function winItem($user, $case)
    {
        $itemsDataBase = CaseItem::where('case_id', $case->id)->orderBy('chance', 'asc')->inRandomOrder()->get();
        if (count($itemsDataBase) === 0) return null;

        $items = [];
        $i = 0;
        $maxTickets = 0;

        foreach ($itemsDataBase as $item) {
            if ($item->chance === 0 && $user->chance_roulette === 0) continue;
            $iBD = AllItem::find($item->item_id);

            if ($i == 0) {
                $from = 1;
            } else {
                $from = $items[$i - 1]['to'] + 1;
            }

            if ($user->chance_roulette > 0 && $iBD->price >= $case->price) $item->chance += $user->chance_roulette;

            $to = $from + $item->chance;
            $maxTickets = $to;

            $items[$i] = [
                'item_id' => $item->id,
                'from' => $from,
                'to' => $to,
                'price' => $iBD->price
            ];

            $i++;
        }

        try {
            $winTicket = mt_rand(1, $maxTickets);
        } catch (\Exception $e) {
            return null;
        }

        $winItem = null;

        foreach ($items as $item) {
            if ($item['from'] <= $winTicket && $item['to'] >= $winTicket) {
                $winItem = CaseItem::with(['item'])->find($item['item_id']);
                break;
            }
        }

        return $winItem->item;
    }
}
