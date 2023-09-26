<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(Task $task)//タスク一覧表示機能
    {
        return view('tasks.index')->with(['tasks' => $task->getPaginateByLimit(10)]);
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
        return redirect()->route('index')->with('success', 'タスク「'.$taskTitle.'」が削除されました。');
    }
    
    //チェックボックス
    public function updateState(Request $request, $id) {
        $task = Task::find($id);
        $task->state = $request->input('state');
        $task->save();
    
        return response()->json(['message' => 'Task updated successfully.']);
    }

}
