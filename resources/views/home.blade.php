<x-layouts.basepage title="ホーム" current="ホーム">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold leading-6 text-gray-900">Payke 環境状況</h1>
        </div>
    </div>
    <div class="flow-root mt-4 ">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="flex flex-wrap mb-5">      
                @foreach($users_summary as $tag_users)
                <a href="{{ route('payke_user.index.tagId', ['tagId' => $tag_users['tag_id']]) }}">
                    <div class="p-3 mr-2 w-44 border rounded-md border-slate-200">
                        <div class="text-{{ $tag_users['tag_color'] ?? 'grey' }}-400 text-sm">{{ $tag_users['tag_name'] ?? "" }}</div>
                        <ul class="py-3 text-xs">
                            @if(count($tag_users['user_statuses']) > 0)
                                @foreach($tag_users['user_statuses'] as $user_status)
                                <li>
                                    <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                                        <div class="flex-none rounded-full p-1 text-{{ $user_status['status_color'] ?? 'grey'}}-300 bg-{{ $user_status['status_color'] ?? 'grey'}}-300/10">
                                            <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                                        </div>
                                        <div class="text-xs">{{ $user_status['status_name'] }} ： {{ count($user_status['users']) }}人</div>
                                    </div>
                                </li>
                                @endforeach
                            @else
                                <li class="text-xs">なし</li>
                            @endif
                        </ul>
                    </div>
                </a>
                @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="sm:flex sm:items-center mt-5">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold leading-6 text-gray-900">過去3日間のデプロイログ</h1>
        </div>
    </div>
    <ul id="job_message_box" class="mt-2 text-xs"></ul>
    <div class="flow-root mt-4 ">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            @if(count($logs) > 0)
            <table class="min-w-full divide-y divide-gray-300">
            <thead>
                <tr>
                    <th scope="col" class="whitespace-nowrap pt-3.5 pb-1 pl-4 pr-3 text-center text-xs font-semibold text-gray-900 sm:pl-0">ID</th>
                    <th scope="col" class="whitespace-nowrap px-2 pt-3.5 pb-1 text-left text-xs font-semibold text-gray-900">ユーザー名</th>
                    <th scope="col" class="whitespace-nowrap pt-3.5 pb-1 text-left text-xs font-semibold text-gray-900">実行日時</th>
                    <th scope="col" class="whitespace-nowrap pt-3.5 pb-1 text-center text-xs font-semibold text-gray-900">タイプ</th>
                    <th scope="col" class="whitespace-nowrap px-2 pt-3.5 pb-1 text-left text-xs font-semibold text-gray-900">タイトル</th>
                    <th scope="col" class="whitespace-nowrap px-2 pt-3.5 pb-1 text-left text-xs font-semibold text-gray-900">メッセージ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <div hidden>{{ $old_user_id = ''; }}</div>
                @foreach($logs as $log)
                <tr>
                    <td class="whitespace-nowrap pt-1.5 pb-1 pl-4 pr-3 text-xs text-gray-500 text-center sm:pl-0">{{ $log->id }}</td>
                    <td class="whitespace-nowrap px-2 pt-1.5 pb-1 text-xs">
                        <a class="deploy_status_box" href="{{ route('payke_user.profile', ['userId' => $log->user_id]) }}">
                        @if($log->user_id != $old_user_id)
                            {{ $log->user_name }}
                        @else
                        <div class="pl-5">〃</div>
                        @endif
                        <div hidden>{{ $old_user_id = $log->user_id; }}</div>
                        </a>
                    </td>
                    <td class="whitespace-nowrap pt-1.5 pb-1 pl-4 pr-3 text-xs text-gray-500 sm:pl-0">
                        <a class="deploy_status_box" href="{{ route('deploy_log.show', ['userId' => $log->user_id]) }}">{{ $log->created_at }}</a>    
                    </td>
                    <td class="whitespace-nowrap px-2 pt-1.5 pb-1 text-right text-xs">
                        <a href="{{ route('deploy_log.edit', ['id' => $log->id]) }}">
                        @if($log->is_version_info())
                            <div class="text-green-600 font-semibold">成功</div>
                        @elseif($log->is_success())
                            <div class="text-green-600 font-semibold">成功</div>
                        @elseif($log->is_warm())
                            <div class="text-yellow-400 font-semibold">注目</div>
                        @elseif($log->is_error())
                            <div class="text-rose-500 font-semibold">エラー</div>
                        @elseif($log->title == "新規作成")
                            <div class="text-yellow-400 font-semibold">！新規！</div>
                        @elseif($log->is_other_info())
                           <div class="text-gray-600">通常</div>
                        @endif
                        </a>
                    </td>
                    <td class="whitespace-nowrap px-2 pt-1.5 pb-1 text-xs">
                        <a href="{{ route('deploy_log.edit', ['id' => $log->id]) }}">
                        @if($log->is_warm() || $log->title == "新規作成")
                            <div class="text-yellow-400 font-semibold">{{ $log->title }}</div>
                        @elseif($log->is_error())
                            <div class="text-rose-500 font-semibold">{{ $log->title }}</div>
                        @else
                            {{ $log->title }}
                        @endif
                        </a>
                    </td>
                    <td class="whitespace-nowrap px-2 pt-1.5 pb-1 text-xs">     
                        <a href="{{ route('deploy_log.edit', ['id' => $log->id]) }}" class="flex flex-wrap">               
                        @if($log->is_warm() || $log->title == "新規作成")
                            <div class="text-yellow-400">{{ $log->message }}</div>
                        @elseif($log->is_error())
                            <div class="text-rose-500 font-semibold">{{ $log->message }}</div>
                        @else
                            <div>{{ $log->message }}</div>
                        @endif
                        @if($log->memo)
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 ml-1">
                                <path fill-rule="evenodd" d="M8 2C4.262 2 1 4.57 1 8c0 1.86.98 3.486 2.455 4.566a3.472 3.472 0 0 1-.469 1.26.75.75 0 0 0 .713 1.14 6.961 6.961 0 0 0 3.06-1.06c.403.062.818.094 1.241.094 3.738 0 7-2.57 7-6s-3.262-6-7-6ZM5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm7-1a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM8 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-1"><</div>
                            <div class="text-rose-500 ml-1 w-32 overflow-hidden" title="{{ $log->memo }}">{{ $log->memo }}</div>
                        @endif
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
            <div class="mt-5 pt-3">
                {!! $logs->render() !!}
            </div>
            @else
                <div class="text-xs mb-5">
                    最近の更新はありません。
                </div>
            @endif
            <div class="text-xs text-blue-500">
                <a href="{{ route('deploy_log.index') }}">全期間分のログを見る</a>
            </div>
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
        loop();
    }

    onload();
  </script>
</x-layouts.basepage>
