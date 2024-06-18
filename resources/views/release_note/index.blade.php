<x-layouts.basepage title="リリースノート" current="リリースノート">
    <div class="mb-10">
        <div class="">
            <h1 class="text-base font-semibold leading-6 text-gray-900">リリースノート</h1>
        </div>
    </div>
    <style>
        .payke_memo ol, .payke_memo ul {
            list-style: inside;
        }
        .payke_memo ol li, .payke_memo ul li {
            text-indent: -1.4em;
            padding-left: 1em;
        }
        .payke_memo p {
            margin-top: 15px;
        }
    </style>
    <div class="mt-7 ml-1">
        <ul role="list" class="space-y-6">
            @foreach($notes as $note)
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
                        <div class="h-1.5 w-1.5 rounded-full bg-green-400 ring-1 ring-gray-300"></div>
                    </div>
                    <div class="flex-auto rounded-md p-3 ring-1 ring-inset ring-gray-200">
                        <div class="flex justify-between gap-x-4">
                            <div class="py-0.5 text-xs leading-5 text-gray-500">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $note->version }}</span>
                                    <span class="text-xs ml-2 text-gray-500">{{ $note->created_at }} リリース</span>
                                </div>
                                <div class="text-gray-900">
                                    <div>{{ $note->title }}</div>
                                </div>
                            </div>
                            <time title="{{ $note->created_at }}" class="flex-none py-0.5 text-xs leading-5 text-gray-500">
                                {{ $note->getDiffTime() }}
                            </time>
                        </div>
                        @if( $note->background || $note->content)
                        <details class="mt-1">
                            <summary class="text-xs text-gray-500">詳細</summary>
                            @if($note->background)
                            <div class="mt-4">
                                <div class="text-xs text-gray-500 mt-2">[ 　開発背景　 ]</div>
                                <div class="text-xs text-gray-500 mt-2 ml-4 payke_memo">{!! $note->background_by_md() !!}</div>
                            </div>
                            @endif
                            @if($note->content)
                            <div class="mt-4">
                                <div class="text-xs text-gray-500 mt-2">[ リリース内容 ]</div>
                                <div class="text-xs text-gray-500 mt-2 ml-4 payke_memo">{!! $note->content_by_md() !!}</div>
                            </div>
                            @endif
                        </details>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</x-layouts.basepage>
