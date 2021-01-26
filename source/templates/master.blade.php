<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="description" content="{{ $page->description ?? $page->opisWitryny }}">

        <meta property="og:site_name" content="{{ $page->nazwaWitryny }}"/>
        <meta property="og:title" content="{{ $page->title() ?  $page->title() . ' | ' : '' }}{{ $page->nazwaWitryny }}"/>
        <meta property="og:description" content="{{ $page->description ?? $page->opisWitryny }}"/>
        <meta property="og:url" content="{{ $page->getUrl() }}"/>
        <meta property="og:image" content="/dist/img/logo.png"/>
        <meta property="og:type" content="website"/>

        <meta name="twitter:image:alt" content="{{ $page->nazwaWitryny }}">
        <meta name="twitter:card" content="summary_large_image">

        @if ($page->docsearchApiKey && $page->docsearchIndexName)
            <meta name="generator" content="tighten_jigsaw_doc">
        @endif

        <title>{{ $page->nazwaWitryny }}{{ $page->title() ? ' | ' . $page->title() : ' – ' . $page->opisWitryny }}</title>

        <link rel="home" href="{{ $page->baseUrl ?: '/' }}">
        <link rel="icon" href="/favicon.ico">

        @stack('meta')

        <link rel="stylesheet" href="{{ mix('css/main.css', 'dist/build') }}">

        @if ($page->docsearchApiKey && $page->docsearchIndexName)
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/docsearch.js@2/dist/cdn/docsearch.min.css" />
        @endif
    </head>

    <body class="flex flex-col justify-between min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-400 leading-normal font-sans">
        <header class="shadow bg-white mb-8 dark:bg-gray-800 z-10" role="banner">
            @include('templates.nav.menu', ['items' => $page->mainNav])
        </header>

        <div class="w-full flex-auto">
            @yield('body')
        </div>
        
        @stack('scripts')

        <footer class="bg-white shadow dark:bg-gray-900 text-center text-sm mt-12 py-4" role="contentinfo">
            @include('_ogolne.stopka')
        </footer>
    </body>
</html>
