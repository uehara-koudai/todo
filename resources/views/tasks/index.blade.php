<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<!--str_replaceは文字列内のアンダースコア_をハイフン-に置き換える-->
<!--Laravelのアプリケーションインスタンスから現在のロケール（言語設定）を取得-->


    <x-app-layout>
        <!--CSS-->
        <style>
            /*ヘッダーのフォント*/
            /*.header-name {*/
            /*    font-size: 36px;*/
            /*}*/
            
            .header-name {
                font-family: 'Roboto', sans-serif;
                font-size: 40px;
                font-weight: 700;
                letter-spacing: 1.5px;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            }
            
            /*成功ログのフォント*/
            .alert-success {
                color: green; // メッセージのテキストカラー
            }
            
            /*成功ログのフォント*/
            .alert-danger {
                color: red; // メッセージのテキストカラー
            }
            
            
            /*新しいタスクの追加ボタン*/
            .add-button {
                background-color: rgb(200, 250, 260);/* lightsteelblue */
                color: black;
                font-bold: bold;
                font-weight: bold;
                padding: 8px 16px; /* px-2 py-1 に相当する値 */
                border-radius: 0.25rem; /* rounded に相当する値 */
            
                /* hover時のスタイル */
                transition: background-color 0.3s, color 0.3s; /* これにより、背景色と文字色が滑らかに変化します */
            }
            
            .add-button:hover {
                background-color: rgb(176, 224, 230);
                color: white;
            }

            
        </style>
        <!--CSS-->


        <x-slot name='header'>
            <span class="header-name">To-Do</span><!--ヘッダー名-->
        </x-slot>
        
        <head>
            <meta charset="utf-8"><!--HTMLドキュメントの文字エンコーディングを指定するためのメタタグ-->
            <title>Todo リスト</title>　<!--indexページのタイトル-->
            <!--Fonts-->
            <link href="{{ asset('css/app.css') }}" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
        </head>
        
        <body>
            <!--<h1>Todoリスト</h1>-->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            
            <!--タスクの追加フォーム-->
            <div class="task-form">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <input type="text" name="title" placeholder="新しいタスクを入力" required>
                    <!-- 必要に応じて、memoや他の入力フィールドを追加することもできます -->
                    <button type="submit" class="add-button">＋</button>
                </form>
            </div>

            <div class='tasks'>
                @foreach ($tasks as $task)
                    <!--タスクの一覧表示-->
                    <div class='task flex items-center'>
                        <input type="checkbox" data-task-id="{{ $task->id }}" class="task-checkbox" {{ $task->state == 'Done' ? 'checked' : '' }}>
                        <h2 class='title'>︎{{ $task->title }}</h2>
                        <!--削除ボタン-->
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="ml-2">
                            @csrf
                            @method('DELETE')
                            <button class="bg-gray-300 hover:bg-gray-400 text-black hover:text-white font-bold px-2 py-1 rounded">削除️</button>
                        </form>
                    </div>
                @endforeach
            </div>

            
            <!--ぺジネーション-->
            <div class='paginate'>
                {{ $tasks->links() }}
            </div>
            
            
            <!-- JavaScriptファイル -->
            <script src="todo.js"></script>
            
            

        </body>

        
    </x-app-layout>
    


</html>