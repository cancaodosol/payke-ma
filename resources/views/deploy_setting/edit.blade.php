<x-layouts.basepage title="親Payke連携設定" current="親Payke連携設定">

    <form action="{{ route('deploy_setting.edit.post') }}", method="post">
    @method('POST')
    @csrf
    <div class="space-y-12 sm:space-y-16">
        <div>
        <h2 class="text-base font-semibold leading-7 text-gray-900">親Payke連携設定</h2>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">親PaykeとのWebhookの設定、および、決済時に作成するPaykeECの環境設定を行います。</p>

        @if(session('feedback.success'))
            <p style="color: green">{{ session('feedback.success') }}</p>
        @endif

        <h3 class="mt-10 text-base font-semibold leading-7 text-gray-900">- 注文/決済の受信設定</h3>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">下記情報を、親PaykeECの商品ページ「注文/決済の情報の配信」にご登録ください。</p>
        <div class="mt-2 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
            <x-forms.input name="payke_ec2ma_url" value="{{ route('payke.ec2ma') }}" label="送信先" disabled="true" addCopyBtn="コピー"/>
            <x-forms.input name="payke_x_auth_token" value="{{ $settings['payke_x_auth_token'] ?? false ? $settings['payke_x_auth_token'] : null }}" label="認証鍵" addRandamBtn="再作成"/>
        </div>
        <!-- 保存ボタン -->
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">更新する</button>
        </div>

        <h3 class="text-base font-semibold leading-7 text-gray-900 mt-2">- PaykeEC環境設定</h3>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">親Paykeからの決済データ受信後に、下記の設定でPaykeECの環境を作成します。<br />※作成先のデータベースはあらかじめ用意しておく必要があります。</p>
        <div class="mt-2 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
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