<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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


    public function destroy($todoId, $id)
    {
        try {
            // Find the item within the specified todoId
            $item = Item::where('todo_id', $todoId)->findOrFail($id);

            // Delete the item
            $item->delete();

            // Return success response
            return response()->json(['message' => 'Item deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            // If item not found, return 404 error
            return response()->json(['error' => 'Item not found'], 404);
        } catch (\Exception $e) {
            // Catch any other unexpected errors
            return response()->json(['error' => 'Failed to delete item'], 500);
        }
    }
}
