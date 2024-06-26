<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function store(Request $request, $todoId)
    {
        $item = Item::create(['todo_id' => $todoId] + $request->all());
        return response()->json($item);
    }

    public function update(Request $request, $todoId, $id)
    {
        $item = Item::findOrFail($id);

        $item->update([
            'completed' => $request->input('completed')
        ]);

        return response()->json($item);
    }


    public function destroy(Item $item)
    {
        $item->delete();
        return response()->json(null);
    }
}
