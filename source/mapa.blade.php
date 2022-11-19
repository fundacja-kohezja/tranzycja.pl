@extends('__source.layouts.master', ['force_title' => 'Mapa specjalistówj'])

@section('body')
<main class="container max-w-6xl mx-auto px-6 py-10 md:py-12">
    <div class="text-center">
        <h1 class="text-indigo-600 dark:text-purple-300 mb-0">
            <span class="align-middle">Mapa specjalistów</span>
        </h1>
        <p class="text-l text-medium font-semibold font-heading tracking-wider">
            Pod tym adresem niedługo znajdzie się mapa specjalistów. Prosimy o cierpliwość, prace trwają!
        </p>
        <p class="text-l text-medium font-semibold font-heading tracking-wider">
            Do tego czasu, jeśli szukasz specjalisty, możesz <a target="_blank" href="https://www.google.com/maps/d/u/0/viewer?mid=1H6uOjAMXq1hfVfF-bHXgd6h8JhGpWh3k&ll=52.174992137211575%2C16.02873109751078&z=5">skorzystać z tej mapki</a>
        </p>
    </div>
</main>
@endsection