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
                @if($log->is_version_info() || $log->is_other_info())
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
                        @elseif($log->is_warm())
                            <div class="h-1.5 w-1.5 rounded-full bg-yellow-400 ring-1 ring-gray-300"></div>
                        @elseif($log->is_error())
                            <div class="h-1.5 w-1.5 rounded-full bg-rose-500 ring-1 ring-gray-300"></div>
                        @elseif($log->is_other_info())
                           <div class="h-1.5 w-1.5 rounded-full bg-gray-100 ring-1 ring-gray-300"></div>
                        @endif
                    </div>
                    <p class="flex-auto py-0.5 text-xs leading-5 text-gray-500">
                        <span class="font-medium text-gray-900">{{ $log->title }}</span><br>
                        <span>{{ $log->message }}</span>
                    </p>
                    <time title="{{ $log->created_at }}" class="flex-none py-0.5 text-xs leading-5 text-gray-500">
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
                                <span class="font-medium text-gray-900">{{ $log->title }}</span><br>
                                <span>{{ $log->message }}</span>
                            </div>
                            <time title="{{ $log->created_at }}" class="flex-none py-0.5 text-xs leading-5 text-gray-500">
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
