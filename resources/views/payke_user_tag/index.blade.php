<x-layouts.basepage title="Payke一覧" current="Payke一覧">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold leading-6 text-gray-900">タグ一覧</h1>
            <p class="mt-2 text-sm text-gray-700">
                タグの登録を行います。色は、以下を参考にしてください。
            </p>
            <div class="mt-4">
                <a href="https://tailwindcss.com/docs/text-color" class="hover:bg-gray-100 text-xs font-semibold me-0.5 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-400 inline-flex items-center justify-center">none</a>
                <a href="https://tailwindcss.com/docs/text-color" class="bg-blue-100 hover:bg-blue-200 text-blue-800 text-xs font-semibold me-0.5 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400 inline-flex items-center justify-center">blue</a>
                <a href="https://tailwindcss.com/docs/text-color" class="bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-semibold me-0.5 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-400 inline-flex items-center justify-center">gray</a>
                <a href="https://tailwindcss.com/docs/text-color" class="bg-red-100 hover:bg-red-200 text-red-800 text-xs font-semibold me-0.5 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400 inline-flex items-center justify-center">red</a>
                <a href="https://tailwindcss.com/docs/text-color" class="bg-green-100 hover:bg-green-200 text-green-800 text-xs font-semibold me-0.5 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400 inline-flex items-center justify-center">green</a>
                <a href="https://tailwindcss.com/docs/text-color" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 text-xs font-semibold me-0.5 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-yellow-400 border border-yellow-400 inline-flex items-center justify-center">yellow</a>
                <a href="https://tailwindcss.com/docs/text-color" class="bg-indigo-100 hover:bg-indigo-200 text-indigo-800 text-xs font-semibold me-0.5 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-indigo-400 border border-indigo-400 inline-flex items-center justify-center">indigo</a>
                <a href="https://tailwindcss.com/docs/text-color" class="bg-purple-100 hover:bg-purple-200 text-purple-800 text-xs font-semibold me-0.5 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-purple-400 border border-purple-400 inline-flex items-center justify-center">purple</a>
                <a href="https://tailwindcss.com/docs/text-color" class="bg-pink-100 hover:bg-pink-200 text-pink-800 text-xs font-semibold me-0.5 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-pink-400 border border-pink-400 inline-flex items-center justify-center">pink</a>
            </div>
        </div>
    </div>
    <div class="mt-2 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block py-2 align-middle sm:px-6 lg:px-8">
                <form action="{{ route('payke_user_tags.edit.post') }}", method="post" enctype="multipart/form-data">
                    @method('POST')
                    @csrf
                    <input type="hidden" name="tags_count" value="{{ count($tags) }}"/>
                    <table class="divide-y divide-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="whitespace-nowrap py-3.5 text-center text-xs font-semibold text-gray-900">No.</th>
                            <th scope="col" class="whitespace-nowrap py-3.5 text-center text-xs font-semibold text-gray-900">並び順</th>
                            <th scope="col" class="whitespace-nowrap py-3.5 text-center text-xs font-semibold text-gray-900">名前</th>
                            <th scope="col" class="whitespace-nowrap py-3.5 text-center text-xs font-semibold text-gray-900">色</th>
                            <th scope="col" class="whitespace-nowrap py-3.5 text-center text-xs font-semibold text-gray-900">一覧に表示しない</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <div hidden>{{ $no = 1; }}</div>
                        @foreach($tags as $tag)
                        <tr>
                            <td class="whitespace-nowrap pl-2 py-1.5 text-sm text-gray-900 text-center">
                                {{ $no }}
                                <input type="hidden" name="tag_id_{{$no}}" value="{{ $tag->id }}"/>
                            </td>
                            <td class="whitespace-nowrap py-1.5 pl-4 text-sm text-gray-500 text-center">
                                <input class="text-center" name="order_no_{{$no}}" value="{{ $tag->order_no }}" />
                            </td>
                            <td class="whitespace-nowrap pl-2 py-1.5 text-sm text-gray-900 text-center">
                                <input class="text-center" name="name_{{$no}}" value="{{ $tag->name }}" />
                            </td>
                            <td class="whitespace-nowrap pl-2 py-1.5 text-sm text-gray-900 text-center">
                                <input class="text-center" name="color_{{$no}}" value="{{ $tag->color }}" />
                            </td>
                            <td class="whitespace-nowrap pl-2 py-1.5 text-sm text-gray-900 text-center">
                                <input class="text-center" type="checkbox" name="is_hidden_{{$no}}" {{ $tag->is_hidden ? 'checked' : '' }} />
                            </td>
                            <div hidden>{{ $no++; }}</div>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="whitespace-nowrap pl-2 py-1.5 text-sm text-gray-900 text-center">
                                New!
                            </td>
                            <td class="whitespace-nowrap py-1.5 pl-4 text-sm text-gray-500 text-center">
                                <input class="text-center" name="order_no_new" />
                            </td>
                            <td class="whitespace-nowrap pl-2 py-1.5 text-sm text-gray-900 text-center">
                                <input class="text-center" name="name_new"/>
                            </td>
                            <td class="whitespace-nowrap pl-2 py-1.5 text-sm text-gray-900 text-center">
                                <input class="text-center" name="color_new"/>
                            </td>
                            <td class="whitespace-nowrap pl-2 py-1.5 text-sm text-gray-900 text-center">
                                <input class="text-center" type="checkbox" name="is_hidden_new"/>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">更新する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.basepage>
