<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\auth;
use App\Models\Category;

class TodoController extends Controller
{
    public function index()
    {
        //$todos = Todo::all();
        $todos = Todo::where('user_id', auth()->user()->id)
        ->orderBy('created_at', 'desc')
        ->get();
        //dd($todos);

        $todosCompleted = Todo::where('user_id', auth()->user()->id)
        -> where('is_done', true)
        ->count();

        $todos = Todo::with('category')->where('user_id', auth()->id())->get();
        return view('todo.index', compact('todos', 'todosCompleted'));
    }

    public function create()
    {
        $categories = Category::where('user_id', auth()->id())->get();
        return view('todo.create',compact('categories'));
    }

    public function edit(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id){
            // dd($todo);
            $categories = Category::where('user_id', auth()->id())->get();
            return view('todo.edit', compact('todo', 'categories'));
        } else {
        
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to edit this todo!');
        }
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);
        $todo->update([
            'title' => ucfirst($request->title),
            'category_id' => $request->category_id,
        ]);
        return redirect()->route('todo.index')->with('success', 'Todo updated successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $todo = Todo::create([
            'title' => ucfirst($request->title),
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('todo.index')->with('success', 'Todo created successfully.');
    }

    public function complete(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->update(['is_done' => true,
        ]);
            return redirect()->route('todo.index')->with('success', 'Todo completed successfully.');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to complete this todo!');
        }
    }

    public function uncomplete(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->update(['is_done' => false,
        ]);
            return redirect()->route('todo.index')->with('success', 'Todo uncompleted successfully.');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to uncomplete this todo!');
        }
    }

    public function destroy(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id){
            $todo->delete();
            return redirect()->route('todo.index')->with('success', 'Todo deeleted successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to delete this todo!');
        }
    }

    public function destroyCompleted()
    {
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
        ->where('is_done', true)
        ->get();
        foreach ($todosCompleted as $todo){
            $todo->delete();
        }
        return redirect()->route('todo.index')->with('success', 'All completed todos deleted successfully!');
    }
}
