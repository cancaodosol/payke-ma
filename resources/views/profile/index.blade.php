<x-app-layout>
<div class="pb-16 pt-4">
<div class="lg:px-8">
        <div class="mx-auto flex flex-col lg:max-w-4xl">
          <main class="flex-1">
            <div class="relative mx-auto max-w-4xl">
              <div class="pb-16">
                <div class="px-4 sm:px-6 lg:px-0">
                  <div>
                    <!-- Description list with inline editing -->
                    <div class="mt-6 divide-y divide-gray-200">
                      <div class="space-y-1">
                        <h3 class="text-2xl font-bold tracking-tight leading-6 text-gray-900">Payke</h3>
                        @if(count($user->PaykeUsers) > 0)
                          <p class="max-w-2xl text-sm text-gray-500">こちらのログインURLから、Paykeをご利用ください。</p>
                        @else
                          <p class="max-w-2xl text-sm text-gray-500 pb-8">現在、利用できるPaykeはありません。</p>
                        @endif
                      </div>
                      @foreach($user->PaykeUsers as $pUser)
                      <div class="mt-6">
                        <dl class="divide-y divide-gray-200">
                          <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sm font-medium text-gray-500">プラン</dt>
                            <dd class="mt-1 flex items-end text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                              <span>{{ $pUser->deploy_setting_name() }}</span>
                              <form action="{{ route('profile.plan_view') }}" method="post">
                                @method('POST')
                                @csrf
                                <input type="hidden" name="payke_user_uuid" value="{{ $pUser->uuid }}"/>
                                <button class="text-xs text-blue-500 ml-2">変更</button>
                              </form>
                            </dd>
                          </div>
                          <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sm font-medium text-gray-500">ログインURL</dt>
                            <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                              <a href="{{ $user->PaykeUsers[0]->app_url }}">
                                <div class="flex">
                                    <span class="flex-grow text-blue-500 underline">{{ $pUser->app_url }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 ml-2 text-gray-500">
                                        <path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 0 0-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 0 0 .75-.75v-4a.75.75 0 0 1 1.5 0v4A2.25 2.25 0 0 1 12.75 17h-8.5A2.25 2.25 0 0 1 2 14.75v-8.5A2.25 2.25 0 0 1 4.25 4h5a.75.75 0 0 1 0 1.5h-5Z" clip-rule="evenodd" />
                                        <path fill-rule="evenodd" d="M6.194 12.753a.75.75 0 0 0 1.06.053L16.5 4.44v2.81a.75.75 0 0 0 1.5 0v-4.5a.75.75 0 0 0-.75-.75h-4.5a.75.75 0 0 0 0 1.5h2.553l-9.056 8.194a.75.75 0 0 0-.053 1.06Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                              </a>
                            </dd>
                          </div>
                          <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:pt-5">
                            <dt class="text-sm font-medium text-gray-500">初期ユーザー名</dt>
                            <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                              <span class="flex-grow">このサイトのログインに使用したメールアドレスです。</span>
                            </dd>
                          </div>
                          <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:pt-5">
                            <dt class="text-sm font-medium text-gray-500">初期パスワード</dt>
                            <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                              <span class="flex-grow">このサイトのログインに使用したパスワードです。</span>
                            </dd>
                          </div>
                          <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sm font-medium text-gray-500"></dt>
                            <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                              <span class="flex-grow"></span>
                            </dd>
                          </div>
                        </dl>
                      </div>
                      @endforeach
                    </div>
                    <div class="mt-6 divide-y divide-gray-200">
                      <div class="space-y-1">
                        <h3 class="text-2xl font-bold tracking-tight leading-6 text-gray-900">利用情報</h3>
                        <p class="max-w-2xl text-sm text-gray-500"></p>
                      </div>
                      <div class="mt-6">
                        <dl class="divide-y divide-gray-200">
                          <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sm font-medium text-gray-500">名前</dt>
                            <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                              <span class="flex-grow">{{ $user->name }}</span>
                            </dd>
                          </div>
                          <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:pt-5">
                            <dt class="text-sm font-medium text-gray-500">メールアドレス</dt>
                            <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                              <span class="flex-grow">{{ $user->email }}</span>
                            </dd>
                          </div>
                          <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sm font-medium text-gray-500">利用開始</dt>
                            <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                              <span class="flex-grow">{{ $user->created_at }}</span>
                            </dd>
                          </div>
                          <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                            <dt class="text-sm font-medium text-gray-500"></dt>
                            <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                              <span class="flex-grow"></span>
                            </dd>
                          </div>
                        </dl>
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
