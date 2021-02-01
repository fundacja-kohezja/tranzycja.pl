---
permalink: 404.html
---
@extends('__source.layouts.master')

@section('body')
<div class="flex flex-col items-center mt-16 max-w-6xl text-gray-700 dark:text-gray-300 mx-auto overflow-hidden text-center">
    <h1 class="text-6xl font-extrabold leading-none mb-2">404</h1>

    <h2 class="text-3xl">Nie znaleziono strony</h2>

    <hr class="block w-full max-w-lg mx-auto my-8 border">

    <p class="text-lg px-4 sm:px-8 mb-0">
        Strona o podanym adresie nie istnieje.<br>Zapraszamy na stronę główną – z pewnością znajdziesz tam coś ciekawego!
    </p>
    <p class="text-lg px-4 sm:px-8">
        <a href="/" class="przycisk">Strona główna</a>
    </p>
</div>
@endsection
