<x-layouts.basepage title="管理ユーザー一覧" current="管理ユーザー一覧">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
        <h1 class="text-base font-semibold leading-6 text-gray-900">管理ユーザー一覧</h1>
        <!-- <p class="mt-2 text-sm text-gray-700">リリース済みPaykeの一覧です。</p> -->
        </div>
        <div class="mt-4 sm:ml-2 sm:mt-0 sm:flex-none">
        <a href="{{ route('register') }}" type="button" class="block rounded-md bg-emerald-500 px-2 py-1 text-center text-xs font-semibold text-white shadow-sm hover:bg-emerald-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500">
            ＋ 新規登録</a>
        </div>
    </div>
    <ul id="job_message_box" class="mt-4 text-xs"></ul>
    <div class="mt-4 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <table class="min-w-full divide-y divide-gray-300">
            <thead>
                <tr>
                    <th scope="col" class="whitespace-nowrap py-3.5 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 sm:pl-0 hidden md:table-cell">No.</th>
                    <th scope="col" class="whitespace-nowrap py-3.5 text-left text-sm font-semibold text-gray-900">名前</th>
                    <th scope="col" class="whitespace-nowrap pl-4 py-3.5 text-left text-sm font-semibold text-gray-900">メールアドレス</th>
                    <th scope="col" class="whitespace-nowrap pl-4 py-3.5 text-left text-sm font-semibold text-gray-900">パスワード</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <div hidden>{{ $no = 1; }}</div>
                @foreach($users as $user)
                <tr>
                    <td class="whitespace-nowrap py-1.5 pl-4 pr-3 text-sm text-gray-500 text-center sm:pl-0 hidden md:table-cell">{{ $no++ }}</td>
                    <td class="whitespace-nowrap py-1.5 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">{{ $user->name }}</td>
                    <td class="whitespace-nowrap pl-2 py-1.5 text-sm text-gray-900">{{ $user->email }}</td>
                    <td class="whitespace-nowrap pl-2 py-1.5 text-sm text-gray-900">
                        <form action="{{ route('admin.edit.password') }}" method="post">
                            @method('POST')
                            @csrf
                            <div class="flex flex-row">
                                <input type="hidden" name="id" value="{{ $user->id }}"/>
                                <div class="flex rounded-md shadow-sm w-24 ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                    <input name="password" value="12345678" type="password" class="w-24 border-0 bg-transparent py-0 px-2 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-4"/>
                                </div>
                                <button class="reset_password_btn ml-1 text-xs bg-white hover:bg-gray-100 text-gray-900 font-semibold py-1 px-4 border border-gray-300 rounded-lg shadow" onClick="return false;">パスワード再作成</button>
                                <button hidden type="submit" class="ml-1 text-xs focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold py-1 px-4 border border-gray-300 rounded-lg shadow">更新</button>
                            </div>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        </div>
    </div>
    <script>
    function get_random_string(length = 10) {
        const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        return Array.from({ length }, () => chars[Math.floor(Math.random() * chars.length)]).join('');
    }
    async function onload() {
        const reset_password_btns = document.querySelectorAll(".reset_password_btn");
        reset_password_btns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                inputEle = e.target.previousElementSibling.children[0];
                editBtnEle = e.target.nextElementSibling;
                inputEle.setAttribute("type", "");
                inputEle.value = get_random_string();
                editBtnEle.hidden = false;
                e.target.hidden = true;
            });
        });
    }

    onload();
  </script>
</x-layouts.basepage>
