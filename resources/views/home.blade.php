<x-layouts.basepage title="ホーム" current="ホーム">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold leading-6 text-gray-900">バージョンアップログ一覧</h1>
        </div>
    </div>
    <ul id="job_message_box" class="mt-2 text-xs"></ul>
    <div class="flow-root mt-4 ">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <table class="min-w-full divide-y divide-gray-300">
            <thead>
                <tr>
                    <th scope="col" class="whitespace-nowrap pt-3.5 pb-1 pl-4 pr-3 text-center text-xs font-semibold text-gray-900 sm:pl-0 hidden md:table-cell">ID</th>
                    <th scope="col" class="whitespace-nowrap px-2 pt-3.5 pb-1 text-left text-xs font-semibold text-gray-900">ユーザー名</th>
                    <th scope="col" class="whitespace-nowrap pt-3.5 pb-1 text-left text-xs font-semibold text-gray-900">実行日時</th>
                    <th scope="col" class="whitespace-nowrap pt-3.5 pb-1 text-center text-xs font-semibold text-gray-900">タイプ</th>
                    <th scope="col" class="whitespace-nowrap px-2 pt-3.5 pb-1 text-left text-xs font-semibold text-gray-900">タイトル</th>
                    <th scope="col" class="whitespace-nowrap px-2 pt-3.5 pb-1 text-left text-xs font-semibold text-gray-900">メッセージ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <div hidden>{{ $old_user_name = ''; }}</div>
                @foreach($logs as $log)
                <tr>
                    <td class="whitespace-nowrap pt-1.5 pb-1 pl-4 pr-3 text-xs text-gray-500 text-center sm:pl-0 hidden md:table-cell">{{ $log->id }}</td>
                    <td class="whitespace-nowrap px-2 pt-1.5 pb-1 text-xs">
                        <a class="deploy_status_box" href="{{ route('deploy_log.index', ['userId' => $log->user_id]) }}">
                        @if($log->user_name != $old_user_name)
                            {{ $log->user_name }}
                        @else
                        <div class="pl-5">〃</div>
                        @endif
                        <div hidden>{{ $old_user_name = $log->user_name; }}</div>
                        </a>
                    </td>
                    <td class="whitespace-nowrap pt-1.5 pb-1 pl-4 pr-3 text-xs text-gray-500 sm:pl-0">{{ $log->created_at }}</td>
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
                        <a href="{{ route('deploy_log.edit', ['id' => $log->id]) }}">               
                        @if($log->is_warm() || $log->title == "新規作成")
                            <div class="text-yellow-400">{{ $log->message }}</div>
                        @elseif($log->is_error())
                            <div class="text-rose-500 font-semibold">{{ $log->message }}</div>
                        @else
                            {{ $log->message }}
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
