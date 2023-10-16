<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('parent', 0)->latest()->paginate(20);
        return view('admin.categories.all', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->parent) {
            $request->validate([
                'parent' => 'exists:categories,id'
            ]);
        }
        $request->validate([
            'name'=> 'required|min:3',
        ]);
        Category::create([
            'name' => $request->name,
            'parent' => $request->parent ?? 0
        ]);

        alert()->success('دسته مورد نظر با موفقیت ثبت شد.');

        return redirect(route('admin.categories.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        if ($request->parent) {
            $request->validate([
                'parent' => 'exists:categories,id'
            ]);
        }

        $request->validate([
            'name'=> 'required|min:3',
        ]);
        $category->update([
            'name' => $request->name,
            'parent' => $request->parent
        ]);

        alert()->success('دسته مورد نظر ویرایش شد.');

        return redirect(route('admin.categories.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        alert()->success('دسته بندی حذف شد');
        return back();
    }
}
