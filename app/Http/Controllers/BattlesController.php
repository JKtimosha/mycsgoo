<?php


namespace App\Http\Controllers;

use App\AllItem;
use App\Battle;
use App\CaseItem;
use App\Open;
use App\Style;
use App\User;
use Auth;
use App\Cases;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BattlesController extends Controller
{

    public function loadCases()
    {
        return Cases::where('id', '<>', 1)->where('id', '<>', 2)->where('type', '<>', 'free')->orderBy('price', 'asc')->get();
    }

    public function create(Request $r)
    {
        if (Auth::guest()) return ['success' => false, 'message' => 'auth'];

        $players = intval($r->players);
        $type = strval($r->type);
        $cases = $r->cases;
        $casesGame = [];

        if ($players < 2 && $players > 4) return ['success' => false, 'message' => 'error'];
        if ($type !== "public" && $type !== "private") return ['success' => false, 'message' => 'error'];

        $allPrice = 0;
        $rounds = 0;
        $casesRounds = [];

        foreach ($cases as $case) {
            if (!$case['selected'] || $case['selected'] > 50 || !Cases::find($case['id']) || Cases::find($case['id'])->type === 'free') return ['success' => false, 'message' => 'error'];
            $allPrice += (Cases::find($case['id'])->price * $case['selected']);
            $rounds += $case['selected'];
            for ($i = 0; $i < $case['selected']; $i++) {
                $casesRounds[] = Cases::find($case['id']);
            }
            $casesGame[$case['id']] = Cases::find($case['id']);
        }

        if ($this->user->balance < $allPrice) return ['success' => false, 'message' => 'balance'];

        $this->user->decrement('balance', $allPrice);

        $users[] = [
            'id' => $this->user->id,
            'username' => $this->user->username,
            'avatar' => $this->user->avatar
        ];

        $battle = Battle::create([
            'user_id' => $this->user->id,
            'price' => $allPrice,
            'players' => $players,
            'rounds' => $rounds,
            'type' => $type,
            'cases' => json_encode($cases),
            'users' => json_encode($users),
            'status' => 0
        ]);

        $roundsHistory = [];

        for ($i = 0; $i < $rounds; $i++) {
            for ($l = 0; $l < count($users); $l++) {
                $roundsHistory[] = [
                    'battle_id' => $battle->id,
                    'case_id' => $casesRounds[$i]->id,
                    'user_id' => $this->user->id,
                    'round_number' => $i + 1,
                    'item' => null,
                    'win_price' => null,
                    'is_win' => 0
                ];
            }
        }

        $battle->update([
            'rounds_history' => json_encode($roundsHistory)
        ]);

        $this->redis->publish('battleNew', json_encode($this->getBattleID($battle->id)));

        return [
            'success' => true,
            'id' => $battle->id
        ];
    }

    public function getBattle(Request $r)
    {
        return $this->getBattleID($r->id);
    }

    public function getBattleID($id)
    {
        $battle = Battle::find($id);

        if (!$battle) return ['success' => false];

        $cases = json_decode($battle->cases);
        $users = json_decode($battle->users);
        $caseItems = [];
        $casesRounds = [];

        foreach ($cases as $case) {
            $thisItems = [];
            foreach (CaseItem::with(['item'])->where('case_id', $case->id)->get() as $item) {
                $caseItems[$item->id] = [
                    'name_first' => CaseItem::name_first($item->item->market_hash_name),
                    'name_second' => CaseItem::name_second($item->item->market_hash_name),
                    'name_first_en' => CaseItem::name_first($item->item->market_hash_name_en),
                    'name_second_en' => CaseItem::name_second($item->item->market_hash_name_en),
                    'image' => $item->item->image,
                    'style' => Style::getStyle($item->item->type)->style
                ];
                $thisItems[] = $item->id;
            }
            for ($i = 0; $i < $case->selected; $i++) {
                $case->case_items = $thisItems;
                $casesRounds[$case->id] = $case;
            }
        }

        $type = 0;
        if ($battle->type === 'private') $type = 1;

        return [
            'battle' => [
                'id' => $battle->id,
                'players' => $battle->players,
                'price' => $battle->price,
                'rounds_count' => $battle->rounds,
                'rounds' => json_decode($battle->rounds_history),
                'status' => $battle->status,
                'users' => json_decode($battle->users),
                'type' => $type,
                'cases' => $casesRounds,
                'caseItems' => $caseItems,
                'win_price' => $battle->win_price,
                'user_win_id' => $battle->user_win_id,
                'user_id' => $battle->user_id
            ],
            'success' => true
        ];
    }

    public function join(Request $r)
    {
        if (Auth::guest()) return ['success' => false, 'message' => 'auth'];

        $battle = Battle::find($r->id);

        if (!$battle) return ['success' => false, 'message' => 'error'];

        if ($this->user->balance < $battle->price) return ['success' => false, 'message' => 'balance'];

        $users = json_decode($battle->users, true);

        foreach ($users as $user) {
            if ($user['id'] === $this->user->id) return ['success' => false, 'message' => 'yuReady'];
        }

        if ($battle->status > 0) return ['success' => false, 'message' => 'started'];

        $users[] = [
            'id' => $this->user->id,
            'username' => $this->user->username,
            'avatar' => $this->user->avatar
        ];

        if (count($users) > $battle->players) return ['success' => false, 'message' => 'max'];

        $this->user->decrement('balance', $battle->price);

        $casesRounds = [];

        foreach (json_decode($battle->cases) as $case) {
            for ($i = 0; $i < $case->selected; $i++) {
                $casesRounds[] = $case;
            }
        }
        $battleHistory = [];

        for ($i = 0; $i < $battle->rounds; $i++) {
            for ($l = 0; $l < count($users); $l++) {
                $battleHistory[] = [
                    'battle_id' => $battle->id,
                    'case_id' => $casesRounds[$i]->id,
                    'user_id' => $users[$l]['id'],
                    'round_number' => $i + 1,
                    'item' => null,
                    'win_price' => null,
                    'is_win' => 0
                ];
            }
        }

        usort($battleHistory, function ($a, $b) {
            return ($a['round_number'] - $b['round_number']);
        });

        $battle->update([
            'users' => json_encode($users),
            'rounds_history' => json_encode($battleHistory)
        ]);

        $this->redis->publish('battleJoin', json_encode([
            'battle_id' => $battle->id,
            'battle' => $this->getBattleID($battle->id)
        ]));

        if (count($users) === $battle->players) {
            $battle->update([
                'status' => 1
            ]);
            $this->redis->publish('battleJoin', json_encode([
                'battle_id' => $battle->id,
                'battle' => $this->getBattleID($battle->id)
            ]));
            $this->setWinners($battle);
        }

        return [
            'success' => true
        ];
    }

    public function setWinners($battle)
    {
        $cases = json_decode($battle->cases);
        $users = json_decode($battle->users);
        $rounds_history = json_decode($battle->rounds_history);
        $rounds = [];
        $newRoundsHistory = [];

        for ($i = 0; $i < count($rounds_history); $i++) {
            $rounds[$rounds_history[$i]->round_number][] = $rounds_history[$i];
        }

        $pricesAll = [];

        foreach ($users as $user) {
            $pricesAll[$user->id] = 0;
        }

        $winItems = [];
        $priceAll = 0;

        foreach ($rounds as $round) {
            $winPrices = [];
            for ($l = 0; $l < count($round); $l++) {
                $item = $this->winItem(User::find($round[$l]->user_id), Cases::find($round[$l]->case_id));
                $item->name_first = CaseItem::name_first($item->market_hash_name);
                $item->name_second = CaseItem::name_second($item->market_hash_name);
                $item->style = Style::getStyle($item->type)->style;

                $open = Open::create([
                    'user_id' => NULL,
                    'case_id' => $round[$l]->case_id,
                    'item_id' => $item->id,
                    'price' => $item->price
                ]);

                $item->open_id = $open->id;
                $round[$l]->item = $item;
                $round[$l]->win_price = $item->price;
                $winPrices[] = $item->price;
                $pricesAll[$round[$l]->user_id] += $item->price;
                $winItems[] = $item;
                $priceAll += $item->price;
            }
            $winID = array_search(max($winPrices), $winPrices);
            $round[$winID]->is_win = 1;
        }

        foreach ($rounds as $round) {
            for ($i = 0; $i < count($round); $i++) {
                $newRoundsHistory[] = $round[$i];
            }
        }

        $userWinID = array_search(max($pricesAll), $pricesAll);

        $battle->update([
            'user_win_id' => $userWinID,
            'win_price' => $priceAll,
            'win_items' => json_encode($winItems),
            'rounds_history' => json_encode($newRoundsHistory)
        ]);

        $this->redis->publish('battleStart', json_encode($this->getBattleID($battle->id)));

        return 'success';
    }

    public function setBattleStatus(Request $r)
    {
        $battle = Battle::find($r->id);

        $battle->update(['status' => $r->status]);

        if ($r->status === 2) {
            $winItems = json_decode($battle->win_items, true);
            foreach ($winItems as $item) {
                $open = Open::find($item['open_id']);
                if ($open) $open->update(['user_id' => $battle->user_win_id]);
            }
            $this->redis->publish('liveDrop', json_encode([
                'type' => 'default',
                'live' => CasesController::liveDropStatic()
            ]));
        }

        return $this->getBattleID($battle->id);
    }

    public function decline(Request $r)
    {
        $id = intval($r->id);

        $battle = Battle::find($id);
        if (!$battle) return ['type' => 'error', 'message' => 'not_found'];
        $users = json_decode($battle->users);

        if ($battle->user_id !== $this->user->id) return ['type' => 'error', 'message' => 'creator'];
        if ($battle->status > 0) return ['type' => 'error', 'message' => 'started'];
        if (count($users) > 1) return ['type' => 'error', 'message' => 'started'];

        $this->redis->publish('battleDelete', json_encode($battle->id));

        $this->user->increment('balance', $battle->price);
        $battle->delete();

        return [
            'type' => 'success',
            'message' => 'delete'
        ];
    }

    public function history()
    {
        if (Auth::guest()) return [];

        $games = Battle::where('status', 2)->orderBy('id', 'desc')->get();
        $gameList = [];
        $battles = [];
        $x = 0;

        foreach ($games as $game) {
            if ($x === 20) break;
            $users = json_decode($game->users, true);

            foreach ($users as $user) {
                if ($user['id'] === $this->user->id) {
                    $x++;
                    $gameList[] = $game;
                }
            }
        }

        foreach ($gameList as $battle) {
            $battles[] = [
                'id' => $battle->id,
                'user_win_id' => $battle->user_win_id,
                'updated_at' => Carbon::parse($battle->updated_at)->format('Y-m-d h:i'),
                'win_price' => $battle->win_price,
                'players' => $battle->players,
                'users' => json_decode($battle->users),
                'cases' => json_decode($battle->cases)
            ];
        }

        return $battles;
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

        $winTicket = mt_rand(1, $maxTickets);
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