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
            
            /*クラスのフォントなどの設定*/
            .header-name {
                font-family: 'Roboto', sans-serif;
                font-size: 50px;
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
            
            .category {
                display: flex;
                font-size: 20px;
                font-bold: bold;
                font-weight: bold;
                
            }
            
            /* 削除ボタンのスタイル */
            .delete-button {
                background-color: transparent;
                border: none;
                color: #FF0000; /* 赤色 */
                font-size: 24px; /* サイズを大きくして目立たせる */
                cursor: pointer; /* マウスオーバー時のカーソルをポインタに */
                transition: 0.3s; /* トランジション効果 */
            }
            
            /* ホバー時のスタイル */
            .delete-button:hover {
                transform: scale(1.2); /* サイズを少し大きく */
                opacity: 0.7; /* 透明度を変更 */
            }
            
            .Completed-List{
                margin-top: 20px; 
                font-size: 28px;
                font-weight: 400;
                color: #555;
                margin-bottom: 15px;
            }
            
            .edit-button {
                background-color: #D3D3D3; /* ライトグレー */
                border: none;
                padding: 5px 10px;
                border-radius: 5px;
                cursor: pointer;
                transition: 0.3s;
            }
            
            .edit-button:hover {
                background-color: #B0B0B0; /* グレーの濃いバージョン */
            }
            
            .hidden {
                display: none;
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
            <!--CSRFトークンの取得のため-->
            <meta name="csrf-token" content="{{ csrf_token() }}">
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
            
            
            <!-- カテゴリー別のタスク表示 -->
            @foreach ($categories as $category)
                <div class="category"> <!-- この行に flex と items-center クラスを追加 -->
                    📁<span class="category-name">{{ $category->name }}</span>
                    <!--カテゴリー畳むトグルボタン-->
                    <span class="toggleCategory" style="cursor:pointer;" data-category-id="{{ $category->id }}">▼</span>
                    <!--カテゴリー名編集ボタン-->
                    <button class="edit-button edit-category-button" data-category-id="{{ $category->id }}">編集</button>

                    <!--カテゴリー削除ボタンの実装-->
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="ml-2">
                        @csrf
                        @method('DELETE')
                        <button class="delete-button">✖</button>
                    </form>
                </div>
                
                
                <!--カテゴリー内のタスクのコンテナ-->
                <div id="categoryContent-{{ $category->id }}">
                    
                    <!-- カテゴリー別のタスクの追加フォーム -->
                    <div class="task-form">
                        <form action="{{ route('storeTaskInCategory') }}" method="POST">
                            @csrf
                            <input type="hidden" name="category_id" value="{{ $category->id }}">
                            <input type="text" name="title" id="title" placeholder="新しいタスクを入力" required>
                            <button type="submit" class="add-button">＋</button>
                        </form>
                    </div>
                    <!-- カテゴリー別のタスクの追加フォーム終わり -->
                    
                    <!--タスク-->
                    @foreach ($category->tasks as $task)
                        @if ($task->state != 'Complete')
                            <!-- タスクの一覧表示 -->
                            <div class="task flex items-center">
                                <input type="checkbox" data-task-id="{{ $task->id }}" class="task-checkbox" {{ $task->state == 'Done' ? 'checked' : '' }}>
                                <h2 class="title">{{ $task->title }}</h2>
                                <!--タスク名編集ボタン-->
                                <button class="edit-button" data-task-id="{{ $task->id }}" onclick="openTaskEditModal({{ $task->id }},{{$task->title}})">編集</button>
                                <!-- タスク削除ボタン -->
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-gray-300 hover:bg-gray-400 text-black hover:text-white font-bold px-2 py-1 rounded">削除</button>
                                </form>
                            </div>
                        @endif
                    @endforeach
                    <!--タスク-->
                    
                </div>
                
            @endforeach
            
             <!-- カテゴリーの追加フォーム -->
            <div class="category-form">
               <form action="{{ route('storeCategory') }}" method="post">
                    @csrf
                    <input type="text" name="name" placeholder="カテゴリー名を入力">
                    <button type="submit"　class="add-button">＋</button>
                </form>
            </div>
            
            
            
            <!-- 達成リストの表示・非表示を切り替えるボタン -->
            <!--<button id="toggleCompletedTasks">達成リストを表示/非表示</button>-->
            
            <!-- 達成リストのタイトルとトグルアイコン -->
            <div class="Completed-List">
                達成リスト
                <span id="toggleCompletedTasks" style="cursor:pointer;">▼</span>
            </div>

            <div id="completedTasksContainer">
                @foreach ($completedTasks as $task)
                    <div class="task flex items-center">
                        
                        <!-- タスクのタイトル -->
                        <h2 class="title">{{ $task->title }}</h2>
                        
                        <!-- カテゴリー名の表示 -->
                        <span>({{ $task->category->name }})</span>
                        
                        <!-- タスクの削除ボタン -->
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="ml-2">
                            @csrf
                            @method('DELETE')
                            <button class="delete-button">✖</button>
                        </form>
        
                    </div>
                @endforeach
            </div>


            

                        
             {{-- ぺジネーション --}}
            {{-- <div class='paginate'>
                {{ $tasks->links() }}
            </div> --}}

            
            
            <!-- JavaScriptファイル -->
            {{-- <script src="{{ asset('js/todo.js') }}"></script> これではうまくいかなかった--}}
            <script src="{{ asset('assets/app.js') }}"></script>
            
            
            <!-- Task Edit Modal -->
            <div id="taskEditModal" class="hidden fixed top-0 left-0 w-full h-full bg-gray-800 bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-4 rounded">
                    <h2 class="text-xl mb-4">タスクを編集</h2>
                    <form id="taskEditForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="text" name="title" id="taskEditTitle" class="border p-2 rounded w-full mb-4">
                        <button type="submit" class="bg-blue-500 text-white p-2 rounded">更新</button>
                        <button type="button" class="ml-2" onclick="closeTaskEditModal()">キャンセル</button>
                    </form>
                </div>
            </div>
            
            
            <!--カテゴリー編集モーダル-->
            <div id="categoryEditModal" class="hidden fixed top-0 left-0 w-full h-full bg-gray-800 bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-4 rounded">
                    <h2 class="text-xl mb-4">カテゴリーを編集</h2>
                    <form id="categoryEditForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="text" name="name" id="categoryEditName" class="border p-2 rounded w-full mb-4">
                        <button type="submit" class="bg-blue-500 text-white p-2 rounded">更新</button>
                        <button type="button" class="ml-2" onclick="closeCategoryEditModal()">キャンセル</button>
                    </form>
                </div>
            </div>



            

        </body>

        
    </x-app-layout>
    


</html>