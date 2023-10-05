<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Category;

class TaskController extends Controller
{
    public function index()
    {
        // すべてのカテゴリーを取得
        $categories = Category::all();
        
        // Complete以外のタスクを取得
        $tasks = Task::where('state', '<>', 'Complete')->get();
        
        // Completeのタスクのみを取得
        $completedTasks = Task::where('state', 'Complete')->get();

        return view('tasks.index')->with([
            'tasks' => $tasks,
            'completedTasks' => $completedTasks,
            'categories' => $categories,
        ]);
    }
    
    public function store(Request $request)//タスク作成機能
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
        ]);
        
        // デフォルトのカテゴリーを設定
        $validatedData['category_id'] = 1; // 1はデフォルトカテゴリーのID
        $validatedData['user_id'] = auth()->id(); // 現在のログインユーザーのIDをセット
    
        Task::create($validatedData);
    
        return redirect()->route('index')->with('success', '新しいタスクが追加されました!');
    }
    
    public function destroy(Task $task)//タスク削除機能
    {
        $taskTitle = $task->title; // 削除するタスクのタイトルを取得
        $task->delete();
        return redirect()->route('index')->with('success', 'タスク「'.$taskTitle.'」が削除されました！');
    }
    
    //チェックボックス
    public function updateState(Request $request, $id) {
        $task = Task::find($id);
        $task->state = $request->input('state');
        $task->save();
    
        return response()->json(['message' => 'Task updated successfully.']);
    }
    
    //カテゴリー内でのタスクの追加
    public function storeTaskInCategory(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
        ]);
        
        $validatedData = $request->all();
        $validatedData['user_id'] = auth()->id();
        
        Task::create($validatedData);
        return back()->with('success', 'タスクが追加されました！');
    }
    
    
    //タスク名編集のモーダルウィンドウ
    public function update(Request $request, Task $task) {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
        ]);
    
        $task->title = $validatedData['title'];
        $task->save();
    
        return redirect()->route('index')->with('success', 'タスク名が更新されました！');
    }

    
}
