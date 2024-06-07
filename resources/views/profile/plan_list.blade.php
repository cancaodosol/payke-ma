<x-app-layout>
<div class="pb-16 pt-4">
<div class="lg:px-8">
        <div class="mx-auto flex flex-col lg:max-w-4xl">
          <main class="flex-1">
            <div class="relative mx-auto max-w-4xl">
              <div class="pb-16">
                <div class="px-4 sm:px-6 lg:px-0">
                  <div>
                    <div class="mt-6 divide-y divide-gray-200">
                      <div class="space-y-1">
                        <h3 class="text-2xl font-bold tracking-tight leading-6 text-gray-900">Payke プラン変更</h3>
                        <p class="max-w-2xl text-sm text-gray-500 pb-4"></p>
                      </div>
                      <div class="mt-6">
                        <ul role="list" class="divide-y divide-gray-100">
                          @foreach($units as $unit)
                          <li class="flex items-center justify-between gap-x-6 py-5 px-3 rounded-lg hover:bg-gray-50">
                            <div class="min-w-0">
                              <div class="flex items-start gap-x-3">
                                <p class="text-sm font-semibold leading-6 text-gray-900">
                                  {{ $unit->get_value('setting_title') }}
                                </p>
                              </div>
                              <div class="flex flex-wrap items-center text-xs leading-5 text-gray-500">
                                <div class="flex gap-x-2 mt-2">
                                    <pre>{{ $unit->get_value('plan_explain') }}</pre>
                                </div>
                              </div>
                            </div>
                            <div class="flex flex-none items-center gap-x-4">
                                <a href="{{ $unit->get_value('payke_order_url') }}?arg1=puuid_{{ $pUser->uuid }}" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    変更
                                </a>
                            </div>
                          </li>
                          @endforeach
                        </ul>
                      </div>
                    </div>
                    <div class="mt-14 divide-y divide-gray-200">
                      <div class="space-y-1">
                      </div>
                      <div class="mt-6">
                        <ul role="list" class="divide-y divide-gray-100">
                          <li class="flex items-center justify-between gap-x-6 py-5 px-3 rounded-lg hover:bg-gray-50">
                            <div class="min-w-0">
                              <div class="flex items-start gap-x-3">
                                <p class="text-sm font-semibold leading-6 text-gray-900">
                                  利用停止
                                </p>
                              </div>
                              <div class="flex flex-wrap items-center text-xs leading-5 text-gray-500">
                                <div class="flex gap-x-2 mt-2">
                                    <pre>Paykeの利用を終了します。</pre>
                                </div>
                              </div>
                            </div>
                            <div class="flex flex-none items-center gap-x-4">
                                <a href="#" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                  停止
                                </a>
                            </div>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>
</x-app-layout>
