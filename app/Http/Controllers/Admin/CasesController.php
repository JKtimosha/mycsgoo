<?php

namespace App\Http\Controllers\Admin;

use App\Battle;
use App\CaseItem;
use App\Cases;
use App\Category;
use App\Open;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CasesController extends Controller
{
    public function load()
    {
        return [
            'cases' => Cases::with(['category'])->where('id', '<>',  1)->where('id', '<>', 2)->get(),
            'categories' => Category::where('id', '<>', 1)->get(),
            'gameId' => $this->config->game_id
        ];
    }

    public function create(Request $r)
    {
        $case = Cases::create($r->cases);
        if (!$r->cases['appId']) $case->update(['appId' => $this->config->game_id]);

        return ['success' => true];
    }

    public function get(Request $r)
    {
        $item = Cases::find($r->id);

        if ($item) {
            return ['success' => true, 'cases' => $item];
        } else {
            return ['success' => false];
        }
    }

    public function edit(Request $r)
    {
        $item = Cases::find($r->cases['id']);

        if ($item) {
            $item->update($r->cases);
            if ($r->cases['type'] === 'default') $item->update(['max_open' => null]);
            if (!$r->cases['appId']) $item->update(['appId' => $this->config->game_id]);
            return ['type' => 'success', 'message' => 'Кейс изменен'];
        } else {
            return ['type' => 'error', 'message' => 'Кейс не найден'];
        }
    }

    public function del(Request $r)
    {
        $item = Cases::find($r->id);

        if ($item) {
            $games = Battle::where('status', 2)->orderBy('id', 'desc')->get();

            foreach ($games as $game) {
                $cases = json_decode($game->cases, true);

                foreach ($cases as $case) {
                    if ($case['id'] === $item->id) {
                        $game->delete();
                    }
                }
            }

            CaseItem::where('case_id', $item->id)->delete();
            Open::where('case_id', $item->id)->delete();

            $item->delete();
            return ['type' => 'success', 'message' => 'Кейс удален'];
        } else {
            return ['type' => 'error', 'message' => 'Кейс не найден'];
        }
    }
}
