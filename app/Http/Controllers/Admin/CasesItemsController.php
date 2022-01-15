<?php

namespace App\Http\Controllers\Admin;

use App\CaseItem;
use App\AllItem;
use App\Cases;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CasesItemsController extends Controller
{
    public function load(Request $r)
    {
        $box = Cases::find($r->id);

        if ($box) {
            return ['success' => true, 'box' => $box, 'items' => CaseItem::with(['item'])->where('case_id', $box->id)->get()];
        } else {
            return ['success' => false];
        }
    }

    public function create(Request $r)
    {
        if (!AllItem::find(intval($r->item_id)))  return ['type' => 'error', 'message' => 'Ошибка добавления. Обновите страницу.'];

        CaseItem::create([
            'case_id' => $r->case_id,
            'item_id' => intval($r->item_id),
            'chance' => intval($r->chance)
        ]);

        return ['type' => 'success', 'message' => 'Предмет добавлен'];
    }

    public function get(Request $r)
    {
        $item = CaseItem::with(['item'])->find($r->id);

        if ($item) {
            return ['success' => true, 'cases' => $item, 'item' => ['id' => $item->item_id, 'text' => $item->item->market_hash_name . ' (' . $item->item->price . ' р.)']];
        } else {
            return ['success' => false];
        }
    }

    public function edit(Request $r)
    {
        if (!AllItem::find(intval($r->item_id)))  return ['type' => 'error', 'message' => 'Ошибка добавления. Обновите страницу.'];
        
        $item = CaseItem::find($r->id);

        if ($item) {
            $item->update([
                'item_id' => intval($r->item_id),
                'chance' => intval($r->chance)
            ]);
            return ['type' => 'success', 'message' => 'Предмет изменен'];
        } else {
            return ['type' => 'error', 'message' => 'Предмет не найден'];
        }
    }

    public function del(Request $r)
    {
        $item = CaseItem::find($r->id);

        if ($item) {
            $item->delete();
            return ['type' => 'success', 'message' => 'Предмет удален'];
        } else {
            return ['type' => 'error', 'message' => 'Предмет не найден'];
        }
    }

    public function all(Request $r)
    {
        $case = Cases::find($r->caseId);
        if (!$case) return ['type' => 'error', 'message' => 'Кейс не найден'];

        if ($r->search) {
            $pagination = AllItem::where([['appId', '=', $case->appId], ['market_hash_name', 'like', '%'.$r->search.'%']])->orWhere('price', 'like', '%'.$r->search.'%')->orWhere('type', 'like', '%'.$r->search.'%')->paginate(15);
        } else {
            $pagination = AllItem::where('appId', $case->appId)->paginate(15);
        }
        $items = [];
        $more = true;

        if ($pagination->lastPage() === $pagination->currentPage()) $more = false;

        foreach ($pagination->items() as $item) {
            $items[] = [
                'id' => $item->id,
                'text' => $item->market_hash_name . ' (' . $item->price . ' р.)'
            ];
        }

        return [
            'results' => $items,
            'more' => $more
        ];
    }
}
