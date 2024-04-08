<div class="sm:grid sm:grid-cols-6 sm:items-start sm:gap-4 sm:py-3">
    <label for="{{ $name }}" class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5 lg:text-right">{{ $label }}</label>
    <div class="mt-2 sm:col-span-2 sm:mt-0">
        @if($disabled ?? false)
            <div class="flex rounded-md shadow-sm border border-solid border-gray-300 ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                <input type="text" name="{{ $name }}" id="input_{{ $name }}" autocomplete="{{ $name }}" value="{{ old($name) ?? $value ?? '' }}" disabled {{ $pattern ?? false ? 'pattern='.$pattern : '' }} class="bg-gray-50 text-gray-500 rounded-md block flex-1 border-0 py-1.5 px-2 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="{{ $example ?? ''}}">
            </div>
        @else
            <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                <input type="text" name="{{ $name }}" id="input_{{ $name }}" autocomplete="{{ $name }}" value="{{ old($name) ?? $value ?? '' }}" {{ $pattern ?? false ? 'pattern='.$pattern : '' }} class="bg-transparent text-gray-900 block flex-1 border-0 py-1.5 px-2 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="{{ $example ?? ''}}">
            </div>
        @endif
        @if($explain ?? false)
            <p class="mt-1 text-xs leading-6 text-gray-500">{{ $explain }}</p>
        @endif
    </div>
    @if($addRandamBtn ?? false)
        <div class="mt-2 sm:col-span-3 sm:mt-0">
            <button id="randam_btn_{{ $name }}" title="{{ $addRandamBtn }}" onClick="return false;" class="inline-flex justify-center text-xs bg-white hover:bg-gray-100 text-gray-900 font-semibold px-3 py-2 border border-gray-300 rounded-lg shadow">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M8 3.5c-.771 0-1.537.022-2.297.066a1.124 1.124 0 0 0-1.058 1.028l-.018.214a.75.75 0 1 1-1.495-.12l.018-.221a2.624 2.624 0 0 1 2.467-2.399 41.628 41.628 0 0 1 4.766 0 2.624 2.624 0 0 1 2.467 2.399c.056.662.097 1.329.122 2l.748-.748a.75.75 0 1 1 1.06 1.06l-2 2.001a.75.75 0 0 1-1.061 0l-2-1.999a.75.75 0 0 1 1.061-1.06l.689.688a39.89 39.89 0 0 0-.114-1.815 1.124 1.124 0 0 0-1.058-1.028A40.138 40.138 0 0 0 8 3.5ZM3.22 7.22a.75.75 0 0 1 1.061 0l2 2a.75.75 0 1 1-1.06 1.06l-.69-.69c.025.61.062 1.214.114 1.816.048.56.496.996 1.058 1.028a40.112 40.112 0 0 0 4.594 0 1.124 1.124 0 0 0 1.058-1.028 39.2 39.2 0 0 0 .018-.219.75.75 0 1 1 1.495.12l-.018.226a2.624 2.624 0 0 1-2.467 2.399 41.648 41.648 0 0 1-4.766 0 2.624 2.624 0 0 1-2.467-2.399 41.395 41.395 0 0 1-.122-2l-.748.748A.75.75 0 1 1 1.22 9.22l2-2Z" clip-rule="evenodd" />
                </svg>
                <span class="ml-1">{{ $addRandamBtn }}</span>
            </button>
        </div>
    @endif
    @if($addCopyBtn ?? false)
        <div class="mt-2 sm:col-span-3 sm:mt-0">
            <button id="copy_btn_{{ $name }}" title="{{ $addCopyBtn }}" onClick="return false;" class="inline-flex justify-center text-xs bg-white hover:bg-gray-100 text-gray-900 font-semibold px-3 py-2 border border-gray-300 rounded-lg shadow">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M11.986 3H12a2 2 0 0 1 2 2v6a2 2 0 0 1-1.5 1.937V7A2.5 2.5 0 0 0 10 4.5H4.063A2 2 0 0 1 6 3h.014A2.25 2.25 0 0 1 8.25 1h1.5a2.25 2.25 0 0 1 2.236 2ZM10.5 4v-.75a.75.75 0 0 0-.75-.75h-1.5a.75.75 0 0 0-.75.75V4h3Z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M3 6a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H3Zm1.75 2.5a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5h-3.5ZM4 11.75a.75.75 0 0 1 .75-.75h3.5a.75.75 0 0 1 0 1.5h-3.5a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                </svg>
                <span class="ml-1">{{ $addCopyBtn }}</span>
            </button>
        </div>
    @endif
</div>

<script>
@if($addRandamBtn ?? false)
function {{ $name }}_get_random_string(length = 10) {
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-";
    return Array.from({ length }, () => chars[Math.floor(Math.random() * chars.length)]).join('');
}
@endif

function {{ $name }}_onload() {
    const input_{{ $name }} = document.getElementById("input_{{ $name }}");

    @if($addRandamBtn ?? false)
    if("{{$addRandamBtn ?? ''}}") {
        const btn = document.getElementById("randam_btn_{{ $name }}");
        console.log(btn);
        btn.addEventListener('click', (e) => {
            input_{{ $name }}.value = {{ $name }}_get_random_string(25);
        });
    }
    @endif

    @if($addCopyBtn ?? false)
    if("{{$addCopyBtn ?? ''}}") {
        const btn = document.getElementById("copy_btn_{{ $name }}");
        console.log(btn);
        btn.addEventListener('click', (e) => {
            navigator.clipboard.writeText(input_{{ $name }}.value);
        });
    }
    @endif
}

{{ $name }}_onload();
</script>