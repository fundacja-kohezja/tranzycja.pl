@extends('templates.master')

@section('body')
<main class="container max-w-6xl mx-auto px-6 py-10 md:py-12">
    <div class="text-center">
        <h1 class="text-purple-700 dark:text-purple-300 mb-0">
            <span class="align-middle">Tranzycja krok po kroku</span>
        </h1>
        <p class="text-lg text-medium text-gray-600 dark:text-gray-400">
            {{ $page->opisSekcjiKrokPoKroku }}
        </p>
    </div>
    <ul class="list-none pl-0 py-2 numbered-container">
        @foreach($krok_po_kroku as $poradnik)
            <li class="flex flex-1 my-4">
                <a class="excerpt-card numbered flex flex-grow border-b-0 bg-gray-200 hover:bg-indigo-100 dark:bg-gray-800 dark:hover:bg-blue-900 shadow rounded-lg break-words px-4 py-6" href="{{ $poradnik->getUrl() }}">
                    <article class="flex flex-grow flex-col">
                        <h2 class="font-semibold leading-tight text-2xl mb-0">
                            {!! $poradnik->title() !!}
                        </h2>
                        <p class="mb-0 text-gray-700 font-normal text-sm dark:text-gray-300">
                            {!! $poradnik->excerpt() !!}
                        </p>
                    </article>
                </a>
            </li>
        @endforeach
    </ul>
</main>
@endsection
