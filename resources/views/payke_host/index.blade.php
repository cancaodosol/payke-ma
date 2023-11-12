<x-layouts.basepage title="リソース一覧" current="リソース一覧"
    successTitle="{{ $successTitle ?? '' }}" successMessage="{{ $successMessage ?? '' }}"
    warnTitle="{{ $warnTitle ?? '' }}" warnMessage="{{ $warnMessage ?? '' }}">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
            <h1 class="text-base font-semibold leading-6 text-gray-900">リソース一覧</h1>
            <p class="mt-2 text-sm text-gray-700">サーバー、データベースの一覧です。</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('payke_host.create') }}" type="button" class="block rounded-md bg-emerald-500 px-2 py-1 text-center text-xs font-semibold text-white shadow-sm hover:bg-emerald-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500">
                ＋ サーバー</a>
            </div>
            <div class="mt-4 sm:ml-2 sm:mt-0 sm:flex-none">
            <a href="{{ route('payke_db.create') }}" type="button" class="block rounded-md bg-emerald-500 px-2 py-1 text-center text-xs font-semibold text-white shadow-sm hover:bg-emerald-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500">
                ＋ データベース</a>
            </div>
        </div>
        <div class="flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                @foreach($hosts as $host)
                <h2 class="mt-10 text-base font-semibold leading-6 text-gray-900">
                    ・{{ $host->name }} ({{ $host->hostname }}、{{ $host->public_html_dir }})
                    <a href="{{ route('payke_host.edit', ['id' => $host->id]) }}" class="text-indigo-600 hover:text-indigo-900">
                        編集<span class="sr-only">, AAPS0L</span>
                    </a>
                </h2>
                <table class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr>
                        <th scope="col" class="whitespace-nowrap py-3.5 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-0">No.</th>
                        <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">使用中 / 空き</th>
                        <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">ホスト</th>
                        <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">ユーザー名</th>
                        <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">テーブル名</th>
                        <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">使用者</th>
                        <th scope="col" class="relative whitespace-nowrap py-3.5 pl-3 pr-4 sm:pr-0">
                            <span class="sr-only">詳細</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <div hidden>{{ $no = 1; }}</div>
                    @foreach($host->PaykeDbs as $db)
                    <tr>
                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 text-center sm:pl-0">{{ $no++ }}</td>
                        <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">
                            @if($db->status == 1)
                            <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                                <div class="flex-none rounded-full p-1 text-green-400 bg-green-400/10">
                                    <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                                </div>
                                <div class="text-xs">使用中</div>
                            </div>
                            @elseif($db->status == 0)
                            <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                                <div class="flex-none rounded-full p-1 text-slate-300 bg-slate-300/10">
                                    <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                                </div>
                                <div class="text-xs">空き</div>
                            </div>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-900">{{ $db->db_host }}</td>
                        <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">{{ $db->db_username }}</td>
                        <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ $db->db_database }}</td>
                        <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ $db->PaykeUser ? '>> '.$db->PaykeUser->user_name : ''}}</td>
                        <td class="relative whitespace-nowrap py-2 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                            <a href="{{ route('payke_db.edit', ['id' => $db->id]) }}" class="text-indigo-600 hover:text-indigo-900">
                                編集<span class="sr-only">, AAPS0L</span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
                @endforeach
            </div>
            </div>
        </div>
</x-layouts.basepage>
