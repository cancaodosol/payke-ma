<x-layouts.basepage title="Payke環境 新規作成" current="新規作成">

    <form action="{{ route('payke_user.edit.post') }}", method="post">
    @method('POST')
    @csrf
    <div class="space-y-12 sm:space-y-16">
        <div>
        <h2 class="text-base font-semibold leading-7 text-gray-900">Payke環境 編集</h2>
        <p class="mt-1 mb-2 max-w-2xl text-sm leading-6 text-gray-600">Payke環境情報の編集を行います。</p>
        <span class="max-w-2xl text-xs leading-6 text-red-500">
            ※この画面でのPaykeバージョン変更は、データのみの変更となります。<br>
            Payke環境本体のバージョンを変更したい場合は、<a class="text-blue-500" href="{{ route('deploy_log.index', ['userId' => $user->id]) }}">>> Paykeアップデート画面</a> より行なってください。
        </span>

        @if(session('feedback.success'))
            <p style="color: green">{{ session('feedback.success') }}</p>
        @endif

        <div class="mt-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
            <input type="hidden" name="id" value="{{ $user->id }}"/>
            <x-forms.list name="status" value="{{ $user->status }}" label="ステータス" :list="$statuses"/>
            <x-forms.list name="tag_id" value="{{ $user->tag_id }}" label="タグ" :list="$tags"/>
            <x-forms.list name="payke_host_db_id" value="{{ $user->host_db_id() }}" label="サーバー / DB" :list="$host_dbs" addPageLink="{{ route('payke_db.create') }}"/>
            <x-forms.list name="payke_resource_id" value="{{ $user->payke_resource_id }}" label="Paykeバージョン" :list="$resources" addPageLink="{{ route('payke_resource.index') }}"/>
            <x-forms.input name="deploy_setting_no" value="{{ $user->deploy_setting_no }}" label="連携設定No"/>
            <x-forms.input name="user_app_name" value="{{ $user->user_app_name }}" label="公開アプリ名" example="例) tarotaro7" pattern="^[0-9a-zA-Z]+$" explain="この名前が、サブディレクトリ名となります。英数字のみ使用可能です。"/>
            <x-forms.checkbox name="enable_affiliate" value="{{ $user->enable_affiliate }}" label="アフィリエイト機能" cbText="有効にする"/>
            <x-forms.input name="user_name" value="{{ $user->user_name }}" label="利用者名" explain="管理画面に表示する名前です。"/>
            <x-forms.input name="email_address" value="{{ $user->email_address }}" label="メールアドレス" example="xxxxx@xxxxx.com"/>
            <x-forms.textarea name="memo" value="{{ $user->memo }}" label="メモ"/>
            <x-forms.input name="payke_order_id" value="{{ $user->payke_order_id }}" label="Payke 注文ID"/>
            <x-forms.input name="superadmin_username" value="{{ $user->superadmin_username }}" label="メンテナンスユーザーID"/>
            <x-forms.input name="superadmin_password" value="{{ $user->superadmin_password }}" label="メンテナンスパスワード"/>
        </div>
        </div>
    </div>
    <!-- 保存ボタン -->
    <div class="mt-6 flex items-center justify-end gap-x-6">
        <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">更新する</button>
    </div>
    </form>
</x-layouts.basepage>