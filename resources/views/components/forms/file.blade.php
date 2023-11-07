<div class="sm:grid sm:grid-cols-6 sm:items-start sm:gap-4 sm:py-3">
    <label for="{{ $name }}" class="block text-sm text-right font-medium leading-6 text-gray-900 sm:pt-1.5">{{ $label }}</label>
    <div class="mt-2 sm:col-span-5 sm:mt-0">
        <div class="flex sm:max-w-md">
            <input type="file" name="{{ $name }}" id="{{ $name }}" autocomplete="{{ $name }}" value="{{ old($name, '') }}" class="block w-full text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" placeholder="{{ $example ?? ''}}">
        </div>
        @if($explain ?? false)
            <p class="mt-1 text-xs leading-6 text-gray-500">{{ $explain }}</p>
        @endif
    </div>
</div>