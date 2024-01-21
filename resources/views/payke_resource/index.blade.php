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
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <table class="min-w-full divide-y divide-gray-300">
            <thead>
                <tr>
                    <th scope="col" class="whitespace-nowrap py-3.5 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-0">No.</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-center text-sm font-semibold text-gray-900">バージョン</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">ファイル名</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">コメント</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">利用者数</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">アップデート日</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <div hidden>{{ $no = count($paykes); }}</div>
                @foreach($paykes as $payke)
                <tr>
                    <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 text-center sm:pl-0">{{ $no-- }}</td>
                    <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-900 text-center sm:pl-0">{{ $payke->version }}</td>
                    <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 text-left sm:pl-0">{{ $payke->payke_zip_name }}</td>
                    <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 text-left sm:pl-0">
                        <span class="font-medium text-slate-900">{{ $payke->memo }}</span>
                    </td>
                    <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 text-left sm:pl-0">
                    @if($payke->PaykeUsers()->count() > 0)
                        <a href="{{ route('payke_user.index.paykeId', ['paykeId' => $payke->id]) }}" class="text-xs text-indigo-600 hover:text-indigo-900">
                            {{ $payke->PaykeUsers()->count() }}人
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
                    @endif
                    </td>
                    <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 text-left sm:pl-0">{{ $payke->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        </div>
    </div>
</x-layouts.basepage>