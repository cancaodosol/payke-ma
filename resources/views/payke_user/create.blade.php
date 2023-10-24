<x-layouts.basepage title="Payke環境 新規登録" current="新規作成">
    <form action="{{ route('payke_user.create.post') }}", method="post">
    @method('POST')
    @csrf
    <div class="space-y-12 sm:space-y-16">
        <div>
        <h2 class="text-base font-semibold leading-7 text-gray-900">Payke環境 新規作成</h2>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">この画面では、新規利用者のPayke環境作成を行います。</p>

        @if(session('feedback.success'))
            <p style="color: green">{{ session('feedback.success') }}</p>
        @endif

        <div class="mt-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
            <x-forms.list name="payke_host_db" label="サーバー / DB" :list="$host_dbs"/>
            <x-forms.list name="payke_resource" label="Paykeバージョン" :list="$resources"/>
            <x-forms.input name="payke_app_name" label="APP名" explain="この名前が、サブディレクトリ名となります。"/>
            <x-forms.checkbox name="comments" label="アフィリエイト機能" cbText="有効にする"/>
            <x-forms.input name="user_name" label="利用者名" explain="管理画面に表示する名前です。"/>
            <x-forms.input name="email_address" label="メールアドレス" example="xxxxx@xxxxx.com"/>
            <x-forms.textarea name="memo" label="メモ"/>
        </div>
        </div>
    </div>
    <!-- 保存ボタン -->
    <div class="mt-6 flex items-center justify-end gap-x-6">
        <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">新規作成する</button>
    </div>
    </form>
</x-layouts.basepage>