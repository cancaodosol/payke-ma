<x-layouts.basepage title="Payke一覧" current="Payke一覧">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
        <h1 class="text-base font-semibold leading-6 text-gray-900">Payke一覧</h1>
        <p class="mt-2 text-sm text-gray-700">リリース済みPaykeの一覧です。</p>
        </div>
        <div class="mt-4 sm:ml-2 sm:mt-0 sm:flex-none">
        <a href="{{ route('payke_user.create') }}" type="button" class="block rounded-md bg-emerald-500 px-2 py-1 text-center text-xs font-semibold text-white shadow-sm hover:bg-emerald-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500">
            ＋ 新規登録</a>
        </div>
    </div>
    <ul id="job_message_box" class="mt-4 text-xs"></ul>
    <div class="mt-4 flow-root">
        <div class="">
            <a href="{{ route('payke_user.index') }}" class="hover:bg-gray-100 text-xs font-semibold me-0.5 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-400 inline-flex items-center justify-center">
                一覧
            </a>
            @foreach($tags as $tag)
                @if(!$tag->color || $tag->color == "none")
                <a href="{{ route('payke_user.index.tagId', ['tagId' => $tag->id]) }}" class="hover:bg-gray-100 text-xs font-semibold me-0.5 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-400 inline-flex items-center justify-center">
                    {{ $tag->name }}
                </a>
                @else
                <a href="{{ route('payke_user.index.tagId', ['tagId' => $tag->id]) }}" class="bg-{{$tag->color}}-100 hover:bg-{{$tag->color}}-200 text-{{$tag->color}}-800 text-xs font-semibold me-0.5 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-{{$tag->color}}-400 border border-{{$tag->color}}-400 inline-flex items-center justify-center">
                    {{ $tag->name }}
                </a>
                @endif
            @endforeach
            <a href="{{ route('payke_user_tags.index') }}" class="bg-emerald-500 hover:bg-emerald-400 text-white text-xs font-semibold me-0.5 px-2.5 py-0.5 rounded border border-emerald-400 inline-flex items-center justify-center">
                ＋ 編集
            </a>
        </div>
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <table class="min-w-full divide-y divide-gray-300">
            <thead>
                <tr>
                    <th scope="col" class="whitespace-nowrap py-3.5 pl-4 pr-3 text-center text-xs font-semibold text-gray-900 sm:pl-0 hidden md:table-cell">No.</th>
                    <th scope="col" class="whitespace-nowrap py-3.5 text-center text-xs font-semibold text-gray-900">@sortablelink('status', '稼働 / 停止 ▼')</th>
                    <th scope="col" class="whitespace-nowrap pl-4 py-3.5 text-left text-xs font-semibold text-gray-900">@sortablelink('user_name', '使用者 ▼')</th>
                    <th scope="col" class="whitespace-nowrap text-left text-xs font-semibold text-gray-900 md:hidden"></th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-xs font-semibold text-gray-900">@sortablelink('PaykeResource.version_for_sort', 'リリース状況 ▼')</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-xs font-semibold text-gray-900">@sortablelink('enable_affiliate', 'アフィリエイト ▼')</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-xs font-semibold text-gray-900 hidden md:table-cell">更新可能バージョン</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-xs font-semibold text-gray-900 hidden md:table-cell">@sortablelink('PaykeHost.name', 'サーバー ▼')</th>
                    <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-xs font-semibold text-gray-900 hidden md:table-cell">@sortablelink('app_url', 'URL ▼')</th>
                    <th scope="col" class="relative whitespace-nowrap text-xs py-3.5 pl-3 pr-4 sm:pr-0">
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
                        <a class="deploy_status_box" href="{{ route('deploy_log.index', ['userId' => $user->id]) }}" is_update="{{ $user->is_update_waiting() || $user->is_updating_now() }}" user_id="{{ $user->id }}">
                        @if($user->is_active())
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                            <div class="flex-none rounded-full p-1 text-green-400 bg-green-400/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">正常稼働</div>
                        </div>
                        @elseif($user->has_unpaid())
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                            <div class="flex-none rounded-full p-1 text-yellow-400 bg-yellow-400/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">未払いあり</div>
                        </div>
                        @elseif($user->is_disable_admin() || $user->is_disable_admin_and_sales() || $user->is_delete())
                        <!-- 管理停止、管理・販売停止、完全停止 は、赤丸。 -->
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                            <div class="flex-none rounded-full p-1 text-rose-500 bg-rose-500/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">{{$user->status_name()}}</div>
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
                            <svg aria-hidden="true" role="status" class="inline w-3.5 h-3.5 text-gray-200 animate-spin dark:text-gray-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="#1C64F2"/>
                            </svg>
                            <div class="text-xs">アプデ中</div>
                        </div>
                        @elseif($user->is_before_setting())
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                            <div class="flex-none rounded-full p-1 text-slate-300 bg-slate-300/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">設定待ち</div>
                        </div>
                        @elseif($user->is_unused())
                        <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                            <div class="flex-none rounded-full p-1 text-slate-300 bg-slate-300/10">
                                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                            </div>
                            <div class="text-xs">利用終了</div>
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
                            <svg aria-hidden="true" role="status" class="inline w-3.5 h-3.5 text-gray-200 animate-spin dark:text-gray-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="#1C64F2"/>
                            </svg>
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
                    <td class="whitespace-nowrap px-2 py-1.5">
                        <span class="w-16 px-2 py-1.5 text-xs md:text-sm font-medium text-gray-900">{{ $user->PaykeResource->version }}</span>
                    </td>
                    <td class="whitespace-nowrap px-2 py-1.5 text-xs">
                        @if($user->enable_affiliate)
                            可能
                        @else
                            不可
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
                    <td class="whitespace-nowrap px-2 py-1.5 text-sm text-gray-900 hidden md:table-cell">@if($user->PaykeHost) {{$user->PaykeHost->name}} @else <span class="text-xs">未設定</span> @endif</td>
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
    <script>
    async function getJobQueue() {
      const res = await fetch(`{{ route('api.jobqueue.index') }}`);
      const retjson = await res.json();
      return retjson.jobs;
    };

    async function getPaykeUser(user_id) {
      let url = `{{ route('api.payke_user.profile', ["userId" => 123456789]) }}`.replace("123456789", user_id);
      const res = await fetch(url);
      const retjson = await res.json();
      return retjson.user;
    };

    async function display_job_messages() {
        const jobs = await getJobQueue();
        console.log(jobs);
        job_message_box.innerHTML = "";
        jobs.forEach(function(job) {
            let listhtml = '';
            listhtml = listhtml + `<li>${ job.message }`;
            if(job.is_running) listhtml = listhtml + `<span id="running_time" start_at="${ job.reserved_at }"><span>`;
            listhtml = listhtml + `</li>`;
            job_message_box.innerHTML = job_message_box.innerHTML + listhtml;
        }, jobs);

        let is_next = jobs.length > 0;
        if(!is_next) return;
        setTimeout(display_job_messages, 7000);
    }

    async function display_deploy_status() {
        const deployStatusBoxEles = Array.from(document.getElementsByClassName("deploy_status_box"));

        let is_next = false;
        deployStatusBoxEles.forEach(async (ele, index) => {
            const user_id = ele.getAttribute("user_id");
            const is_update = ele.getAttribute("is_update");
            if(is_update == "false") return;
            is_next = true;

            console.log(user_id);
            const user = await getPaykeUser(user_id);
            console.log(user);
            const new_is_update = user.is_update_waiting || user.is_updating_now;
            ele.setAttribute("is_update", new_is_update);

            for(const divEle of ele.children)
            {
                if(user.is_updating_now)
                {
                    if(divEle.getAttribute("name", "none") === "is_updating_now")
                    {
                        divEle.classList.remove("hidden");
                    } else {
                        divEle.classList.add("hidden");
                    }
                }
                if(user.is_active)
                {
                    if(divEle.getAttribute("name", "none") === "is_active")
                    {
                        divEle.classList.remove("hidden");
                    } else {
                        divEle.classList.add("hidden");
                    }
                }
                if(user.is_before_setting)
                {
                    if(divEle.getAttribute("name", "none") === "is_before_setting")
                    {
                        divEle.classList.remove("hidden");
                    } else {
                        divEle.classList.add("hidden");
                    }
                }
                if(user.has_error)
                {
                    if(divEle.getAttribute("name", "none") === "has_error")
                    {
                        divEle.classList.remove("hidden");
                    } else {
                        divEle.classList.add("hidden");
                    }
                }
            };
        });

        if(!is_next) return;
        setTimeout(display_deploy_status, 7000);
    }

    function loop() {
        running_time = document.getElementById("running_time");
        if(running_time) {
            const now_sec = Math.floor((new Date()).getTime()/1000);
            const start_at = running_time.getAttribute("start_at");
            let running_sec = now_sec - start_at;
            if(running_sec) running_time.innerHTML = `${running_sec} 秒経過`;
        }
        setTimeout(loop, 1000);
    }

    async function onload() {
        display_job_messages();
        display_deploy_status();
        loop();
    }

    onload();
  </script>
</x-layouts.basepage>
