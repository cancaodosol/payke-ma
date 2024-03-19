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
                <x-forms.textarea name="memo" label="バージョン説明（任意）"/>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">追加登録する</button>
            </div>
        </form>
    </div>
    <style>
        .payke_memo ol, .payke_memo ul {
            list-style: inside;
        }
    </style>
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
                    <div class="text-xs">
                        <div class="flex items-center text-xs text-gray-500">
                            <span class="font-medium text-sm text-gray-900">{{ $payke->version }}</span>
                            <a href="{{ route('payke_resource.edit', ['id' => $payke->id]) }}" class="ml-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                                    <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                                </svg>
                            </a>
                        </div>
                        <div class="flex-auto text-xs text-gray-500">
                            <span>{{ $payke->payke_zip_name }}</span> 
                            <span class="ml-2" title="{{ $payke->created_at }}">{{ $payke->diff_time_from_now() }}UP</span>
                        </div>
                        @if($payke->memo)
                        <div class="text-xs text-slate-900 mt-2 payke_memo">{!! $payke->memo_by_md() !!}</div>
                        @endif
                        @if($payke->PaykeUsers()->count() > 0)
                        <div class="mt-1">
                            <a href="{{ route('payke_user.index.paykeId', ['paykeId' => $payke->id]) }}" class="text-xs text-indigo-600 hover:text-indigo-900">
                                利用者 {{ $payke->PaykeUsers()->count() }}人
                            </a>
                            (
                            @foreach($payke->PaykeUsers as $user)
                            <a href="{{ route('payke_user.profile', ['userId' => $user->id]) }}" class="text-indigo-600 hover:text-indigo-900">
                                @if($loop->last)
                                {{ $user->user_name }}
                                @else
                                {{ $user->user_name }},
                                @endif
                            </a>
                            @endforeach
                            )
                        </div>
                        @endif
                        <br><br>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</x-layouts.basepage>
