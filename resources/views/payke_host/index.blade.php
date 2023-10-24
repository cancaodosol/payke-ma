<x-layouts.basepage title="Payke Host 一覧" current="一覧">
    <h1 class="text-3xl font-bold underline">Payke Host 一覧</h1>
    <a href="{{ route('payke_user.create') }}">＋ 新規作成</a>
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
                <td>▶︎</td>
                <td>{{ $db->db_host }}</td>
                <td>{{ $db->db_username }}</td>
                <td>{{ $db->db_password }}</td>
                <td>{{ $db->db_database }}</td>
                <td>{{ $db->PaykeUser ? '▶︎ '.$db->PaykeUser->PaykeResource->payke_name : ''}}</td>
                <td>{{ $db->PaykeUser ? $db->PaykeUser->user_app_name : '' }}</td>
                <td></td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</x-layouts.basepage>
