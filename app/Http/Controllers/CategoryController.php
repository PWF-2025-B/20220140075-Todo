<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;


class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('todos')
                        ->where('user_id', auth()->id())
                        ->get();

        return view('category.index', compact('categories'));
    }


    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
        'title' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'title' => ucfirst($request->title),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('category.index')->with('success', 'Category created successfully!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('category.index')->with('danger', 'Category deleted.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);
        $category->update([
            'title' => ucfirst($request->title),
        ]);
        return redirect()->route('category.index')->with('success', 'Todo updated successfully.');
    }

    public function edit(Category $category)
    {
        if (auth()->user()->id == $category->user_id){
            // dd($category);

            return view('category.edit', compact('category'));
        } else {
        
            return redirect()->route('category.index')->with('danger', 'You are not authorized to edit this todo!');
        }
    }
}