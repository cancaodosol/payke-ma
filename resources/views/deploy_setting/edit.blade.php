<x-layouts.basepage title="自動デプロイ設定" current="自動デプロイ設定">

    <form action="{{ route('deploy_setting.edit.post') }}", method="post">
    @method('POST')
    @csrf
    <div class="space-y-12 sm:space-y-16">
        <div>
        <h2 class="text-base font-semibold leading-7 text-gray-900">自動デプロイ設定</h2>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">Paykeデプロイ設定情報を編集します。</p>

        @if(session('feedback.success'))
            <p style="color: green">{{ session('feedback.success') }}</p>
        @endif

        <div class="mt-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
            <x-forms.list name="payke_resource_id" value="{{ $settings['payke_resource_id'] ?? false ? $settings['payke_resource_id'] : null }}" label="Paykeバージョン" :list="$resources" addPageLink="{{ route('payke_resource.index') }}"/>
        </div>
        </div>
    </div>
    <!-- 保存ボタン -->
    <div class="mt-6 flex items-center justify-end gap-x-6">
        <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">更新する</button>
    </div>
    </form>
</x-layouts.basepage>