<x-layouts.basepage title="Payke環境 新規作成" current="新規作成">

    <form action="{{ route('payke_user.create.post') }}", method="post">
    @method('POST')
    @csrf
    <div class="space-y-12 sm:space-y-16">
        <div>
        <h2 class="text-base font-semibold leading-7 text-gray-900">Payke環境 新規作成</h2>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">新規利用者のPayke環境作成を行います。</p>

        @if(session('feedback.success'))
            <p style="color: green">{{ session('feedback.success') }}</p>
        @endif

        <div class="mt-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
            <x-forms.list name="payke_host_db_id" value="{{ $user ?? false ? $user->host_db_id() : null }}" label="サーバー / DB" :list="$host_dbs" addPageLink="{{ route('payke_db.create') }}"/>
            <x-forms.list name="payke_resource_id" value="{{ $user ?? false ? $user->payke_resource_id : null }}" label="Paykeバージョン" :list="$resources" addPageLink="{{ route('payke_resource.index') }}"/>
            <x-forms.input name="user_app_name" value="{{ $user ?? false ? $user->user_app_name : null }}" label="公開アプリ名"  example="例) tarotaro7" pattern="^[0-9a-zA-Z\d_-]+$" explain="この名前が、サブディレクトリ名となります。英数字のみ使用可能です。"/>
            <x-forms.checkbox name="enable_affiliate" value="{{ $user ?? false ? $user->enable_affiliate : null }}" label="アフィリエイト機能" cbText="有効にする"/>
            <x-forms.input name="user_name" value="{{ $user ?? false ? $user->user_name : null }}" label="利用者名" explain="管理画面に表示する名前です。"/>
            <x-forms.input name="email_address" value="{{ $user ?? false ? $user->email_address : null }}" label="メールアドレス" example="xxxxx@xxxxx.com"/>
            <x-forms.textarea name="memo" value="{{ $user ?? false ? $user->memo : null }}" label="メモ"/>
        </div>
        </div>
    </div>
    <!-- 保存ボタン -->
    <div class="mt-6 flex items-center justify-end gap-x-6">
        <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">新規作成する</button>
    </div>
    </form>
</x-layouts.basepage>