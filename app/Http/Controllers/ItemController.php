<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function create(Request $request, $todoId)
    {
        $item = Item::class(['todo_id' => $todoId] + $request->all());
        return response()->json($item);
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(Item $item)
    {
        $item->delete();
        return response()->json(null);
    }
}
