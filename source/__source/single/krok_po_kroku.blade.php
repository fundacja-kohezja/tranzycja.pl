@extends('__source.layouts.master', ['lang' => $lang ?? NULL])

@section('body')
<div class="container max-w-6xl mx-auto px-6 py-4">
    <div class="text-pink-600 dark:text-indigo-300 text-xl hidden lg:block">
        <nav>
            <a data-i18n-attrs="text" data-i18n-text="pages.stepByStep" href="./" class="border-b-0 font-heading font-bold tracking-wider"></a>
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

@push('scripts')
    <script src="{{ mix('js/section-highlight.js', 'assets/build') }}"></script>
    <script src="{{ mix('js/search/mark.js', 'assets/build') }}"></script>
@endpush