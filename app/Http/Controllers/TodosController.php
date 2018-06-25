<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;


class TodosController extends Controller
{
    public function todos () {

        $todos = Todo::all();

        return response()->json(['todos' => $todos]);
    }

    public function addTodo (Request $request) {

        $todo = new Todo ();
        $todo->title = $request->todo['title'];
        $todo->done = $request->todo['done'];
        $todo->save();

        return response()->json(['message' => 'todo has been added']);
    }

    public function deleteTodo (Request $request) {

        $todo = Todo::find($request->id);
        $todo->delete();

        return response()->json(['message' => 'todo has been deleted']);
    }

    public function doneTodo (Request $request) {

        $todo = Todo::find($request->id);
        $todo->done = $request->done;
        $todo->save();

        return response()->json(['message' => 'todo status has been changed']);
        
    }
}
