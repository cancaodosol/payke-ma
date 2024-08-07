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
                    <th scope="col" class="whitespace-nowrap py-3.5 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-0 hidden md:table-cell">No.</th>
                    <th scope="col" class="whitespace-nowrap py-3.5 text-center text-sm font-semibold text-gray-900">稼働 / 停止</th>
                    <th scope="col" class="whitespace-nowrap pl-4 py-3.5 text-left text-sm font-semibold text-gray-900">使用者</th>
                    <th scope="col" class="whitespace-nowrap text-left text-sm font-semibold text-gray-900 md:hidden"></th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">現バージョン</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 hidden md:table-cell">更新可能</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 hidden md:table-cell">
                        <!-- サーバー -->
                        <img class="w-5 h-5" src="{{ asset('/images/鯖_black.png') }}" alt="サバ" title="サバ">
                    </th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 hidden md:table-cell">URL</th>
                    <th scope="col" class="relative whitespace-nowrap text-sm py-3.5 pl-3 pr-4 sm:pr-0">
                        詳細
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <div hidden>{{ $no = 1; }}</div>
                @foreach($users as $user)
                <tr>
                    <td class="whitespace-nowrap py-1.5 pl-4 pr-3 text-sm text-gray-500 text-center sm:pl-0 hidden md:table-cell">{{ $no++ }}</td>
                    <td class="whitespace-nowrap py-1.5 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">
                        <a class="deploy_status_box" href="{{ route('deploy_log.show', ['userId' => $user->id]) }}" is_update="{{ $user->is_update_waiting() || $user->is_updating_now() }}" user_id="{{ $user->id }}">
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
                        @elseif($user->is_before_deploy())
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                            <div class="flex-none rounded-full p-1 text-slate-300 bg-slate-300/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">未設置</div>
                        </div>
                        @elseif($user->is_update_waiting())
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                            <div class="flex-none rounded-full p-1 text-slate-300 bg-slate-300/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">アプデ待</div>
                        </div>
                        @elseif($user->is_updating_now())
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                            <div class="flex-none rounded-full p-1 text-slate-300 bg-slate-300/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">アプデ中</div>
                        </div>
                        @elseif($user->is_before_setting())
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                            <div class="flex-none rounded-full p-1 text-slate-300 bg-slate-300/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">設定待ち</div>
                        </div>
                        @endif
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start hidden" name="is_active">
                            <div class="flex-none rounded-full p-1 text-green-400 bg-green-400/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">正常稼働</div>
                        </div>
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start hidden" name="has_error">
                            <div class="flex-none rounded-full p-1 text-rose-500 bg-rose-500/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">エラー有り</div>
                        </div>
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start hidden" name="is_updating_now">
                            <div class="flex-none rounded-full p-1 text-slate-300 bg-slate-300/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">アプデ中</div>
                        </div>
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start hidden" name="is_before_setting">
                            <div class="flex-none rounded-full p-1 text-slate-300 bg-slate-300/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">設定待ち</div>
                        </div>
                        </a>
                    </td>
                    <td class="whitespace-nowrap pl-2 py-1.5 text-sm text-gray-900">{{ $user->user_name }}</td>
                    <td class="whitespace-nowrap md:hidden">
                        <a href="{{ $user->app_url }}" target="_blank" rel="noopener noreferrer">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 text-gray-500">
                                <path d="M1.75 1.002a.75.75 0 1 0 0 1.5h1.835l1.24 5.113A3.752 3.752 0 0 0 2 11.25c0 .414.336.75.75.75h10.5a.75.75 0 0 0 0-1.5H3.628A2.25 2.25 0 0 1 5.75 9h6.5a.75.75 0 0 0 .73-.578l.846-3.595a.75.75 0 0 0-.578-.906 44.118 44.118 0 0 0-7.996-.91l-.348-1.436a.75.75 0 0 0-.73-.573H1.75ZM5 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM13 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z" />
                            </svg>
                        </a>
                    </td>
                    <td class="flex items-center whitespace-nowrap px-2 py-1.5">
                        <span class="w-16 px-2 py-1.5 text-xs md:text-sm font-medium text-gray-900">{{ $user->PaykeResource->version }}</span>
                        @if($user->enable_affiliate)
                        <img class="ml-2 w-5 h-5" src="{{ asset('/images/アフィリエイト可能アイコン.png') }}" alt="アフィリエイト可能" title="アフィリエイト可能">
                        @else
                        <img class="ml-2 w-5 h-5" src="{{ asset('/images/アフィリエイト不可アイコン.png') }}" alt="アフィリエイト不可" title="アフィリエイト不可">
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-2 py-1.5 text-xs font-medium text-gray-900 hidden md:table-cell">
                        @if($resources[0]['name'] != $user->PaykeResource->version)
                        <form action="{{ route('payke_user.version.up') }}" method="post">
                            @method('POST')
                            @csrf
                            <div class="flex flex-row">
                                <input type="hidden" name="user_id" value="{{$user->id}}"/>
                                <select name="payke_resource" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-20 p-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    @foreach($resources as $resource)
                                        @if($resource['name'] == $user->PaykeResource->version)
                                            @break
                                        @endif
                                        <option value="{{ $resource['id'] }}">{{ $resource['name'] }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="ml-1 text-xs bg-white hover:bg-gray-100 text-gray-900 font-semibold py-1 px-1 border border-gray-300 rounded-lg shadow">更新</button>
                            </div>
                        </form>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-2 py-1.5 text-sm text-gray-900 hidden md:table-cell">{{ $user->PaykeHost->name }}</td>
                    <td class="whitespace-nowrap px-2 py-1.5 text-sm text-blue-500 underline hidden md:table-cell"><a href="{{ $user->app_url }}" target="_blank" rel="noopener noreferrer">{{ $user->app_url }}</a></td>
                    <td class="relative whitespace-nowrap py-1.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                        <a href="{{ route('payke_user.profile', ['userId' => $user->id]) }}" class="text-indigo-600 hover:text-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path d="m3.196 12.87-.825.483a.75.75 0 0 0 0 1.294l7.25 4.25a.75.75 0 0 0 .758 0l7.25-4.25a.75.75 0 0 0 0-1.294l-.825-.484-5.666 3.322a2.25 2.25 0 0 1-2.276 0L3.196 12.87Z" />
                                <path d="m3.196 8.87-.825.483a.75.75 0 0 0 0 1.294l7.25 4.25a.75.75 0 0 0 .758 0l7.25-4.25a.75.75 0 0 0 0-1.294l-.825-.484-5.666 3.322a2.25 2.25 0 0 1-2.276 0L3.196 8.87Z" />
                                <path d="M10.38 1.103a.75.75 0 0 0-.76 0l-7.25 4.25a.75.75 0 0 0 0 1.294l7.25 4.25a.75.75 0 0 0 .76 0l7.25-4.25a.75.75 0 0 0 0-1.294l-7.25-4.25Z" />
                            </svg>
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
