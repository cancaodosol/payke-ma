<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Payke | 初回設定
        </h2>
    </x-slot>

    <div hidden>{{ $payke_user = Auth::user()->PaykeUsers[0]; }}</div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden sm:rounded-lg">
                <form action="{{ route('profile.update_app_name') }}" method="post">
                @method('POST')
                @csrf
                <div class="space-y-12 sm:space-y-16">
                    <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Paykeのご利用開始ありがとうございます。</h2>
                    <p class="mt-2 mb-2 max-w-2xl text-sm leading-6 text-gray-600">まず初めに、ご利用になるPaykeの公開アプリ名を設定してください。</p>

                    <div class="mt-8">
                        @if(session('feedback.success'))
                            <p style="color: green">{{ session('feedback.success') }}</p>
                        @endif
                        <!-- Your Message Area -->
                        @if ($errors->any())
                            <x-messages.errors title="入力内容に問題があります。" :errors="$errors->all()"/>
                        @endif
                        @if (session('successTitle'))
                            <x-messages.success title="{{ session('successTitle') }}" message="{{ session('successMessage') }}"/>
                        @endif
                        @if (session('warnTitle'))
                            <x-messages.warn title="{{ session('warnTitle') }}" message="{{ session('warnMessage') }}"/>
                        @endif
                        @if (session('errorTitle'))
                            <x-messages.error title="{{ session('errorTitle') }}" message="{{ session('errorMessage') }}"/>
                        @endif
                    </div>

                    <div id="app_url_base" hidden>https://{{$payke_user->PaykeHost->hostname}}/USER_APP_NAME</div>

                    <div class="mt-5 space-y-8 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10">
                        <input type="hidden" name="id" value="{{ $payke_user->id }}"/>
                        <x-forms.appname name="user_app_name" value="{{ $payke_user->user_app_name }}" label="https://{{ $payke_user->PaykeHost->hostname }}/" example="例) tarotaro7" pattern="^[0-9a-zA-Z]+$" explain="この名前がPaykeのURLとなります。英数字のみ使用可能です。" addSubmit="この公開アプリ名に設定する"/>
                    </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
