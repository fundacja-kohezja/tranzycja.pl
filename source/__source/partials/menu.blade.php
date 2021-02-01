<nav class="container flex flex-1 flex-col max-w-6xl mx-auto px-4 lg:px-6">
  <div class="max-w-7xl flex flex-1 items-center">
    <div class="relative flex items-center flex-1 justify-between h-24">
      <div class="absolute inset-y-0 left-0 flex items-center lg:hidden">
        {{-- Mobile menu button --}}
        <button onclick="navMenu.toggle()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-expanded="false">
          <span class="sr-only">Open main menu</span>
          <svg id="js-nav-menu-show" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg id="js-nav-menu-hide" class="block hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex-1 flex items-center justify-center lg:items-stretch lg:justify-start">
        <div class="flex-shrink-0 flex items-center">
            <a href="/" title="Strona główna {{ $page->nazwaWitryny }}" class="inline-flex items-center w-auto group border-0">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="h-8 md:h-10 mr-3 text-pink-600 dark:text-purple-400" viewBox="0 0 286.6 286.9">
                    <path fill="currentColor" d="M0.7,67.5L0,0l66.1,9.5L63,31.3L40.2,28l29,40.6c8.6-3.2,18-5,27.7-5c40,0,73.2,29.8,78.5,68.3h12.5v-20.3H210
                        v20.3h30.1L219,116.7l12.9-17.9l54.7,39.5l-46.9,47.6L224,170.4l16.2-16.4H210v20.3h-22.1V154h-12.5c-5.4,38.5-38.5,68.3-78.5,68.3
                        c-11.6,0-22.6-2.5-32.6-7l-11.5,18.8l17.3,10.6l-11.5,18.7l-17.3-10.6l-21,34.1L1.5,275.4l20.9-34.1L5.1,230.7l11.5-18.8l17.3,10.6
                        l11.7-19c-17.1-14.6-28-36.3-28-60.5c0-26.1,12.6-49.2,32.1-63.7L22.5,41.2l0.3,26L0.7,67.5z M39.6,143c0,31.6,25.7,57.3,57.3,57.3
                        s57.3-25.7,57.3-57.3s-25.7-57.3-57.3-57.3S39.6,111.4,39.6,143z"
                    />
                </svg>
                <span class="text-lg md:text-2xl text-indigo-800 dark:text-blue-100 font-semibold group-hover:text-indigo-600 dark:group-hover:text-indigo-300 my-0 pr-4">{{ $page->nazwaWitryny }}</span>
            </a>
        </div>
        <div class="hidden lg:block lg:ml-6">
          <div class="flex space-x-4">
            @foreach ($items as $item)
                <a href="{{ $item->path }}"
                    class="{{ $page->isActive($item->path) ? 'text-pink-700 hover:text-pink-700 dark:text-purple-400 dark:hover:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-purple-800 dark:hover:text-indigo-300' }} px-3 py-2 rounded-md text-sm font-medium border-0"
                >
                    {{ $item->title }}
                </a>
            @endforeach
          </div>
        </div>
      </div>
    
      <div class="absolute inset-y-0 right-0 flex items-center flex-1 justify-end text-right">
          @if ($page->docsearchApiKey && $page->docsearchIndexName)
              @include('__source.partials.search-input')
          @endif
          <div class="relative inline-block text-left hover-trigger">
            <button type="button" class="inline-flex justify-center w-full text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-purple-800 dark:hover:text-indigo-300 px-3 py-2 rounded-md text-sm font-medium border-0" id="options-menu" aria-haspopup="true">
              <span class="sr-only">Motyw</span>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-5 mr-1" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="square" d="M2.53,7.17l1,.43M15.17,2.79l.42-1M2.53,14.83l1-.43M7.94,1.76l.43,1" />
                <path stroke-width="2" d="M19.51,20.52a6.3,6.3,0,0,1,0-8.89,6.19,6.19,0,0,1,1.73-1.22,6.28,6.28,0,1,0,0,11.33A6.19,6.19,0,0,1,19.51,20.52Zm-11.28-6A5,5,0,0,1,15.3,7.46" />
              </svg>
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            <div class="hidden hover-target origin-top-right absolute right-0 w-56 rounded-lg shadow-lg bg-gray-100 dark:bg-gray-800 ring-1 ring-black ring-opacity-5">
              <div class="p-2 flex flex-col" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                <input class="w-0 h-0 invisible" name="theme" id="theme_auto" type="radio" onchange="el = document.getElementById('stylesheet_link'); el.href = el.dataset.mainsheeturl; document.documentElement.classList.remove('dark'); localStorage.removeItem('theme')">
                <label for="theme_auto" class="cursor-pointer radio-label text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 px-3 py-2 rounded-md text-sm font-medium border-0 flex" role="menuitem">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-5 mr-2" stroke="currentColor">
                    <path stroke-width="2" stroke-linecap="square" d="M20.07,3.93l-.79.79M23,11H21.89M13,1V2.11" />
                    <path stroke-width="2" d="M5.67,13A9.44,9.44,0,0,1,5,9.5a9.6,9.6,0,0,1,.71-3.61,9,9,0,1,0,12.4,12.4A9.6,9.6,0,0,1,14.5,19a10,10,0,0,1-1.35-.09" />
                    <path stroke-width="2" stroke-linejoin="bevel" d="M12.14,5.17a5.5,5.5,0,0,1,6.71,6.62M8.08,16.38l3.5-8.76,3.5,8.76M9.34,14h4.48" />
                  </svg>
                  Automatycznie
                </label>
                <input class="w-0 h-0 invisible" name="theme" id="theme_light" type="radio" onchange="el = document.getElementById('stylesheet_link'); el.href = el.dataset.manualmodesheeturl; document.documentElement.classList.remove('dark'); localStorage.theme = 'light'">
                <label for="theme_light" class="cursor-pointer radio-label text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 px-3 py-2 rounded-md text-sm font-medium border-0 flex" role="menuitem">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-5 mr-2" stroke="currentColor">
                    <path stroke-linecap="square" stroke-width="2" d="M5.72,18.28l-.79.79M19.07,4.93l-.79.79m-12.56,0-.79-.79M19.07,19.07l-.79-.79M3.11,12H2m20,0H20.89M12,20.89V22M12,2V3.11M12,7a5,5,0,1,0,5,5A5,5,0,0,0,12,7Z" />
                  </svg>
                  Jasny
                </label>
                <input class="w-0 h-0 invisible" name="theme" id="theme_dark" type="radio" onchange="el = document.getElementById('stylesheet_link'); el.href = el.dataset.manualmodesheeturl; document.documentElement.classList.add('dark'); localStorage.theme = 'dark'">
                <label for="theme_dark" class="cursor-pointer radio-label text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 px-3 py-2 rounded-md text-sm font-medium border-0 flex" role="menuitem">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-5 mr-2" stroke="currentColor">
                    <path stroke-width="2" d="M17.5,15.5a9,9,0,0,1-9-9,8.89,8.89,0,0,1,.52-3A9,9,0,1,0,20.48,15,8.89,8.89,0,0,1,17.5,15.5Z" />
                  </svg>
                  Ciemny
                </label>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>

  <div id="js-nav-menu" class="block hidden lg:hidden">
    <div class="px-2 pt-2 pb-3 space-y-1">
      @foreach ($items as $item)
        {{-- Menu item with URL --}}
        <a href="{{ $item->path }}"
            class="{{ $page->isActive($item->path) ? 'text-pink-700 hover:text-pink-700 dark:text-purple-400 dark:hover:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-indigo-800 hover:text-indigo-800 dark:hover:text-white' }} block px-3 py-2 rounded-md text-base font-medium border-0"
        >
            {{ $item->title }}
        </a>
    @endforeach
    </div>
  </div>
</nav>

@push('scripts')
<script>
    var navMenu = {
        toggle: function() {
            document.getElementById('js-nav-menu').classList.toggle('hidden');
            document.getElementById('js-nav-menu-hide').classList.toggle('hidden');
            document.getElementById('js-nav-menu-show').classList.toggle('hidden');
        }
    }
</script>
@endpush
