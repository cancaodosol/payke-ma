<!doctype html>
<html lang="ja" class="h-full bg-white">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0,
            maximum-scale=1.0, minimum-slace=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clifford: '#da373d',
                    }
                }
            }
            }
        </script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <title>{{ $title ?? false ? 'Payke MA - '.$title : 'Payke MA' }}</title>
    </head>
    <body class="h-full">
    <div class="grid min-h-full grid-cols-1 grid-rows-[1fr,auto,1fr] bg-white lg:grid-cols-[max(50%,36rem),1fr]">
        <main class="mx-auto w-full max-w-7xl px-6 py-24 sm:py-32 lg:col-span-2 lg:col-start-1 lg:row-start-2 lg:px-8">
            <div class="max-w-lg">
            <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl">使用停止</h1>
            <p class="mt-6 text-base leading-7 text-gray-600">現在、こちらのアプリは使用できません。</p>
            <div class="mt-10">
                <a href="{{ route('home') }}" class="text-sm font-semibold leading-7 text-indigo-600"><span aria-hidden="true">&larr;</span> ホームへ</a>
            </div>
            </div>
        </main>
        <div class="hidden lg:relative lg:col-start-2 lg:row-start-1 lg:row-end-4 lg:block">
            <img src="{{ asset('/images/生命の樹.jpg') }}" alt="" class="absolute inset-0 h-full w-full object-cover">
        </div>
        </div>
    </body>
</html>