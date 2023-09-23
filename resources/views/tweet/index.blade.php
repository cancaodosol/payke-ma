<!doctype html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0,
            maximum-scale=1.0, minimum-slace=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>つぶやきアプリ</title>
    </head>
    <body>
        <h1>つぶやきアプリ</h1>
        <div>
            <p>登録フォーム</p>
            <form action="{{ route('tweet.create') }}" method="post">
                @csrf
                <label for="tweet-content">つぶやき</label>
                <span>140文字まで</span>
                <textarea id="tweet-content" type="text" name="tweet" placeholder="つぶやきを入力"></textarea>
                @error('tweet')
                <p style="color:red;">{{ $message }}</p>
                @enderror
                <button type="submit">投稿</button>
            </form>
        </div>
        <div>
            @foreach($tweets as $tweet)
                <details>
                    <summary>{{ $tweet->content }}</summary>
                    <div>
                        <a href="{{ route('tweet.update.index', ['tweetId' => $tweet->id]) }}" method="post">編集</a>
                    </div>
                </details>
            @endforeach
        </div>
    </body>
</html>