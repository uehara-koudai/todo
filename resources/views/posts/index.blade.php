<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<!--str_replaceは文字列内のアンダースコア_をハイフン-に置き換える-->
<!--Laravelのアプリケーションインスタンスから現在のロケール（言語設定）を取得-->
    
    <head>
        <meta charset="utf-8"><!--HTMLドキュメントの文字エンコーディングを指定するためのメタタグ-->
        <title>Todo リスト</title>　<!--indexページのタイトル-->
        <!--Fonts-->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    
    <body>
        <h1>Todoリスト</h1>
        
        <div class='posts'>
            @foreach ($posts as $post)
                <div class='post'>
                    <h2 class='title'>{{ $post->title }}</h2>
                    <p class='body'>{{ $post->body }}</p>
                </div>
            @endforeach
        </div>
        
        <!--ぺジネーション-->
        <div class='paginate'>
            {{ $posts->links() }}
        </div>
    </body>

</html>