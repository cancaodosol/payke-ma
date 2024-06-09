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
                      <div class="mt-6 pt-6">
                        <div>Paykeの利用を停止しますが、よろしいでしょうか？</div>
                        <ul class="mt-5 text-sm list-disc pl-6">
                          <li>利用停止後、現在販売中の商品販売ページへはアクセスできなくなります。</li>
                          <li>利用停止後、現在販売中の継続/分割払い商品への支払いは、ストップします。</li>
                        </ul>
                        <form action="{{ route('profile.cancel_confirm') }}" method="post" class="mt-2 text-right">
                          @method('POST')
                          @csrf
                          <input type="hidden" name="payke_user_uuid" value="{{ $pUser->uuid }}"/>
                          <button class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            同意して、停止する
                          </button>
                        </form>
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