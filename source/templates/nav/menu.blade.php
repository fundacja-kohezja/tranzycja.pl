<nav class="container flex flex-1 flex-col max-w-6xl mx-auto px-4 lg:px-6">
  <div class="max-w-7xl flex flex-1 items-center h-24">
    <div class="relative flex items-center flex-1 justify-between h-16">
      <div class="absolute inset-y-0 left-0 flex items-center lg:hidden">
        {{-- Mobile menu button --}}
        <button onclick="navMenu.toggle()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-expanded="false">
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
            <a href="/" title="{{ $page->nazwaWitryny }} home" class="inline-flex items-center w-auto group border-0">
                <img class="h-8 md:h-10 mr-3" src="/dist/img/logo.svg" alt="Logo"/>
                <span class="text-lg md:text-2xl text-blue-900 dark:text-blue-100 font-semibold group-hover:text-blue-600 dark:group-hover:text-blue-400 my-0 pr-4">{{ $page->nazwaWitryny }}</span>
            </a>
        </div>
        <div class="hidden lg:block lg:ml-6">
          <div class="flex space-x-4">
            @foreach ($items as $item)
                <a href="{{ $item->path }}"
                    class="{{ $page->isActive($item->path) ? 'text-purple-700 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-indigo-800 hover:text-indigo-800 dark:hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium border-0"
                >
                    {{ $item->title }}
                </a>
            @endforeach
          </div>
        </div>
      </div>

      @if ($page->docsearchApiKey && $page->docsearchIndexName)
        <div class="absolute inset-y-0 right-0 flex items-center pr-2 lg:static lg:inset-auto lg:ml-6 lg:pr-0 flex-1 justify-end text-right md:pl-10">
            @if ($page->docsearchApiKey && $page->docsearchIndexName)
                @include('templates.nav.search-input')
            @endif
        </div>
      @endif
    </div>
  </div>

  <div id="js-nav-menu" class="block hidden lg:hidden">
    <div class="px-2 pt-2 pb-3 space-y-1">
      @foreach ($items as $item)
        {{-- Menu item with URL --}}
        <a href="{{ $item->path }}"
            class="{{ $page->isActive($item->path) ? 'text-purple-700 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-indigo-800 hover:text-indigo-800 dark:hover:text-white' }} block px-3 py-2 rounded-md text-base font-medium border-0"
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
