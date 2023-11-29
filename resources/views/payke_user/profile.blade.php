<x-layouts.basepage title="利用者情報 ： {{ $user->user_name }}" current="利用者情報"
    successTitle="{{ $successTitle ?? '' }}" successMessage="{{ $successMessage ?? '' }}"
    warnTitle="{{ $warnTitle ?? '' }}" warnMessage="{{ $warnMessage ?? '' }}">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold leading-6 text-gray-900">利用者情報 ： {{ $user->user_name }}</h1>
        </div>
    </div>
    <div class="flow-root">


    <section class="my-4 sm:my-5 grid grid-cols-1 lg:grid-cols-2 gap-x-4 gap-y-2 p-2">
        <div class="flex flex-col justify-center">
            <div class="flex flex-col h-full bg-card dark:bg-card-dark shadow justify-between rounded-lg pb-8 p-6 xl:p-8 mt-3 bg-gray-50">
                <div>
                    <h4 class=" font-bold text-base leading-tight">稼働情報</h4>
                    <div class="my-2 text-sm">
                        <table class="mt-5 ml-5">
                            <tr><th class="text-right">稼働状況：</th><td>{{ $user->status_name() }}</td></tr>
                            <tr><th class="text-right">バージョン：</th><td>Payke EC {{ $user->PaykeResource->version }} 
                            <a href="{{ route('deploy_log.index', ['userId' => $user->id]) }}">
                                📝</a>
                            </td></tr>
                            <tr><th class="text-right">アフィリ：</th><td>{{ $user->enable_affiliate ? '使用可能' : '使用不可' }}</td></tr>
                            <tr><th class="text-right">URL：</th><td><a href="{{ $user->app_url }}" class="text-indigo-600 hover:text-indigo-900">{{ $user->app_url }}</a></td></tr>
                            <tr><th class="text-right">初回作成：</th><td>{{ $user->created_at }}</td></tr>
                            <tr><th class="text-right">最終更新：</th><td>{{ $user->updated_at }}</td></tr>
                        </table>
                    </div>
                    <div class="mt-5 text-right">
                        <a href="{{ route('deploy_log.index', ['userId' => $user->id]) }}" type="button" class="rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            >> アップデート履歴</a>
                        <a href="{{ route('payke_user.edit', ['id' => $user->id]) }}" type="button" class="rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            >> 編集画面</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center">
            <div class="flex flex-col h-full bg-card dark:bg-card-dark shadow justify-between rounded-lg pb-8 p-6 xl:p-8 mt-3 bg-gray-50">
                <div>
                    <h4 class="font-bold text-base leading-tight">使用サーバー</h4>
                    <div class="my-2 text-sm">
                        <table class="mt-2 ml-5">
                            <tr><th class="text-right">サーバー名：</th><td>{{ $user->PaykeHost->name }}</td></tr>
                            <tr><th class="text-right">ホスト：</th><td>{{ $user->PaykeHost->hostname }}</td></tr>
                            <tr><th class="text-right">公開パス：</th><td>{{ $user->PaykeHost->public_html_dir."/".$user->user_app_name."/" }}</td></tr>
                            <tr><th class="text-right">資材パス：</th><td>{{ $user->PaykeHost->resource_dir."/".$user->user_folder_id."/" }}</td></tr>
                        </table>
                    </div>
                    <h4 class="font-bold text-base leading-tight mt-5">使用データベース</h4>
                    <div class="text-sm">
                        <table class="mt-2 ml-5">
                            <tr><th class="text-right">ホスト：</th><td>{{ $user->PaykeDb->db_host }}</td></tr>
                            <tr><th class="text-right">DB：</th><td>{{ $user->PaykeDb->db_database }}</td></tr>
                            <tr><th class="text-right">ユーザー名：</th><td>{{ $user->PaykeDb->db_username }}</td></tr>
                            <tr><th class="text-right">パスワード：</th><td>{{ $user->PaykeDb->db_password }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center">
            <div class="flex flex-col h-full bg-card dark:bg-card-dark shadow justify-between rounded-lg pb-8 p-6 xl:p-8 mt-3 bg-gray-50">
                <div>
                    <h4 class=" font-bold text-base leading-tight">個人情報</h4>
                    <div class="text-sm">
                        <table class="mt-2 ml-5">
                            <tr><td class="text-right">メールアドレス：</td><td>{{ $user->email_address }}</td></tr>
                        </table>
                    </div>
                    <h4 class="font-bold text-base leading-tight mt-5">購入情報</h4>
                    <div class="text-sm">
                        <table class="mt-2 ml-5">
                            <tr>
                                <th class="text-center">決済日</th>
                                <th class="text-center">決済ID</th>
                                <th class="text-center">注文ID</th>
                                <th class="text-center">リファレンスID</th>
                            </tr>
                            <tr>
                                <td> ---- no data ---- </td>
                                <td> ---- no data ---- </td>
                                <td> ---- no data ---- </td>
                                <td> ---- no data ---- </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center">
            <div
                class="flex flex-col h-full bg-card dark:bg-card-dark shadow justify-between rounded-lg pb-8 p-6 xl:p-8 mt-3 bg-gray-50">
                <div>
                    <h4 class="font-bold text-base leading-tight">メモ</h4>
                    <form action="{{ route('payke_user.memo_edit.post') }}", method="post">
                    @method('POST')
                    @csrf
                        <div class="mt-2">
                            <input type="hidden" name="id" value="{{ $user->id }}"/>
                            <textarea name="memo" rows="7" class="text-xs w-full p-3">{{ $user->memo }}</textarea>
                        </div>
                        <div class="mt-1 text-right">
                            <button type="submit" class="rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                >> メモを更新</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    </div>
</x-layouts.basepage>
