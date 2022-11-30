@extends('__source.layouts.master', ['lang' => $lang ?? NULL])

@section('body')
<div class="container max-w-6xl mx-auto px-6 py-4">
    <div class="text-pink-600 text-xl dark:text-indigo-300">
        <nav>
            <a data-i18n-attrs="text" data-i18n-text="pages.publications" href="./" class="border-b-0 font-heading font-bold tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline-block mb-1 h-6">
                    <path d="M14.62,2.74A5.64,5.64,0,0,0,10,5.14a5.61,5.61,0,0,0-8.57-.75V17.52A5.64,5.64,0,0,1,10,18.3a5.64,5.64,0,0,1,8.57-.78V4.39A5.61,5.61,0,0,0,14.62,2.74ZM4.44,13v-7A2.32,2.32,0,0,1,5,5.59,3.9,3.9,0,0,1,9,6.76v6.93a8.68,8.68,0,0,0-3.61-.79C5.07,12.9,4.75,12.92,4.44,13Zm11.14,0c-.31,0-.63,0-1,0a8.62,8.62,0,0,0-3.62.8V6.78a3.89,3.89,0,0,1,4-1.19,2.32,2.32,0,0,1,.6.34Z" />
                </svg>
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline-block transform -rotate-90 -scale-y-100 mt-1 h-5">
                <path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
        </nav>
    </div>
    <main class="flex flex-col lg:flex-row clear-both">
        <article class="long-article title-wide DocSearch-content w-full lg:w-3/5 break-words pb-16">
            @yield('content')
        </article>
    </main>
</div>
@endsection

@push('scripts')
    <script src="{{ mix('js/section-highlight.js', 'assets/build') }}"></script>
    <script src="{{ mix('js/search/mark.js', 'assets/build') }}"></script>
@endpush