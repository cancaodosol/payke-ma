<x-layouts.basepage title="親Payke連携設定" current="親Payke連携設定">

    <form action="{{ route('deploy_setting.edit.post') }}", method="post">
    @method('POST')
    @csrf
    <div class="space-y-12 sm:space-y-16">
        <div>
        <h2 class="text-base font-semibold leading-7 text-gray-900">親Payke連携設定</h2>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">親PaykeとのWebhookの設定、および、決済時に作成するPaykeの環境設定を行います。</p>

        @if(session('feedback.success'))
            <p style="color: green">{{ session('feedback.success') }}</p>
        @endif

        <h3 class="mt-10 text-base font-semibold leading-7 text-gray-900">- 接続名・プラン設定</h3>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">親Payke連携設定一覧に表示される名前を設定します。<br>また、プランとして使用する場合は、その設定を行います。</p>
        <div class="mt-2 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
            <x-forms.input name="setting_title" value="{{ $settings['setting_title'] ?? false ? $settings['setting_title'] : null }}" label="接続名"/>
            <x-forms.input name="sort_no" value="{{ $settings['sort_no'] ?? false ? $settings['sort_no'] : null }}" label="表示順"/>
            <x-forms.checkbox name="is_plan" value="{{ $settings['is_plan'] ?? false ? $settings['is_plan'] : null }}" label="プランとして" cbText="使用する"/>
            <x-forms.input name="payke_order_url" value="{{ $settings['payke_order_url'] ?? false ? $settings['payke_order_url'] : null }}" label="購入ページURL"/>
            <x-forms.textarea name="plan_explain" value="{{ $settings['plan_explain'] ?? false ? $settings['plan_explain'] : null }}" label="プラン説明文" row="1"/>
            <input type="hidden" name="no" value="{{ $settings['no'] }}"/>
        </div>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-red-500">※ 購入ページURLは、プラン変更時にお客さんの決済に使用します。</p>

        <h3 class="mt-10 text-base font-semibold leading-7 text-gray-900">- 注文/決済の受信設定</h3>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">下記情報を、親Paykeの商品ページ「注文/決済の情報の配信」にご登録ください。</p>
        <div class="mt-2 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
            <x-forms.input name="payke_ec2ma_url" value="{{ route('payke.ec2ma', ['no' => $settings['no']]) }}" label="送信先" disabled="true" addCopyBtn="コピー"/>
            <x-forms.input name="payke_x_auth_token" value="{{ $settings['payke_x_auth_token'] ?? false ? $settings['payke_x_auth_token'] : null }}" label="認証鍵" addCopyBtn="コピー" addRandamBtn="再作成"/>
        </div>

        <h3 class="mt-10 text-base font-semibold leading-7 text-gray-900">- Payke環境設定</h3>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">親Paykeからの決済データ受信後に、下記の設定でPaykeの環境を作成します。<br />※作成先のデータベースはあらかじめ用意しておく必要があります。</p>
        <div class="mt-2 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
            <x-forms.checkbox name="payke_enable_affiliate" value="{{ $settings['payke_enable_affiliate'] ?? false ? $settings['payke_enable_affiliate'] : null }}" label="アフィリエイト機能" cbText="有効にする"/>
            <x-forms.list name="payke_host_id" value="{{ $settings['payke_host_id'] ?? false ? $settings['payke_host_id'] : null }}" label="デプロイ先" :list="$hosts"/>
            <x-forms.list name="payke_tag_id" value="{{ $settings['payke_tag_id'] ?? false ? $settings['payke_tag_id'] : null }}" label="タグ" :list="$tags" addPageLink="{{ route('payke_user_tags.index') }}"/>
            <x-forms.list name="payke_resource_id" value="{{ $settings['payke_resource_id'] ?? false ? $settings['payke_resource_id'] : null }}" label="Paykeバージョン" :list="$resources" addPageLink="{{ route('payke_resource.index') }}"/>
        </div>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-red-500">※ v3.26.0 ~ v3.26.17で、マイグレーションエラーが発生します。選択しないでください。</p>
        </div>
        <!-- 保存ボタン -->
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">更新する</button>
        </div>
    </div>
    </form>
</x-layouts.basepage>