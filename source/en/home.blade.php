---
permalink: en/index.html
---
@extends('__source.layouts.master_en', ['container_class' => 'overflow-hidden no-slide'])

@section('body')
<div id="autocomplete-search-container" class="max-w-6xl mx-auto px-6 py-0 md:py-4"></div>
<main class="container max-w-6xl mx-auto px-6 py-10 md:py-12">
    <section class="flex flex-col-reverse mb-10 lg:flex-row lg:mb-24">
        <div class="introdution">
            @include('_ogolne.en.str_glowna_wstep')
        </div>
        <div class="-mb-20 lg:-mt-10 lg:mb-0 lg:-mr-32 xl:-mr-48 lg:-ml-8 ml-5 flex items-end justify-end flex-shrink-0 gradient-overlay">
            <picture>
                <source media="(prefers-color-scheme: dark)" srcset="/assets/img/ilustracja-trans-ciemne.svg">
                <img src="/assets/img/ilustracja-trans-jasne.svg" alt="" class="auto-dark h-auto ml-auto">
            </picture>
            <img src="/assets/img/ilustracja-trans-jasne.svg" alt="" class="manual-light h-auto ml-auto">
            <img src="/assets/img/ilustracja-trans-ciemne.svg" alt="" class="manual-dark h-auto ml-auto">
        </div>
    </section>
    <section class="mb-24">
        <div>
            <h1 id="about-us" class="inline-block mr-4 text-indigo-600 dark:text-purple-300 mb-0 text-3xl sm:text-4xl tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline mr-2 h-8 sm:h-10 align-middle -mt-8 -mb-6">
                    <path d="M10,1.81A8.19,8.19,0,1,1,1.81,10,8.21,8.21,0,0,1,10,1.81m1.2,9.24A3.32,3.32,0,1,0,7.26,6.11l-.65,1,2,1.29.65-1A1,1,0,0,1,10,7a1,1,0,0,1,0,2H8.86V12.1H11.2Zm0,2.25H8.86v2.33H11.2Z" />
                </svg>
                <span>About us</span>
            </h1>
            @include('_ogolne.en.o_nas')
        </div>
    </section>
    <section class="mb-24">
        <div>
            <h1 class="inline-block mr-4 text-indigo-600 dark:text-purple-300 mb-0 text-3xl sm:text-4xl tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline mr-2 h-9 sm:h-10 align-middle -mt-8 -mb-8">
                    <path d="M14.62,2.74A5.64,5.64,0,0,0,10,5.14a5.61,5.61,0,0,0-8.57-.75V17.52A5.64,5.64,0,0,1,10,18.3a5.64,5.64,0,0,1,8.57-.78V4.39A5.61,5.61,0,0,0,14.62,2.74ZM4.44,13v-7A2.32,2.32,0,0,1,5,5.59,3.9,3.9,0,0,1,9,6.76v6.93a8.68,8.68,0,0,0-3.61-.79C5.07,12.9,4.75,12.92,4.44,13Zm11.14,0c-.31,0-.63,0-1,0a8.62,8.62,0,0,0-3.62.8V6.78a3.89,3.89,0,0,1,4-1.19,2.32,2.32,0,0,1,.6.34Z" />
                </svg>
                <span class="align-middle">Publications</span>
            </h1>
            <p class="inline-block">
                <a href="/en/publications" class="border-b-0 font-heading font-bold tracking-wider">
                    More publications
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 inline-block align-middle mb-1 mr-1">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </p>
        </div>
        <ul class="flex-1 flex list-none -mr-6 md:-mr-2 -ml-6 py-2 px-4 overflow-auto">
            @foreach($publications as $publication)
                <li class="slider-item flex mx-2">
                    <a class="excerpt-card publications flex flex-grow border-b-0 bg-gray-300 hover:bg-gray-350 dark:bg-gray-800 dark:hover:bg-blue-900 rounded-lg break-words px-4 py-6" href="{{ $publication->getPath() }}">
                        <article class="flex flex-grow flex-col">
                            <h2 class="font-extrabold leading-tight text-gray-700 dark:text-gray-300 text-xl mb-0">
                                {!! $publication->title() !!}
                            </h2>
                            <p class="mb-0 text-gray-700 font-normal text-sm dark:text-gray-300">
                                {!! $publication->excerpt(30) !!}
                            </p>
                        </article>
                    </a>
                </li>
                @break($loop->iteration === 3)
            @endforeach
        </ul>
    </section>
</main>
@endsection