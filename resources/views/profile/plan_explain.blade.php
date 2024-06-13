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
                        <h3 class="text-2xl font-bold tracking-tight leading-6 text-gray-900">Payke プラン変更の手続き</h3>
                        <p class="max-w-2xl text-sm text-gray-500 pb-4"></p>
                      </div>
                      <div class="mt-8 pt-6">
                        <div class="font-bold">プラン変更後のPaykeについて</div>
                        <ul class="mt-3 text-sm list-disc pl-4 lg:pl-6">
                          <li class="mt-2">プラン変更後、現在お使いのPaykeが新しいプランの内容に更新されます。</li>
                          <li class="mt-2">古いプランは自動的にキャンセルされます。</li>
                        </ul>

                        <div class="font-bold mt-8">プロユースプランからベーシックプランに変更される場合の注意事項</div>
                        <div class="text-sm pl-2 lg:pl-4">
                          <div class="mt-3">
                            プラン変更後、「アフィリエイト機能」と「複数管理ユーザー機能」に関連する表示が全てされなくなります。
                          </div>
                          <div class="mt-5">
                            プラン変更前に、
                          </div>
                          <ul class="mt-1 text-sm list-disc pl-4 lg:pl-6">
                            <li class="mt-2">アフィリエイター・共同管理者へ、機能停止のアナウンスをする</li>
                            <li class="mt-2">アフィリエイト報酬を支払い終える</li>
                          </ul>
                          <div class="mt-2">
                            など、確実に機能を利用しない状態になってから変更されるようにしてください。
                          </div>
                        </div>
                        <div class="mt-10 lg:text-right flex justify-end">
                          <form action="{{ route('profile.plan_view') }}" method="post">
                            @method('POST')
                            @csrf
                            <input type="hidden" name="payke_user_uuid" value="{{ $pUser->uuid }}"/>
                            <button class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                プラン一覧へ戻る
                            </button>
                          </form>
                          <a href="{{ $unit->get_value('payke_order_url') }}?arg1=mode_cplan&arg2=puuid_{{ $pUser->uuid }}" class="ml-2 rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            プラン変更の手続きを進める
                          </a>
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
