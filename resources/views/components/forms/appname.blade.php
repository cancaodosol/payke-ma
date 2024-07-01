<div class="sm:items-start sm:gap-4 sm:py-3 flex items-center">
    <label for="{{ $name }}" class="block font-medium leading-6 text-gray-900 sm:pt-1.5">{{ $label }}</label>
    <div class="mt-2 sm:mt-0">
        <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
            <input type="text" name="{{ $name }}" id="{{ $name }}" autocomplete="{{ $name }}" value="{{ old($name) ?? $value ?? '' }}" {{ $disabled ?? false ? 'disabled' : '' }} {{ $pattern ?? false ? 'pattern='.$pattern : '' }} class="block flex-1 border-0 bg-transparent py-1.5 px-2 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="{{ $example ?? ''}}">
        </div>
    </div>
    @if($addSubmit ?? false)
        <div class="mt-1 hidden sm:block">
            <button type="submit" class="need_loading_btn inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $addSubmit }}</button>
        </div>
    @endif
</div>
@if($addSubmit ?? false)
    <div class="text-right pr-2 sm:hidden">
        <button type="submit" class="need_loading_btn inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $addSubmit }}</button>
    </div>
@endif
@if($explain ?? false)
    <p class="my-4 text-xs leading-6 text-gray-500">{{ $explain }}</p>
@endif