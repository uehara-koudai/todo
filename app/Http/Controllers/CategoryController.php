<?php

// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('index', compact('categories'));
    }
    
    //カテゴリーの追加
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
        ]);

        Category::create($request->all());
        return back()->with('success', 'カテゴリーが追加されました');
    }

    //カテゴリー削除
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'カテゴリーが正常に削除されました');
    }
    
    //カテゴリー編集
    public function update(Request $request, Category $category) {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
        ]);
    
        $category->name = $validatedData['name'];
        $category->save();
    
        return redirect()->route('index')->with('success', 'カテゴリーが更新されました！');
    }


}
