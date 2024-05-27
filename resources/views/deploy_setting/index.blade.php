<x-layouts.basepage title="親Payke連携設定" current="親Payke連携設定">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h2 class="text-base font-semibold leading-7 text-gray-900">親Payke連携設定</h2>
          <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">親PaykeとのWebhookの設定、および、決済時に作成するPaykeECの環境設定を行います。</p>
        </div>
        <div class="mt-4 sm:ml-2 sm:mt-0 sm:flex-none">
          <a href="{{ route('deploy_setting.create') }}" type="button" class="block rounded-md bg-emerald-500 px-2 py-1 text-center text-xs font-semibold text-white shadow-sm hover:bg-emerald-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500">
            ＋ 新規登録
          </a>
        </div>
    </div>
    <div class="mt-10">  
      <ul role="list" class="divide-y divide-gray-100">
        @foreach($units as $unit)
        <li class="flex items-center justify-between gap-x-6 py-5 px-3 rounded-lg hover:bg-gray-50">
          <div class="min-w-0">
            <div class="flex items-start gap-x-3">
              <!-- <svg viewBox="0 0 2 2" class="h-1 w-1 fill-current">
                  <circle cx="1" cy="1" r="1" />
              </svg> -->
              <p class="text-sm font-semibold leading-6 text-gray-900">
                {{ $unit->get_value('setting_title') }}
              </p>
              @if($unit->tag)
                @if(!$unit->tag->color || $unit->tag->color == "none")
                <a href="{{ route('payke_user.index.tagId', ['tagId' => $unit->tag->id]) }}" class="hover:bg-gray-100 text-xs font-semibold me-0.5 px-1.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-400 inline-flex items-center justify-center">
                    {{ $unit->tag->name }}
                </a>
                @else
                <a href="{{ route('payke_user.index.tagId', ['tagId' => $unit->tag->id]) }}" class="bg-{{$unit->tag->color}}-100 hover:bg-{{$unit->tag->color}}-200 text-{{$unit->tag->color}}-800 text-xs font-semibold me-0.5 px-1.5 py-0.5 rounded dark:bg-gray-700 dark:text-{{$unit->tag->color}}-400 border border-{{$unit->tag->color}}-400 inline-flex items-center justify-center">
                    {{ $unit->tag->name }}
                </a>
                @endif
              @endif
              @if($unit->payke)
              <p class="mt-0.5 px-1.0 py-0.5 text-xs font-medium">
                {{ $unit->payke->version }}
              </p>
              @endif
              @if($unit->host)
              <p class="mt-0.5 px-1.0 py-0.5 text-xs font-medium">
                  {{ $unit->host->name }}
              </p>
              @endif
              <p class="mt-0.5 px-1.0 py-0.5 text-xs font-medium">
                空きDB：{{ $unit->ready_dbs_count }}
              </p>
            </div>
            <div class="flex flex-wrap items-center text-xs leading-5 text-gray-500">
              <div class="flex gap-x-2 mt-2">
                  <span>送信先：</span>
                  <span>{{ route('payke.ec2ma', ['no' => $unit->no]) }}</span>
                  <button id="copy_btn" title="コピー" onClick="return false;" class="inline-flex justify-center text-xs bg-white hover:bg-gray-100 text-gray-900 font-semibold px-2 py-0.5 border border-gray-300 rounded-lg shadow">
                      <span>コピー</span>
                  </button>
              </div>
              <div class="flex gap-x-2 mt-2">
                  <span class="sm:ml-4">認証鍵：</span>
                  <span>{{ $unit->get_value('payke_x_auth_token') }}</span>
                  <button id="copy_btn" title="コピー" onClick="return false;" class="inline-flex justify-center text-xs bg-white hover:bg-gray-100 text-gray-900 font-semibold px-2 py-0.5 border border-gray-300 rounded-lg shadow">
                      <span>コピー</span>
                  </button>
              </div>
            </div>
          </div>
          <div class="flex flex-none items-center gap-x-4">
              <a href="{{ route('deploy_setting.edit', ['no' => $unit->no]) }}" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                  参照・編集<span class="sr-only">, GraphQL API</span>
              </a>
          </div>
        </li>
        @endforeach
      </ul>
    </div>
</x-layouts.basepage>