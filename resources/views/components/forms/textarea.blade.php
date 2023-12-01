<div class="sm:grid sm:grid-cols-6 sm:items-start sm:gap-4 sm:py-3">
    <label for="{{ $name }}" class="block text-sm lg:text-right font-medium leading-6 text-gray-900 sm:pt-1.5">{{ $label }}</label>
    <div class="mt-2 sm:col-span-5 sm:mt-0">
        <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $row ?? 3}}" class="block w-full max-w-2xl rounded-md border-0 p-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ old($name) ?? $value ?? '' }}</textarea>
    </div>
</div>