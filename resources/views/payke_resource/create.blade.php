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
            <x-forms.file name="payke-zip" label="Payke Zipファイル" addSubmit="新規登録する"/>
        </div>
        </div>
    </div>
    </form>
</x-layouts.basepage>