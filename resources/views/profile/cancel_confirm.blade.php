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
                      <div class="mt-6">
                        <p class="py-6">{{ $message }}<p>
                        <a href="{{route('profile.index')}}" class="text-blue-500 underline">ホームへ</a>
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
