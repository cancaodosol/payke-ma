<x-layouts.basepage title="Paykeアップデート履歴" current="Paykeアップデート履歴">
    <div class="mb-10">
        <div class="">
            <h1 class="text-base font-semibold leading-6 text-gray-900">Paykeアップデート</h1>
        </div>
        @if(count($resources) > 0)
        <form action="{{ route('payke_user.version.up') }}", method="post">
            @method('POST')
            @csrf
            <div>
                <input type="hidden" name="user_id" value="{{$user_id}}"/>
                <x-forms.list name="payke_resource" label="Paykeバージョン" :list="$resources" addSubmit="更新する"/>
            </div>
        </form>
        @else
        <p class="mt-2 text-sm text-gray-700">現在、最新のバージョンを使用しています。</p>
        @endif
    </div>
    <div class="mt-10 mb-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
    </div>

    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold leading-6 text-gray-900">アップデート履歴</h1>
        </div>
        <div class="mt-4 sm:ml-2 sm:mt-0 sm:flex-none">
        <a href="{{ route('payke_user.profile', ['userId' => $user_id]) }}" type="button" class="block rounded-md bg-emerald-500 px-2 py-1 text-center text-xs font-semibold text-white shadow-sm hover:bg-emerald-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500">
            ＞ 使用者情報へ</a>
        </div>
    </div>
    <div class="mt-7 ml-1">
        <ul role="list" class="space-y-6">
            @foreach($logs as $log)
                @if($log->is_version_info() || $log->is_other_info() || $log->is_success() || $log->is_warm())
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
                        @if($log->is_version_info())
                            <div class="h-1.5 w-1.5 rounded-full bg-green-400 ring-1 ring-gray-300"></div>
                        @elseif($log->is_success())
                            <div class="h-1.5 w-1.5 rounded-full bg-green-400 ring-1 ring-gray-300"></div>
                        @elseif($log->is_warm())
                            <div class="h-1.5 w-1.5 rounded-full bg-yellow-400 ring-1 ring-gray-300"></div>
                        @elseif($log->is_error())
                            <div class="h-1.5 w-1.5 rounded-full bg-rose-500 ring-1 ring-gray-300"></div>
                        @elseif($log->is_other_info())
                           <div class="h-1.5 w-1.5 rounded-full bg-gray-100 ring-1 ring-gray-300"></div>
                        @endif
                    </div>
                    <div class="flex-auto py-0.5 text-xs leading-5 text-gray-500">
                        <div class="flex">
                            <span class="font-medium text-gray-900">{{ $log->title }}</span>
                            <a href="{{ route('deploy_log.edit', ['id' => $log->id]) }}" class="ml-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                                    <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                                </svg>
                            </a>
                        </div>
                        <div>
                            <div>{{ $log->message }}</div>
                            @if($log->memo != "")
                            <div class="text-red-600">{{ $log->memo }}</div>
                            @endif
                        </div>
                    </div>
                    <time title="{{ $log->created_at }}" class="flex-none py-0.5 text-xs leading-5 text-gray-500 text-right">
                        {{ $log->created_at }}<br>
                        {{ $log->getDiffTime() }}
                    </time>
                </li>
                @else
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
                    <div class="relative flex mt-3 h-6 w-6 flex-none items-center justify-center bg-white">
                        @if($log->is_version_info())
                            <div class="h-1.5 w-1.5 rounded-full bg-green-400 ring-1 ring-gray-300"></div>
                        @elseif($log->is_success())
                            <div class="h-1.5 w-1.5 rounded-full bg-green-400 ring-1 ring-gray-300"></div>
                        @elseif($log->is_warm())
                            <div class="h-1.5 w-1.5 rounded-full bg-yellow-400 ring-1 ring-gray-300"></div>
                        @elseif($log->is_error())
                            <div class="h-1.5 w-1.5 rounded-full bg-rose-500 ring-1 ring-gray-300"></div>
                        @elseif($log->is_other_info())
                           <div class="h-1.5 w-1.5 rounded-full bg-gray-100 ring-1 ring-gray-300"></div>
                        @endif
                    </div>
                    <div class="flex-auto rounded-md p-3 ring-1 ring-inset ring-gray-200">
                        <div class="flex justify-between gap-x-4">
                            <div class="py-0.5 text-xs leading-5 text-gray-500">
                                <div class="flex">
                                    <span class="font-medium text-gray-900">{{ $log->title }}</span>
                                    <a href="{{ route('deploy_log.edit', ['id' => $log->id]) }}" class="ml-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                                            <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                                        </svg>
                                    </a>
                                </div>
                                <div>
                                    <div>{{ $log->message }}</div>
                                    @if($log->memo != "")
                                    <div class="text-red-600">{{ $log->memo }}</div>
                                    @endif
                                </div>
                            </div>
                            <time title="{{ $log->created_at }}" class="flex-none py-0.5 text-xs leading-5 text-gray-500 text-right">
                                {{ $log->created_at }}<br>
                                {{ $log->getDiffTime() }}
                            </time>
                        </div>
                        @if(count($log->getLogArray()) > 0)
                        <details class="mt-1">
                            <summary class="text-xs text-gray-500">ログ</summary>
                            <p class="text-sm leading-6 text-gray-500 ml-2">
                                @foreach($log->getLogArray() as $dl)
                                    {{ $dl }}<br/>
                                @endforeach
                            </p>
                        </details>
                        @endif
                    </div>
                </li>
                @endif
            @endforeach
        </ul>
    </div>
</x-layouts.basepage>
