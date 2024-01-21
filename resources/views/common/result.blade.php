<x-layouts.basepage title="結果" current="結果">
    <div class="rounded-md p-4 mb-5">
        @if ($successTitle ?? false)
            <x-messages.success title="{{ $successTitle }}" message="{{ $successMessage ?? '' }}"/>
        @endif
        @if ($warnTitle ?? false)
            <x-messages.warn title="{{ $warnTitle }}" message="{{ $warnMessage ?? '' }}"/>
        @endif
        @if ($errorTitle ?? false)
            <x-messages.error title="{{ $errorTitle }}" message="{{ $errorMessage ?? '' }}"/>
        @endif
    </div>
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            @if ($successTitle ?? false)
                <h1 class="text-base font-semibold leading-6 text-gray-900">{{ $successMessage }}</h1>
            @endif
            @if ($warnTitle ?? false)
                <h1 class="text-base font-semibold leading-6 text-gray-900">{{ $warnMessage }}</h1>
            @endif
            @if ($errorTitle ?? false)
                <h1 class="text-base font-semibold leading-6 text-gray-900">{{ $errorMessage }}</h1>
            @endif
        </div>
    </div>
    <div class="mt-5 text-sm">
        <div hidden>{{ $no = 0 }}</div>
        @foreach(($info ?? []) as $row)
            <li>[{{ ++$no }}] {{ $row }}</li>
        @endforeach
    </div>
</x-layouts.basepage>