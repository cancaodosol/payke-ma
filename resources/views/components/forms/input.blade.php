<div class="sm:grid sm:grid-cols-6 sm:items-start sm:gap-4 sm:py-3">
    <label for="{{ $name }}" class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5 lg:text-right">{{ $label }}</label>
    <div class="mt-2 sm:col-span-5 sm:mt-0">
        <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
            <input type="text" name="{{ $name }}" id="{{ $name }}" autocomplete="{{ $name }}" value="{{ old($name) ?? $value ?? '' }}" {{ $disabled ?? false ? 'disabled' : '' }} {{ $pattern ?? false ? 'pattern='.$pattern : '' }} class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="{{ $example ?? ''}}">
        </div>
        @if($explain ?? false)
            <p class="mt-1 text-xs leading-6 text-gray-500">{{ $explain }}</p>
        @endif
    </div>
</div>