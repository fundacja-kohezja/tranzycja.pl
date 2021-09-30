---
permalink: index.html
---
@extends('__source.layouts.master', ['container_class' => 'overflow-hidden'])

@section('body')
<main class="container max-w-6xl mx-auto px-6 py-10 md:py-12">
    <section class="flex flex-col-reverse mb-10 lg:flex-row lg:mb-24">
        <div class="introdution">
            @include('_ogolne.str_glowna_wstep')
        </div>
        <div class="-mt-24 -mb-20 lg:-mt-10 lg:mb-0 lg:-mr-32 xl:-mr-48 lg:-ml-8 ml-5 flex items-end justify-end flex-shrink-0 gradient-overlay">
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
            <h1 class="inline-block mr-4 text-indigo-600 dark:text-purple-300 mb-0 text-3xl sm:text-4xl uppercase tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline mr-2 h-9 sm:h-10 align-middle -mt-8 -mb-8">
                    <path d="M.06,4.71,0,0,4.62.66,4.4,2.18,2.81,2l2,2.83a5.53,5.53,0,0,1,7.41,4.41h.87V7.78h1.54V9.19h2.1l-1.47-1,.9-1.25L20,9.64,16.72,13l-1.09-1.08,1.12-1.14h-2.1v1.41H13.11V10.74h-.87A5.55,5.55,0,0,1,6.77,15.5,5.77,5.77,0,0,1,4.49,15l-.8,1.31,1.21.74-.8,1.3-1.21-.74L1.43,20,.12,19.2l1.45-2.38-1.2-.74.8-1.31,1.2.74.82-1.32A5.53,5.53,0,0,1,2.55,6.4a5.46,5.46,0,0,1,.93-.87L1.58,2.87V4.68ZM3.77,10a3,3,0,1,0,3-3A3,3,0,0,0,3.77,10Z" />
                </svg>
                <span class="align-middle">Krok po kroku</span>
            </h1>
            <p class="inline-block">
                <a href="/krok-po-kroku" class="border-b-0 font-heading font-bold tracking-wider">
                    Zobacz wszystkie
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 inline-block align-middle mb-1 mr-1">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </p>
        </div>
        <ul class="flex-1 flex list-none -mr-6 md:-mr-2 -ml-6 py-2 px-4 overflow-auto numbered-container">
            @foreach($krok_po_kroku as $poradnik)
                    <li class="slider-item-wider flex mx-2">
                        <a class="excerpt-card numbered flex flex-grow border-b-0 bg-gray-300 hover:bg-gray-350 dark:bg-gray-800 dark:hover:bg-blue-900 rounded-lg break-words px-4 py-6" href="{{ $poradnik->getPath() }}">
                            <article class="flex flex-grow flex-col">
                                <h2 class="font-extrabold leading-tight text-gray-700 text-2xl mb-0">
                                    {!! $poradnik->title() !!}
                                </h2>
                                <p class="mb-0 text-gray-700 font-normal text-sm dark:text-gray-300">
                                    {!! $poradnik->excerpt() !!}
                                </p>
                            </article>
                        </a>
                    </li>
                @break($loop->iteration === 2)
            @endforeach
        </ul>
    </section>
    <section class="mb-24">
        <div>
            <h1 class="inline-block mr-4 text-indigo-600 dark:text-purple-300 mb-0 text-3xl sm:text-4xl uppercase tracking-wider">
{{--            -- Ikona żarówki do poradników --
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline mr-2 h-9 sm:h-10 align-middle -mt-8 -mb-8">
                    <path d="M15.46,5.14,14.05,3.75l1.93-2,1.41,1.4ZM11,.09H9V2.86h2ZM3.66,7.68H.91v2H3.66Zm15.43,0H16.34v2h2.75ZM6,3.75,4,1.79,2.61,3.19,4.54,5.14Zm6.71,12.72h0A2.53,2.53,0,0,1,10.13,19H9.87a2.53,2.53,0,0,1-2.53-2.53h5.32ZM10,3.89A5.24,5.24,0,0,0,4.62,9,5.34,5.34,0,0,0,6.41,12.9a3.11,3.11,0,0,1,1.2,2.39v.55h4.78v-.55a3.11,3.11,0,0,1,1.2-2.39A5.34,5.34,0,0,0,15.38,9,5.24,5.24,0,0,0,10,3.89Zm2,7a5.85,5.85,0,0,0-1.75,2.37H9.77A5.85,5.85,0,0,0,8,10.92,2.8,2.8,0,0,1,7.17,9,2.7,2.7,0,0,1,10,6.44,2.7,2.7,0,0,1,12.83,9,2.8,2.8,0,0,1,12,10.92Z" />
                </svg>
 --}}           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline mr-2 h-9 sm:h-10 align-middle -mt-8 -mb-8">
                    <path d="M14.62,2.74A5.64,5.64,0,0,0,10,5.14a5.61,5.61,0,0,0-8.57-.75V17.52A5.64,5.64,0,0,1,10,18.3a5.64,5.64,0,0,1,8.57-.78V4.39A5.61,5.61,0,0,0,14.62,2.74ZM4.44,13v-7A2.32,2.32,0,0,1,5,5.59,3.9,3.9,0,0,1,9,6.76v6.93a8.68,8.68,0,0,0-3.61-.79C5.07,12.9,4.75,12.92,4.44,13Zm11.14,0c-.31,0-.63,0-1,0a8.62,8.62,0,0,0-3.62.8V6.78a3.89,3.89,0,0,1,4-1.19,2.32,2.32,0,0,1,.6.34Z" />
                </svg>
                <span class="align-middle">Publikacje</span>
            </h1>
            <p class="inline-block">
                <a href="/publikacje" class="border-b-0 font-heading font-bold tracking-wider">
                    Więcej publikacji
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 inline-block align-middle mb-1 mr-1">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </p>
        </div>
        <ul class="flex-1 flex list-none -mr-6 md:-mr-2 -ml-6 py-2 px-4 overflow-auto">
            @foreach($publikacje as $publikacja)
                    <li class="slider-item flex mx-2">
                        <a class="excerpt-card flex flex-grow border-b-0 bg-gray-300 hover:bg-gray-350 dark:bg-gray-800 dark:hover:bg-blue-900 rounded-lg break-words px-4 py-6" href="{{ $publikacja->getPath() }}">
                            <article class="flex flex-grow flex-col">
                                <h2 class="font-extrabold leading-tight text-gray-700 text-2xl mb-0">
                                    {!! $publikacja->title() !!}
                                </h2>
                                <p class="mb-0 text-gray-700 font-normal text-sm dark:text-gray-300">
                                    {!! $publikacja->excerpt() !!}
                                </p>
                            </article>
                        </a>
                    </li>
                @break($loop->iteration === 3)
            @endforeach
            @if(count($publikacje) < 2)
                <li class="slider-item hidden sm:flex flex-1 md:w-auto md:flex align-stretch-shrink mx-2">
                    <div class="placeholder excerpt-card flex-grow flex justify-center items-center text-center">
                        <span>Już niedługo pojawi się tu więcej publikacji!</span>
                    </div>
                </li>
            @endif
        </ul>
    </section>
    <section class="mb-24">
        <div>
            <h1 class="inline mr-4 text-indigo-600 dark:text-purple-300 text-3xl sm:text-4xl uppercase tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline mr-2 h-9 sm:h-10 align-middle -mt-8 -mb-6">
                    <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd" />
                    <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z" />
                </svg>
                Aktualności
            </h1>
            <p class="inline-block">
                <a href="/aktualnosci" class="border-b-0 font-heading font-bold tracking-wider">
                    Więcej aktualności
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 inline-block align-middle mb-1 mr-1">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </p>
        </div>
        <div>
            <article class="news-entry on-homepage break-words bg-gray-100 dark:bg-gray-800 rounded-lg px-4 py-6">
                <header class="items-center flex-col flex float-right text-sm mt-2 ml-4 text-indigo-700 dark:text-indigo-300 sm:flex-row">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 inline-block align-middle mb-1 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="align-middle">{{ Jenssegers\Date\Date::create($aktualnosci->first()->data)->format('j M Y') }}</span>
                </header>
                {!! $aktualnosci->first()->getContent() !!}
            </article>
        </div>
    </section>
    <section>
        <div>
            <h1 id="faq" class="inline mr-4 text-indigo-600 dark:text-purple-300 text-3xl sm:text-4xl uppercase tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline mr-2 h-8 sm:h-10 align-middle -mt-8 -mb-6">
                    <path d="M10,1.81A8.19,8.19,0,1,1,1.81,10,8.21,8.21,0,0,1,10,1.81m1.2,9.24A3.32,3.32,0,1,0,7.26,6.11l-.65,1,2,1.29.65-1A1,1,0,0,1,10,7a1,1,0,0,1,0,2H8.86V12.1H11.2Zm0,2.25H8.86v2.33H11.2Z" />
                </svg>
                @php
                    ob_start();
                @endphp
                @include('_ogolne.faq')
                @php
                    $faq = ob_get_clean();
                    $faq = preg_replace_callback( '|<h1>(.*?)</h1>|iU', function($matches) {
                        echo $matches[1];
                        return '';
                    }, $faq);
                @endphp
            </h1>
        </div>
        <div class="faq-section mt-8">
            {!! $faq !!}
        </div>
        <div class="clear-both"></div>
    </section>
    {{-- <section class="clear-both pt-24">
        <h1 class="inline mr-4 text-indigo-600 dark:text-purple-300 text-3xl sm:text-4xl uppercase tracking-wider">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline mr-2 h-8 sm:h-10 align-middle -mt-8 -mb-8">
                <path d="M6.41,12.35.77,9,3.43,7.63l5.68,3Zm2.91-1-2.9,1.81L4.36,11.94v2.68l5,3.19Zm-.18-8L6.41,1.69.71,5.11,3.44,6.82Zm9.79,1.71-5.7-3.42L10.5,3.4l5.7,3.42Zm-3.22,7-2.07,1.1-3.33-1.85v6.53l5.4-3.24Zm.49-4.43-5.64,3,3.09,1.72,5.64-3Z" />
            </svg>
            Dodatkowe materiały
        </h1>
        <p class="inline-block">
            <a href="/materialy" class="border-b-0 font-heading font-bold tracking-wider">
                Zobacz materiały
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 inline-block align-middle mb-1 mr-1">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </p>
    </section> --}}
</main>
@endsection
