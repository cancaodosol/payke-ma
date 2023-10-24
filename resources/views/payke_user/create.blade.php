<!doctype html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0,
            maximum-scale=1.0, minimum-slace=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Payke環境 新規登録</title>
    </head>
    <body>
        <h1>Payke環境 新規登録</h1>
        <div>
            <a href="{{ route('payke_host.index') }}">戻る</a>
            <p>登録情報</p>
            @if(session('feedback.success'))
                <p style="color: green">{{ session('feedback.success') }}</p>
            @endif
            <form action="{{ route('payke_user.create.post') }}", method="post">
                @method('POST')
                @csrf
                <label for="user_name">利用者名</label>
                <input name="user_name"/>
                <br><br>
                <label for="email_address">メールアドレス</label>
                <input name="email_address"/>
                <br><br>
                <label for="payke_app_name">APP名</label>
                <input name="payke_app_name"/>
                <br><br>
                <label for="payke_host_db">サーバー / DB</label>
                <select name="payke_host_db">
                    @foreach ($host_dbs as $host_db)
                        <option value="{{ $host_db['id'] }}">{{ $host_db['name'] }}</option>
                    @endforeach
                </select>
                <br><br>
                <label for="payke_resource">Payke</label>
                <select name="payke_resource">
                    @foreach ($resources as $resource)
                        <option value="{{ $resource->id }}">{{ $resource->payke_name }}</option>
                    @endforeach
                </select>
                <br><br>
                <label for="can_affi">アフィリ機能</label>
                <input name="can_affi" type="checkbox"/>
                <br><br>
                <label for="memo">メモ</label>
                <textarea type="text" name="memo"></textarea>
                <br><br>
                @error('payke_user')
                <p style="color:red;">{{ $message }}</p>
                @enderror
                <button type="submit">新規登録</button>
            </form>
        </div>
    </body>
</html>