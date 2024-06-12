<x-layouts.basepage title="親Payke連携設定" current="親Payke連携設定">

    <form action="{{ route('deploy_setting.edit_base.post') }}", method="post">
    @method('POST')
    @csrf
    <div class="space-y-12 sm:space-y-16">
        <div>
        <h2 class="text-base font-semibold leading-7 text-gray-900">親Payke連携設定</h2>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">親PaykeとのWebhookの設定、および、決済時に作成するPaykeの環境設定を行います。</p>

        @if(session('feedback.success'))
            <p style="color: green">{{ session('feedback.success') }}</p>
        @endif

        <h3 class="mt-10 text-base font-semibold leading-7 text-gray-900">- API接続設定</h3>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">親PaykeとのAPI接続に関する設定をします。</p>
        <div class="mt-2 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
            <x-forms.input name="payke_api_url" value="{{ $settings['payke_api_url'] ?? false ? $settings['payke_api_url'] : null }}" label="URL"/>
            <x-forms.input name="payke_api_token" value="{{ $settings['payke_api_token'] ?? false ? $settings['payke_api_token'] : null }}" label="トークン"/>
        </div>
        <!-- 保存ボタン -->
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">更新する</button>
        </div>
    </div>
    </form>
</x-layouts.basepage>