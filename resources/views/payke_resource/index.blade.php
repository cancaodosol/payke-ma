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
                        <span class="font-medium text-sm text-gray-900">{{ $payke->version }}</span>
                        <div class="flex-auto text-xs text-gray-500">
                            <span>{{ $payke->payke_zip_name }}</span> 
                            <span class="ml-2" title="{{ $payke->created_at }}">{{ $payke->diff_time_from_now() }}UP</span>
                        </div>
                        @if($payke->memo)
                        <div class="text-xs text-slate-900 mt-2">{{ $payke->memo }}</div>
                        @endif
                        @if($payke->PaykeUsers()->count() > 0)
                        <div class="mt-1">
                            <a href="{{ route('payke_user.index.paykeId', ['paykeId' => $payke->id]) }}" class="text-xs text-indigo-600 hover:text-indigo-900">
                                利用者 {{ $payke->PaykeUsers()->count() }}人
                            </a>

                            @if($payke->PaykeUsers()->count() <= 3)
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
                            @endif
                        </div>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</x-layouts.basepage>
