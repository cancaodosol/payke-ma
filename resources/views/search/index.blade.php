<x-layouts.basepage title="検索結果" current="検索結果">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
        <h1 class="text-base font-semibold leading-6 text-gray-900">検索結果</h1>
        <p class="mt-2 text-sm text-gray-700">キーワード「{{ $keyword }}」の検索結果です。</p>
        </div>
        <div class="mt-4 sm:ml-2 sm:mt-0 sm:flex-none">
        <a href="{{ route('payke_user.create') }}" type="button" class="block rounded-md bg-emerald-500 px-2 py-1 text-center text-xs font-semibold text-white shadow-sm hover:bg-emerald-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500">
            ＋ 新規登録</a>
        </div>
    </div>
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <table class="min-w-full divide-y divide-gray-300">
            <thead>
                <tr>
                    <th scope="col" class="whitespace-nowrap py-3.5 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-0">No.</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">稼働 / 停止</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">URL</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">使用者</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">アフィリ機能</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">現バージョン</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">更新可能</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">メモ</th>
                    <th scope="col" class="relative whitespace-nowrap py-3.5 pl-3 pr-4 sm:pr-0">
                        <span class="sr-only">詳細</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <div hidden>{{ $no = 1; }}</div>
                @foreach($users as $user)
                <tr>
                    <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 text-center sm:pl-0">{{ $no++ }}</td>
                    <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">
                        <a href="{{ route('deploy_log.index', ['userId' => $user->id]) }}">
                        @if($user->is_active())
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                            <div class="flex-none rounded-full p-1 text-green-400 bg-green-400/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">正常稼働</div>
                        </div>
                        @elseif($user->is_disable_admin())
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                            <div class="flex-none rounded-full p-1 text-yellow-400 bg-yellow-400/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">管理停止</div>
                        </div>
                        @elseif($user->is_disable_admin_and_sales())
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                            <div class="flex-none rounded-full p-1 text-rose-500 bg-rose-500/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">管理・販売停止</div>
                        </div>
                        @elseif($user->is_delete())
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                            <div class="flex-none rounded-full p-1 text-slate-300 bg-slate-300/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">完全停止</div>
                        </div>
                        @elseif($user->has_error())
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                            <div class="flex-none rounded-full p-1 text-rose-500 bg-rose-500/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">エラー有り</div>
                        </div>
                        @elseif($user->is_before_setting())
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                            <div class="flex-none rounded-full p-1 text-slate-300 bg-slate-300/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">未設置</div>
                        </div>
                        @endif
                        </a>
                    </td>
                    <td class="whitespace-nowrap px-2 py-2 text-sm text-blue-500 underline"><a href="{{ $user->app_url }}" target="_blank" rel="noopener noreferrer">{{ $user->app_url }}</a></td>
                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-900">{{ $user->user_name }}</td>
                    <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                        @if($user->enable_affiliate)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-cyan-600">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ $user->PaykeResource->version }}</td>
                    <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900"></td>
                    <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900 truncate"></td>
                    <td class="relative whitespace-nowrap py-2 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                        <a href="{{ route('payke_user.profile', ['userId' => $user->id]) }}" class="text-indigo-600 hover:text-indigo-900">
                            詳細<span class="sr-only">, AAPS0L</span>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        </div>
    </div>
</x-layouts.basepage>
