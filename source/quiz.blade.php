@extends('__source.layouts.master', ['force_title' => 'Stop terapii konwersyjnej'])

@section('body')
<main class="container max-w-6xl mx-auto px-6 py-10 md:py-12">
    <div class="text-center">
        <h1 class="text-indigo-600 dark:text-purple-300 mb-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="inline mr-2 sm:mr-3 h-12 sm:h-16 text-blue-400 align-middle -mt-8 -mb-6">
                <path fill="currentColor" d="M13 14H11V9H13M13 18H11V16H13M1 21H23L12 2L1 21Z" />
            </svg>
            <span class="align-middle">Stop terapii konwersyjnej</span>
        </h1>
        <p class="text-l text-medium font-semibold font-heading tracking-wider">
            Polskie Towarzystwo Psychoterapii Psychodynamicznej zaprosiło do Polski Evansów, dwójkę psychoanalityków mających diagnozować osobom trans psychozy i chęć odbywania stosunku seksualnego z własną matką. Ale wielu nie zgadza się na tak okropne słowa i gorąco zaprzecza, że Evansowie mają coś wspólnego z transfobią czy terapią konwersyjną - w koncu chcą tylko rozmawiać!
        </p>
        <p class="text-l text-medium font-semibold font-heading tracking-wider">
        W geście protestu przeciwko tak przykrym ocenom państwa Evansów przygotowaliśmy dla Was quiz, w którym będziecie musieli przyporządkować, który cytat pochodzi z książki państwa Evans, a który z twórczości Josepha Nicolosiego, jednego ze słynniejszych terapeutów konwersyjnych orientacji seksualnej (i autora książki "Prewencja homoseksualizmu. Poradnik dla rodziców).
        </p>
        <p class="text-l text-medium font-semibold font-heading tracking-wider mb-0">
            By nie było tak łatwo usunęliśmy z nich słowa bezpośrednio związane z transpłciowością/homoseksualnością</p>
        <h2 class="mt-4 mb-4 text-indigo-600 dark:text-purple-300">
            Miłej zabawy!
        </h2>
        <div class="flex flex-row justify-center border-b-0 bg-gray-300 dark:bg-gray-800 rounded-lg" id="app"></div>
        <h2 class="text-indigo-600 dark:text-purple-300">
            Chcesz dowiedzieć się więcej Evansach i terapii konwersyjnej? <a href="/publikacje/terapia-konwersyjna-raport">Przeczytaj nasz raport!</a>
        </h2>
        <hr>
        <aside class="alert text-center">
            <h2>To co robimy jest dla Ciebie ważne?</h2>
            <p>Ten projekt istnieje dzięki zaangażowaniu takich osób jak Ty. Nawet drobne wsparcie od&nbsp;każdej osoby, która&nbsp;korzysta z&nbsp;naszej pracy, daje nam możliwość dalszego rozwoju i&nbsp;wspierania naszej społeczności.</p>
            <p><a href="/wsparcie" class="przycisk mb-1">Wesprzyj nas!</a></p>
        </aside>
    </div>
</main>
@endsection

@push('scripts')
<script defer="defer" src="{{ mix('js/quiz-bundle.js', 'assets/build') }}"></script>
@endpush