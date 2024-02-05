<x-layouts.basepage title="Paykeバージョン 編集" current="Paykeバージョン 編集"
    successTitle="{{ $successTitle ?? '' }}" successMessage="{{ $successMessage ?? '' }}"
    warnTitle="{{ $warnTitle ?? '' }}" warnMessage="{{ $warnMessage ?? '' }}"
    errorTitle="{{ $errorTitle ?? '' }}" errorMessage="{{ $errorMessage ?? '' }}">

    <form action="{{ route('payke_resource.edit.post') }}", method="post">
    @method('POST')
    @csrf
    <div class="space-y-12 sm:space-y-16">
        <div>
        <h2 class="text-base font-semibold leading-7 text-gray-900">Paykeバージョン 編集</h2>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">Payke ECのバージョン情報を編集します。</p>

        @if(session('feedback.success'))
            <p style="color: green">{{ session('feedback.success') }}</p>
        @endif

        <div class="mt-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
            <input type="hidden" name="id" value="{{ $resource->id }}"/>
            <x-forms.input name="version" value="{{ $resource->version }}" label="バージョン表記"/>
            <x-forms.input name="payke_name" value="{{ $resource->payke_name }}" label="PaykeEC表記名"/>
            <x-forms.textarea name="memo" value="{{ $resource->memo }}" label="バージョン説明（任意）"/>
        </div>
        </div>
    </div>
    <!-- 保存ボタン -->
    <div class="mt-6 flex items-center justify-end gap-x-6">
        <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">更新する</button>
    </div>
    </form>
</x-layouts.basepage>