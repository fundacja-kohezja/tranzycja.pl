@extends('__source.layouts.master')

@section('body')
<main class="container max-w-6xl mx-auto px-6 py-10 md:py-12">
    <div class="text-center">
        <h1 class="text-indigo-600 dark:text-purple-300 mb-0">
            <span class="align-middle">Tranzycja krok po kroku</span>
        </h1>
        @if($page->opisSekcjiKrokPoKroku)
            <p class="text-lg text-medium font-semibold font-heading tracking-wider text-gray-600 dark:text-gray-400">
                {{ $page->opisSekcjiKrokPoKroku }}
            </p>
        @endif
    </div>
    <ul class="list-none pl-0 py-2">
        @foreach($krok_po_kroku as $poradnik)
            <li class="flex flex-1 my-4">
                <a class="flex flex-grow border-b-0 bg-gray-300 hover:bg-gray-350 dark:bg-gray-800 dark:hover:bg-blue-900 rounded-lg break-words px-4 py-6" href="{{ $poradnik->getPath() }}">
                    <article class="flex flex-grow flex-col">
                        <h2 class="font-extrabold leading-tight text-2xl mb-0">
                            {!! $poradnik->title() !!}
                        </h2>
                        <p class="mb-0 text-gray-700 font-normal text-sm dark:text-gray-300">
                            {!! $poradnik->excerpt(60) !!}
                        </p>
                    </article>
                </a>
            </li>
        @endforeach
    </ul>
</main>
@endsection
