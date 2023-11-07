<x-layouts.basepage title="Payke 新規作成" current="Payke 新規作成">

    @if ($errors->any())
        <x-messages.error title="入力内容に問題があります。" :errors="$errors->all()"/>
    @endif

    <form action="{{ route('payke_resource.create.post') }}" method="post" enctype="multipart/form-data">
    @method('POST')
    @csrf
    <div class="space-y-12 sm:space-y-16">
        <div>
        <h2 class="text-base font-semibold leading-7 text-gray-900">Payke 新規登録</h2>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">PaykeのZipファイルをアップロードします。</p>

        @if(session('feedback.success'))
            <p style="color: green">{{ session('feedback.success') }}</p>
        @endif

        <div class="mt-5 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
            <x-forms.file name="file-upload" label="Payke Zipファイル"/>
        </div>
        </div>
    </div>
    <!-- 保存ボタン -->
    <div class="mt-6 flex items-center justify-end gap-x-6">
        <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">新規登録する</button>
    </div>
    </form>
</x-layouts.basepage>