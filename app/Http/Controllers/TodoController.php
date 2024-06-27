<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{

    public function index(Request $request)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Fetch todos for the authenticated user
        $todos = Todo::where('user_id', $request->user()->id)->with('items')->get();
        return response()->json($todos);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $todo = auth()->user()->todos()->create($validated);

        return response()->json($todo, 201);
    }

    public function show($id)
    {
        $todo = Todo::findOrFail($id);

        if ($todo->user_id != auth()->id()) {
            return response()->json(['error' => 'Unaothorized'], 403);
        }

        return response()->json($todo);
    }


    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);

        if ($todo->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $todo->title = $validated['title'];
        $todo->save();

        return response()->json($todo);
    }


    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);

        if ($todo->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $todo->delete();

        return response()->json(['message' => 'Todo deleted successfully']);
    }
}

