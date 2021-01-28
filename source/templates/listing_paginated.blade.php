@extends('templates.master')

@section('body')
<main class="container max-w-6xl mx-auto px-6 py-10 md:py-12">
    <div class="text-center">
        <h1 class="text-pink-600 dark:text-purple-300 mb-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline mr-3 h-16 align-middle -mt-8 -mb-6">
                <path d="M15.46,5.14,14.05,3.75l1.93-2,1.41,1.4ZM11,.09H9V2.86h2ZM3.66,7.68H.91v2H3.66Zm15.43,0H16.34v2h2.75ZM6,3.75,4,1.79,2.61,3.19,4.54,5.14Zm6.71,12.72h0A2.53,2.53,0,0,1,10.13,19H9.87a2.53,2.53,0,0,1-2.53-2.53h5.32ZM10,3.89A5.24,5.24,0,0,0,4.62,9,5.34,5.34,0,0,0,6.41,12.9a3.11,3.11,0,0,1,1.2,2.39v.55h4.78v-.55a3.11,3.11,0,0,1,1.2-2.39A5.34,5.34,0,0,0,15.38,9,5.24,5.24,0,0,0,10,3.89Zm2,7a5.85,5.85,0,0,0-1.75,2.37H9.77A5.85,5.85,0,0,0,8,10.92,2.8,2.8,0,0,1,7.17,9,2.7,2.7,0,0,1,10,6.44,2.7,2.7,0,0,1,12.83,9,2.8,2.8,0,0,1,12,10.92Z" />
            </svg>
            <span class="align-middle">Poradniki</span>
        </h1>
        <p class="text-lg text-medium text-gray-600 dark:text-gray-400">
            {{ $page->opisSekcjiPoradniki }}
        </p>
    </div>
    <ul class="list-none pl-0 py-2">
        @foreach($pagination->items as $poradnik)
            <li class="flex flex-1 my-4">
                <a class="excerpt-card flex flex-grow border-b-0 bg-gray-300 hover:bg-gray-350 dark:bg-gray-800 dark:hover:bg-blue-900 shadow rounded-lg break-words px-4 py-6" href="{{ $poradnik->getUrl() }}">
                    <article class="flex flex-grow flex-col">
                        <h2 class="font-semibold leading-tight text-2xl mb-0">
                            {!! $poradnik->title() !!}
                        </h2>
                        <div class="mt-2 text-indigo-700 dark:text-indigo-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 inline-block align-middle mb-1 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="align-middle">{{ Jenssegers\Date\Date::create($poradnik->data)->format('j M Y') }}</span>
                        </div>
                        <p class="mb-0 text-gray-700 font-normal text-sm dark:text-gray-300">
                            {!! $poradnik->longerExcerpt() !!}
                        </p>
                    </article>
                </a>
            </li>
        @endforeach
    </ul>
    
    <div class="text-center">
        <nav class="max-w-full relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Paginacja">
            @if ($previous = $pagination->previous)
                <a href="{{ $page->baseUrl }}{{ $previous }}" class="bg-gray-300 dark:bg-gray-800 relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-400 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-350 dark:hover:bg-indigo-800 hover:text-indigo-800 dark:hover:text-white px-3 py-2 text-sm font-medium border-0">
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
                            <a href="{{ $page->baseUrl }}{{ $path }}" class="md:inline-flex bg-gray-300 dark:bg-gray-800 relative items-center px-4 py-2 border border-gray-400 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-350 dark:hover:bg-indigo-800 hover:text-indigo-800 dark:hover:text-white px-3 py-2 text-sm font-medium border-0">
                                {{ $pageNumber }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
            @if ($next = $pagination->next)
                <a href="{{ $page->baseUrl }}{{ $next }}" class="bg-gray-300 dark:bg-gray-800 inline-flex items-center px-2 py-2 rounded-r-md border border-gray-400 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-350 dark:hover:bg-indigo-800 hover:text-indigo-800 dark:hover:text-white px-3 py-2 text-sm font-medium border-0">
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
