<div class="mt-10 space-y-10 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0">
    <fieldset>
    <legend class="sr-only">{{ $label }}</legend>
    <div class="sm:grid sm:grid-cols-6 sm:gap-4 sm:py-3">
        <div class="text-sm text-right leading-6 text-gray-900" aria-hidden="true">{{ $label }}</div>
        <div class="mt-4 sm:col-span-5 sm:mt-0">
        <div class="max-w-lg space-y-6">
            <div class="relative flex gap-x-3">
            <div class="flex h-6 items-center">
                <input id="{{ $name }}" name="{{ $name }}" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
            </div>
            <div class="text-sm leading-6">
                <label for="{{ $name }}" class="font-medium text-gray-900">{{ $cbText }}</label>
            </div>
            </div>
        </div>
    </div>
    </fieldset>
</div>