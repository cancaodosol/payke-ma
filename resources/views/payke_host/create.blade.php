<x-layouts.basepage title="サーバー 新規登録" current="サーバー 新規登録">

    <form action="{{ route('payke_host.create.post') }}", method="post" enctype="multipart/form-data">
    @method('POST')
    @csrf
    <div class="space-y-12 sm:space-y-16">
        <div>
        <h2 class="text-base font-semibold leading-7 text-gray-900">サーバー 新規登録</h2>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">Paykeのリリース先サーバーを新規登録します。</p>

        @if(session('feedback.success'))
            <p style="color: green">{{ session('feedback.success') }}</p>
        @endif

        <div class="mt-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
            <x-forms.input name="name" label="サーバー名" explain="管理画面に表示する名前です。"/>
            <x-forms.input name="hostname" label="ホスト名" example="payke-ec.jp"/>
            <x-forms.input name="remote_user" label="ユーザー名"/>
            <x-forms.input name="port" label="ポート番号" example="10022"/>
            <x-forms.file name="identity_file" label="公開鍵" explain="※ 一度目のSSH接続は、手動で行う必要があります。"/>
            <x-forms.input name="resource_dir" label="リソース管理フォルダ" example="~/payke-ec.jp/payke_resources"/>
            <x-forms.input name="public_html_dir" label="Payke公開フォルダ" example="~/hiderin.xyz/public_html"/>
        </div>
        </div>
    </div>
    <!-- 保存ボタン -->
    <div class="mt-6 flex items-center justify-end gap-x-6">
        <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">新規登録する</button>
    </div>
    </form>
</x-layouts.basepage>