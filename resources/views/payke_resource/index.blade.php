<x-layouts.basepage title="Paykeバージョン一覧" current="Paykeバージョン一覧">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold leading-6 text-gray-900">Paykeバージョン一覧</h1>
        </div>
        <div class="mt-4 sm:ml-2 sm:mt-0 sm:flex-none">
        </div>
    </div>
    <div class="mt-3">
        <form action="{{ route('payke_resource.create.post') }}", method="post" enctype="multipart/form-data">
            @method('POST')
            @csrf
            <div>
                <x-forms.file name="payke-zip" label="Payke Zipファイル"/>
                <x-forms.textarea name="memo" label="メモ"/>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">追加登録する</button>
            </div>
        </form>
    </div>
    <div class="mt-5 ml-1">
        <ul role="list" class="space-y-6">
            @foreach($paykes as $payke)
                <li class="relative flex gap-x-4">
                    @if($loop->last)
                        <div class="absolute left-0 top-0 flex w-6 justify-center">
                            <div class="w-px bg-gray-200"></div>
                        </div>
                    @else
                        <div class="absolute left-0 top-0 flex w-6 justify-center -bottom-6">
                            <div class="w-px bg-gray-200"></div>
                        </div>
                    @endif
                    <div class="relative flex h-6 w-6 flex-none items-center justify-center bg-white">
                        <div class="h-1.5 w-1.5 rounded-full bg-green-400 ring-1 ring-gray-300"></div>
                    </div>
                    <p class="flex-auto py-0.5 text-xs leading-5 text-gray-500">
                        <span class="font-medium text-gray-900">{{ $payke->version }}</span>
                        {{ $payke->created_at }}( {{ $payke->diff_time_from_now() }} ) に追加されました。
                        @if($payke->PaykeUsers()->count() > 0)
                        （ 使用者数：{{ $payke->PaykeUsers()->count() }} ）
                        <a href="{{ route('payke_user.index.paykeId', ['paykeId' => $payke->id]) }}" class="text-xs text-indigo-600 hover:text-indigo-900">
                            >> 使用者一覧
                        </a>
                        @endif
                        <br>
                        {{ $payke->payke_zip_name }}
                        <br>
                        <span class="font-medium text-slate-900">{{ $payke->memo }}</span>
                    </p>
                </li>
            @endforeach
        </ul>
    </div>
</x-layouts.basepage>
