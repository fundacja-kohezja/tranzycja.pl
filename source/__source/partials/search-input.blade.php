<button
    title="Rozpocznij wyszukiwanie"
    type="button"
    class="bg-gray-800 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
    onclick="searchInput.toggle()"
>
    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
    </svg>
</button>

<div id="js-search-input" class="docsearch-input__wrapper hidden md:block">
    <label data-i18n-attrs="text" data-i18n-text="search.search" for="search" class="hidden"></label>

    <input
        id="docsearch-input"
        class="docsearch-input relative block h-10 transition-fast w-full lg:w-1/2 xl:w-1/3 bg-gray-100 outline-none rounded-full text-gray-700 dark:text-gray-300 border border-gray-500 focus:border-indigo-400 ml-auto px-4 pb-0"
        name="docsearch"
        type="text"
        data-i18n-attrs="placeholder" 
        data-i18n-placeholder="search.search"
    >

    <button
        class="md:hidden absolute pin-t pin-r h-full font-light text-3xl text-indigo-500 hover:text-indigo-600 focus:outline-none -mt-px pr-7"
        onclick="searchInput.toggle()"
    >&times;</button>
</div>

@push('scripts')
    @if ($page->docsearchApiKey && $page->docsearchIndexName)
        <script type="text/javascript">
            docsearch({
                apiKey: '{{ $page->docsearchApiKey }}',
                indexName: '{{ $page->docsearchIndexName }}',
                inputSelector: '#docsearch-input',
                debug: false // Set debug to true if you want to inspect the dropdown
            });

            const searchInput = {
                toggle() {
                    const menu = document.getElementById('js-search-input');
                    menu.classList.toggle('hidden');
                    menu.classList.toggle('md:block');
                    document.getElementById('docsearch-input').focus();
                },
            }
        </script>
    @endif
@endpush
