---
permalink: en/404.html
---
@extends('__source.layouts.master_en')

@section('body')
<div class="flex flex-col items-center mt-16 max-w-6xl text-gray-700 dark:text-gray-300 mx-auto overflow-hidden text-center">
    <h1 class="text-6xl font-extrabold leading-none mb-2">404</h1>

    <h2 data-i18n-attrs="text" data-i18n-text="pages.404.title" class="text-3xl"></h2>

    <hr class="block w-full max-w-lg mx-auto my-8 border">

    <p data-i18n-attrs="text" data-i18n-text="pages.404.paragraph" class="text-lg px-4 sm:px-8 mb-0"></p>
    <p class="text-lg px-4 sm:px-8">
        <a data-i18n-attrs="text" data-i18n-text="pages.404.home" href="/en" class="przycisk"></a>
    </p>
</div>
@endsection
