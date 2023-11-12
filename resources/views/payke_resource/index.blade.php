<x-layouts.basepage title="Paykeバージョン一覧" current="Paykeバージョン一覧">
    <div class="mb-10">
        <div class="">
            <h1 class="text-base font-semibold leading-6 text-gray-900">Payke Zipアップロード</h1>
        </div>
        <form action="{{ route('payke_resource.create.post') }}", method="post">
            @method('POST')
            @csrf
            <div>
                <x-forms.file name="payke-zip" label="Payke Zipファイル" addSubmit="アップロードする"/>
            </div>
        </form>
    </div>
    <div class="mt-10 mb-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
    </div>

    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold leading-6 text-gray-900">バージョン一覧</h1>
        </div>
        <div class="mt-4 sm:ml-2 sm:mt-0 sm:flex-none">
        </div>
    </div>
    <div class="mt-7 ml-1">
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
                        {{ $payke->created_at }}( {{ $payke->diff_time_from_now() }} ) にアップロードしました。
                        @if($payke->PaykeUsers()->count() > 0)
                        （ 使用者数：{{ $payke->PaykeUsers()->count() }} ）
                        <a href="{{ route('payke_user.index.paykeId', ['paykeId' => $payke->id]) }}" class="text-xs text-indigo-600 hover:text-indigo-900">
                            >> 使用者一覧
                        </a>
                        @endif
                        <br>
                        {{ $payke->payke_zip_name }}
                    </p>
                </li>
            @endforeach
        </ul>
    </div>
</x-layouts.basepage>
