<x-layouts.basepage title="データベース 新規登録" current="リリース先データベース 新規登録">

    @if ($errors->any())
        <x-messages.error title="入力内容に問題があります。" :errors="$errors->all()"/>
    @endif

    <form action="{{ route('payke_user.create.post') }}", method="post">
    @method('POST')
    @csrf
    <div class="space-y-12 sm:space-y-16">
        <div>
        <h2 class="text-base font-semibold leading-7 text-gray-900">データベース 新規登録</h2>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">Paykeの環境作成に使用するデータベースを新規登録します。</p>

        @if(session('feedback.success'))
            <p style="color: green">{{ session('feedback.success') }}</p>
        @endif

        <div class="mt-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
            <x-forms.list name="payke_host_id" label="サーバー" :list="$hosts" addPageLink="{{ route('payke_user.index') }}"/>
            <x-forms.input name="db_host" label="ホスト" example="localhost"/>
            <x-forms.input name="db_database" label="データベース名"/>
            <x-forms.input name="db_username" label="ユーザー名"/>
            <x-forms.input name="db_password" label="パスワード"/>
        </div>
        </div>
    </div>
    <!-- 保存ボタン -->
    <div class="mt-6 flex items-center justify-end gap-x-6">
        <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">新規登録する</button>
    </div>
    </form>
</x-layouts.basepage>