@extends('templates.master')

@section('body')
<div class="container max-w-6xl mx-auto px-6 py-4">
    <div class="float-right text-indigo-700 dark:text-indigo-300">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 inline-block align-middle mb-1 mr-1">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span class="align-middle">{{ Jenssegers\Date\Date::create($page->data)->format('j M Y') }}</span>
    </div>
    <div class="text-indigo-700 text-xl dark:text-indigo-300">
        <nav>
            <a href="/poradniki" class="border-b-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline-block mb-2 h-6">
                    <path d="M15.46,5.14,14.05,3.75l1.93-2,1.41,1.4ZM11,.09H9V2.86h2ZM3.66,7.68H.91v2H3.66Zm15.43,0H16.34v2h2.75ZM6,3.75,4,1.79,2.61,3.19,4.54,5.14Zm6.71,12.72h0A2.53,2.53,0,0,1,10.13,19H9.87a2.53,2.53,0,0,1-2.53-2.53h5.32ZM10,3.89A5.24,5.24,0,0,0,4.62,9,5.34,5.34,0,0,0,6.41,12.9a3.11,3.11,0,0,1,1.2,2.39v.55h4.78v-.55a3.11,3.11,0,0,1,1.2-2.39A5.34,5.34,0,0,0,15.38,9,5.24,5.24,0,0,0,10,3.89Zm2,7a5.85,5.85,0,0,0-1.75,2.37H9.77A5.85,5.85,0,0,0,8,10.92,2.8,2.8,0,0,1,7.17,9,2.7,2.7,0,0,1,10,6.44,2.7,2.7,0,0,1,12.83,9,2.8,2.8,0,0,1,12,10.92Z" />
                </svg>
                Poradniki
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline-block transform -rotate-90 -scale-y-100 mt-1 h-5">
                <path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
        </nav>
    </div>
    <main class="flex flex-col lg:flex-row">
        <article class="long-article DocSearch-content w-full lg:w-3/5 break-words pb-16">
            @yield('content')
        </article>
    </main>
</div>
@endsection
