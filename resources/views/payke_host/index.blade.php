<!doctype html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0,
            maximum-scale=1.0, minimum-slace=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Payke Host</title>
    </head>
    <body>
        <h1>Payke Host 一覧</h1>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>名前</th>
                    <th>ホスト名</th>
                    <th>ユーザー</th>
                    <th>ポート</th>
                    <th>認証キー</th>
                    <th>リソースフォルダ</th>
                    <th>公開フォルダ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hosts as $host)
                <tr>
                    <td>{{ $host->id }}</td>
                    <td>{{ $host->name }}</td>
                    <td>{{ $host->hostname }}</td>
                    <td>{{ $host->remote_user }}</td>
                    <td>{{ $host->port }}</td>
                    <td>{{ $host->identity_file }}</td>
                    <td>{{ $host->resource_dir }}</td>
                    <td>{{ $host->public_html_dir }}</td>
                </tr>
                @foreach($host->PaykeDbs as $db)
                <tr>
                    <td> ▶︎ </td>
                    <td>{{ $db->db_host }}</td>
                    <td>{{ $db->db_username }}</td>
                    <td>{{ $db->db_password }}</td>
                    <td>{{ $db->db_database }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endforeach
                @endforeach
        </table>
    </body>
</html>