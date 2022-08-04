<!DOCTYPE html>
<html lang="pl" style="background-color: #A0AEC0">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="description" content="{{ $page->description ?? $page->opisWitryny }}">
        <meta name="format-detection" content="telephone=no">

        <meta property="og:site_name" content="{{ $page->nazwaWitryny }}">
        <meta property="og:title" content="{{ $page->title() ?  $page->title() . ' | ' : '' }}{{ $page->nazwaWitryny }}">
        <meta property="og:description" content="{{ $page->description ?? $page->opisWitryny }}">
        <meta property="og:url" content="{{ $page->baseUrl }}{{ $page->getPath() == '/home' ? '' : $page->getPath() }}">
        <meta property="og:image" content="{{ $page->baseUrl }}/assets/img/social-share.png">
        <meta property="og:type" content="website">

        <meta name="twitter:image:alt" content="{{ $page->nazwaWitryny }}">
        <meta name="twitter:description" content="{{ $page->description ?? $page->opisWitryny }}">
        <meta name="twitter:image" content="{{ $page->baseUrl }}/assets/img/social-share.png">
        <meta name="twitter:card" content="summary_large_image">

        <title>{{ $page->nazwaWitryny }}{{ $page->title() ? ' | ' . $page->title() : ' – ' . $page->opisWitryny }}</title>

        <link rel="home" href="{{ $page->baseUrl ?: '/' }}">
        <link rel="icon" href="/favicon.ico?v=2">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @stack('meta')

        <link id="stylesheet_link" rel="stylesheet" href="{{ mix('css/main.css', 'assets/build') }}" data-mainsheeturl="{{ mix('css/main.css', 'assets/build') }}" data-manualmodesheeturl="{{ mix('css/manual_mode.css', 'assets/build') }}">

        <script>
            if (localStorage.theme == 'dark' || localStorage.theme == 'light') {
                el = document.getElementById('stylesheet_link')
                el.href = el.dataset.manualmodesheeturl
            }
            if (localStorage.theme == 'dark') {
                document.documentElement.classList.add('dark')
            }
        </script>
    </head>

    <body style="visibility:hidden" tabindex="0" class="flex flex-col justify-between min-h-screen bg-gray-200 dark:bg-gray-900 text-gray-800 dark:text-gray-400 leading-normal font-sans">
        <pre>
        {{ print_r($_SERVER, true) }}
        </pre>
    </body>
</html>
