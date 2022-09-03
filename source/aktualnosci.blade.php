---
pagination:
    collection: aktualnosci
    perPage: 6
---
@extends('__source.layouts.master')

@section('body')
<main class="container max-w-6xl mx-auto px-6 py-10 md:py-12">
    <div class="text-center">
        <h1 class="text-indigo-600 dark:text-purple-300 mb-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline mr-2 sm:mr-3 h-12 sm:h-16 text-blue-400 align-middle -mt-8 -mb-6">
                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd" />
                <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z" />
            </svg>
            <span class="align-middle">Aktualności</span>
        </h1>
        @if($page->opisSekcjiAktualnosci)
            <p class="text-lg text-medium font-semibold font-heading tracking-wider text-gray-600 dark:text-gray-400">
                {{ $page->opisSekcjiAktualnosci }}
            </p>
        @endif
    </div>
    <ul class="list-none pl-0 py-2">
        @foreach($pagination->items as $entry)
            <li class="flex border-b border-gray-300 dark:border-gray-700 pb-12 my-16">
                <article class="news-entry break-words">
                    <header class="items-center flex-col flex float-right text-sm mt-2 ml-4 text-indigo-700 dark:text-indigo-300 sm:flex-row">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 inline-block align-middle mb-1 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="align-middle">{{ Jenssegers\Date\Date::create($entry->opublikowano)->format('j M Y') }}</span>
                    </header>
                    {!! $entry->beginning(1600) !!}
                    <a href="{{ $entry->getPath() }}">Czytaj całość →</a>
                </article>
            </li>
        @endforeach
    </ul>
    
    <div class="text-center">
        <nav class="max-w-full relative z-0 inline-flex rounded-md -space-x-px" aria-label="Paginacja">
            @if ($previous = $pagination->previous)
                <a href="{{ $previous }}" class="bg-gray-200 dark:bg-gray-800 relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-400 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-indigo-800 hover:text-indigo-800 dark:hover:text-white px-3 py-2 text-sm font-bold border-0">
                    <span class="sr-only">Nowsze</span>
                    <!-- Heroicon name: chevron-left -->
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center px-2 py-2 border border-gray-300 dark:border-gray-700 rounded-l-md text-gray-400 dark:text-gray-600">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
            <div class="overflow-auto border-l border-r border-gray-400 dark:border-gray-600">
                <div class="flex">
                    @foreach ($pagination->pages as $pageNumber => $path)
                        @if($pagination->currentPage == $pageNumber)
                            <span class="md:inline-flex relative items-center px-4 py-2 border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-black text-sm font-extrabold text-pink-600 dark:text-purple-400 hover:bg-gray-50">
                                {{ $pageNumber }}
                            </span>
                        @else
                            <a href="{{ $path }}" class="md:inline-flex bg-gray-200 dark:bg-gray-800 relative items-center px-4 py-2 border border-gray-400 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-indigo-800 hover:text-indigo-800 dark:hover:text-white px-3 py-2 text-sm font-bold border-0">
                                {{ $pageNumber }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
            @if ($next = $pagination->next)
                <a href="{{ $next }}" class="bg-gray-200 dark:bg-gray-800 inline-flex items-center px-2 py-2 rounded-r-md border border-gray-400 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-indigo-800 hover:text-indigo-800 dark:hover:text-white px-3 py-2 texbold border-0">
                    <span class="sr-only">Starsze</span>
                    <!-- Heroicon name: chevron-right -->
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center px-2 py-2 border border-gray-300 dark:border-gray-700 rounded-r-md text-gray-400 dark:text-gray-600">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </nav>
    </div>
</main>
@endsection
