@extends('templates.master')

@section('body')
<div class="container max-w-6xl mx-auto px-6 py-4">
    <div class="float-right text-indigo-700 dark:text-indigo-300">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 inline-block align-middle mb-1 mr-1">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span class="align-middle">{{ Jenssegers\Date\Date::create($page->data)->format('j F Y') }}</span>
    </div>
    <div class="text-indigo-700 text-xl">
        <nav>
            <a href="/aktualnosci" class="border-b-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline-block mb-2 h-6">
                    <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd" />
                    <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z" />
                </svg>
                Aktualności
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline-block transform -rotate-90 -scale-y-100 mt-1 h-5">
                <path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
        </nav>
    </div>
    <main class="flex flex-col lg:flex-row">
        <article class="DocSearch-content w-full break-words pb-16">
            @yield('content')
        </article>
    </main>
</div>
@endsection
