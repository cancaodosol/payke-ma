<div class="sm:grid sm:grid-cols-6 sm:items-start sm:gap-4 sm:py-3">
    <label for="{{ $name }}" class="block text-sm text-right font-medium leading-6 text-gray-900 sm:pt-1.5">{{ $label }}</label>
    <div class="mt-2 sm:col-span-2 sm:mt-0">
        <select id="{{ $name }}" name="{{ $name }}" autocomplete="{{ $name }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
        @foreach ($list as $item)
            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
        @endforeach
        </select>
    </div>
    @if($addPageLink ?? false)
        <div class="mt-2 sm:col-span-3 sm:mt-0">
            <a type="button" href="{{ $addPageLink }}" class="rounded bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-600 shadow-sm hover:bg-indigo-100">➕</a>
        </div>
    @endif
</div>