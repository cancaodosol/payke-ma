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
                        <h3 class="text-2xl font-bold tracking-tight leading-6 text-gray-900">Payke 利用停止の手続き</h3>
                        <p class="max-w-2xl text-sm text-gray-500 pb-4"></p>
                      </div>
                      <div class="mt-8 pt-6">
                        <div class="font-bold">停止後のPaykeについて</div>
                        <ul class="mt-3 text-sm list-disc pl-4 lg:pl-6">
                          <li class="mt-2">停止されると、即時Paykeにログインできなくなります。</li>
                          <li class="mt-2">次回支払いタイミングまで期間が残っていても、停止後すぐに利用できなくなります（残期間での差額返金はございません）。</li>
                        </ul>
                        <div class="font-bold mt-8">停止の前に支払い注文の状況をお確かめください</div>
                        <ul class="mt-3 text-sm list-disc pl-4 lg:pl-6">
                          <li class="mt-2">利用停止をする前に、継続/分割支払いが全てキャンセル・停止されていることを確認してください。</li>
                          <li class="mt-2">支払いが継続していても一切Paykeからの設定・データ通信ができなくなります。<br>エラーが生じても対応できませんのでご注意ください。</li>
                        </ul>

                        <div class="font-bold mt-8">アフィリエイト機能を利用されていた場合</div>
                        <ul class="mt-3 text-sm list-disc pl-4 lg:pl-6">
                          <li class="mt-2">利用停止後、すべての画面の確認ができなくなります。<br>アフィリエイト報酬の未払いが残っていないか十分にご確認ください。</li>
                          <li class="mt-2">継続支払いの注文でアフィリエイト支払いが継続していた場合も、今後の確認は一切できません。<br>すべての継続/分割支払いが全てキャンセル・停止されていて、アフィリエイト報酬も支払い済みであることを確認してください。</li>
                        </ul>
                        <div class="text-xl mt-10 lg:text-right">Paykeの利用を停止しますが、よろしいでしょうか？</div>
                        <div class="text-xs mt-1 lg:text-right">※ 停止するボタンクリック後、即時Paykeは利用できなくなりますのでご注意ください。</div>
                        <div class="mt-6 lg:mt-2 lg:text-right flex justify-end">
                          <a href="{{ route('profile.index') }}" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Paykeをこのまま利用する
                          </a>
                          <form action="{{ route('profile.cancel_confirm') }}" method="post" class="ml-2">
                            @method('POST')
                            @csrf
                            <input type="hidden" name="payke_user_uuid" value="{{ $pUser->uuid }}"/>
                            <button class="rounded-md bg-white px-2.5 py-1.5 text-sm font-bold text-red-500 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50">
                              同意して、停止する
                            </button>
                          </form>
                        </div>
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
