<x-layouts.basepage title="Paykeアップデート履歴 編集" current="Paykeアップデート履歴 編集">

    <form action="{{ route('deploy_log.edit.post') }}", method="post">
    @method('POST')
    @csrf
    <div class="space-y-12 sm:space-y-16">
        <div>
        <h2 class="text-base font-semibold leading-7 text-gray-900">Paykeアップデート履歴 編集</h2>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">Paykeアップデート履歴情報を編集します。</p>

        @if(session('feedback.success'))
            <p style="color: green">{{ session('feedback.success') }}</p>
        @endif

        <div class="mt-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
            <input type="hidden" name="id" value="{{ $log->id }}"/>
            <x-forms.input name="title" value="{{ $log->title }}" label="タイトル" disabled="true"/>
            <x-forms.textarea name="message" value="{{ $log->message }}" label="メッセージ" disabled="true" row="1"/>
            <x-forms.textarea name="memo" value="{{ $log->memo }}" label="メモ"/>
        </div>
        </div>
    </div>
    <!-- 保存ボタン -->
    <div class="mt-6 flex items-center justify-end gap-x-6">
        <a class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" href="{{ route('deploy_log.show', ['userId' => $log->user_id]) }}">ユーザーのログ一覧へ</a>
        <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">更新する</button>
    </div>
    </form>

    <div>
        <h2 class="text-base font-semibold leading-7 text-gray-900">実行パラメータ</h2>
        @if(count($log->getParamArray()) > 0)
        <ul class="text-xs text-green-900 mt-3 ml-3">
            @foreach($log->getParamArray() as $param)
                <li>- {{$param}}</li>
            @endforeach
        </ul>
        @else
        <span class="text-xs mt-3 ml-3">なし</span>
        @endif
    </div>

    <div class="mt-10">
        <h2 class="text-base font-semibold leading-7 text-gray-900">実行ログ</h2>
        @if(count($log->getLogArray()) > 0)
        <ul class="text-xs text-green-900 mt-3 ml-3">
            @foreach($log->getLogArray() as $dLog)
                <li>{{$dLog}}</li>
            @endforeach
        </ul>
        @else
        <span class="text-xs mt-3 ml-3">なし</span>
        @endif
    </div>
</x-layouts.basepage>