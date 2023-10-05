<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
//web.phpのルーティングは特定のURLにアクセスした時にどの操作を返すかを定義する

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/index', [TaskController::class, 'index'])->name('index');//TaskControllerというコントローラのindexというメソッドが呼び出される

Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

//チェックボックス
Route::post('/update-task-state/{id}', [TaskController::class, 'updateState']);

//カテゴリー
Route::resource('categories', CategoryController::class);

//カテゴリー追加
Route::post('/category/store', [CategoryController::class, 'storeCategory'])->name('storeCategory');

//カテゴリー内でのタスク追加
Route::post('/category/task/store', [TaskController::class, 'storeTaskInCategory'])->name('storeTaskInCategory');

//カテゴリー削除機能
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

//タスク編集のモーダルウィンドウ
Route::put('/tasks/{task}/edit', [TaskController::class, 'update']);

//カテゴリー編集のモーダルウィンドウ
Route::put('/categories/{category}/edit', [CategoryController::class, 'update']);

