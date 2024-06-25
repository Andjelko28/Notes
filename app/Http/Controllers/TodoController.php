<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{

    public function index()
    {
        return Todo::with('items')->get();
    }


    public function create(Request $request)
    {
        $todo = Todo::create($request->all());
        return response()->json($todo);
    }

    public function show(Todo $todo)
    {
        return $todo->load('items');
    }


    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(Todo $todo)
    {
        $todo->delete();
        return response()->json(null);
    }
}
