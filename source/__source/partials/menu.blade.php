<nav class="container flex flex-1 flex-col max-w-6xl mx-auto px-4 lg:px-6">
  <div class="max-w-7xl flex flex-1 items-center relative">
    <div class="relative flex items-center flex-1 justify-between h-16 lg:h-24">
      <div class="absolute inset-y-0 left-0 flex items-center lg:hidden">
        {{-- Mobile menu button --}}
        <button onclick="navMenu.toggle()" class="inline-flex items-center justify-center -ml-2 sm:ml-0 p-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-blue-500 dark:hover:text-indigo-300 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-expanded="false">
          <span data-i18n-attrs="text" data-i18n-text="navMenu.open" class="sr-only"></span>
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
            <a href="{{ $homeUrl }}" data-i18n-attrs="title" data-i18n-title="navMenu.logoTitle" class="inline-flex items-center w-auto max-w-halfvw group border-0 lg:pr-4">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="200" viewBox="50 0 712 158">
                <g>
                  <path class="logo-dark" d="M206.551,64.384H191.25v40.5h-14.625v-40.5H161.25v-12.75h45.301V64.384z"/>
                  <path class="logo-dark" d="M216.42,104.885v-53.25h24.451c2.598,0,4.998,0.538,7.199,1.612c2.199,1.076,4.088,2.475,5.662,4.2
                    c1.576,1.725,2.812,3.701,3.713,5.925c0.9,2.226,1.35,4.463,1.35,6.713c0,3.101-0.674,5.988-2.025,8.662
                    c-1.35,2.676-3.225,4.863-5.625,6.562l11.25,19.576h-16.5l-9.375-16.351h-5.475v16.351H216.42z M231.045,75.784h9.225
                    c0.9,0,1.738-0.499,2.514-1.5c0.773-1,1.162-2.4,1.162-4.2c0-1.85-0.449-3.263-1.35-4.238s-1.801-1.462-2.701-1.462h-8.85V75.784
                    z"/>
                  <path class="logo-dark" d="M288.359,51.634h13.35l18.449,53.25h-14.85l-3.225-10.426h-14.176l-3.148,10.426h-14.926L288.359,51.634z
                      M299.76,84.709l-4.727-16.5l-4.949,16.5H299.76z"/>
                  <path class="logo-dark" d="M344.248,78.709v26.175h-14.625v-53.25h11.4l21.375,27.075V51.634h14.625v53.25h-11.625L344.248,78.709z"
                    />
                  <path class="logo-dark" d="M387.842,94.01l24.898-29.625h-23.773v-12.75h40.648v10.875l-23.398,29.625h23.85v12.75h-42.225V94.01z"
                    />
                  <path class="logo-dark" d="M453.035,51.634l9.525,23.025l9.75-23.025h15.9l-18.375,35.625v17.625h-14.551V87.109l-18.074-35.475
                    H453.035z"/>
                  <path class="logo-dark" d="M489.135,77.809c0-3.25,0.611-6.45,1.838-9.6c1.225-3.15,3.012-5.975,5.361-8.475
                    c2.35-2.5,5.201-4.525,8.551-6.075c3.35-1.549,7.174-2.325,11.475-2.325c5.148,0,9.611,1.062,13.387,3.188
                    c3.775,2.125,6.588,4.938,8.438,8.438l-11.174,7.95c-0.502-1.299-1.164-2.374-1.988-3.225c-0.824-0.85-1.738-1.524-2.738-2.025
                    c-1-0.499-2.037-0.85-3.111-1.05c-1.076-0.199-2.113-0.3-3.111-0.3c-2.102,0-3.914,0.413-5.438,1.238
                    c-1.527,0.825-2.775,1.901-3.75,3.225c-0.977,1.325-1.701,2.825-2.176,4.5c-0.477,1.676-0.713,3.337-0.713,4.987
                    c0,1.851,0.275,3.626,0.826,5.325c0.549,1.7,1.35,3.2,2.398,4.5c1.051,1.301,2.338,2.338,3.863,3.112
                    c1.525,0.776,3.236,1.163,5.137,1.163c1,0,2.025-0.113,3.076-0.337c1.049-0.225,2.061-0.6,3.037-1.125
                    c0.975-0.525,1.848-1.2,2.625-2.025c0.773-0.825,1.387-1.837,1.836-3.037l11.926,7.125c-0.801,1.95-2.014,3.701-3.637,5.25
                    c-1.627,1.551-3.477,2.85-5.551,3.9c-2.076,1.05-4.301,1.85-6.676,2.4c-2.373,0.549-4.686,0.824-6.936,0.824
                    c-3.951,0-7.564-0.787-10.838-2.362c-3.275-1.575-6.102-3.662-8.475-6.263c-2.375-2.6-4.213-5.55-5.514-8.85
                    C489.783,84.559,489.135,81.21,489.135,77.809z"/>
                  <path class="logo-dark" d="M547.451,90.484c0.148,0.101,0.475,0.275,0.975,0.525c0.498,0.251,1.125,0.5,1.875,0.75
                    c0.75,0.251,1.611,0.476,2.588,0.675c0.975,0.201,1.986,0.3,3.037,0.3c1.549,0,2.799-0.25,3.75-0.75
                    c0.949-0.5,1.674-1.312,2.174-2.438s0.826-2.575,0.977-4.35c0.148-1.774,0.225-3.938,0.225-6.487V51.634h14.625v27.075
                    c0,4.05-0.225,7.738-0.676,11.062c-0.449,3.326-1.424,6.163-2.924,8.512c-1.5,2.351-3.689,4.175-6.562,5.476
                    c-2.877,1.299-6.738,1.949-11.588,1.949c-4.5,0-8.352-1.1-11.551-3.3L547.451,90.484z"/>
                  <path class="logo-dark" d="M602.797,51.634h13.35l18.449,53.25h-14.85l-3.225-10.426h-14.176l-3.148,10.426h-14.926L602.797,51.634z
                      M614.197,84.709l-4.727-16.5l-4.949,16.5H614.197z"/>
                  <path class="logo-dark" d="M644.432,104.885v-13.8h11.549v13.8H644.432z"/>
                  <path class="logo-dark" d="M667.928,104.885v-53.25h22.951c2.598,0,4.998,0.538,7.199,1.612c2.199,1.076,4.088,2.475,5.662,4.2
                    c1.576,1.725,2.812,3.701,3.713,5.925c0.9,2.226,1.35,4.463,1.35,6.713c0,2.351-0.426,4.638-1.275,6.862
                    c-0.85,2.226-2.037,4.2-3.562,5.925c-1.525,1.725-3.375,3.101-5.549,4.125c-2.176,1.025-4.564,1.538-7.162,1.538h-8.701v16.351
                    H667.928z M682.553,75.784h7.725c0.9,0,1.738-0.413,2.514-1.237c0.773-0.825,1.162-2.312,1.162-4.462c0-2.2-0.449-3.7-1.35-4.5
                    c-0.9-0.799-1.801-1.2-2.701-1.2h-7.35V75.784z"/>
                  <path class="logo-dark" d="M719.826,104.885v-53.25h14.625v40.5h24.15v12.75H719.826z"/>
                </g>
                <rect x="54" y="52" fill="#3FA9F5" width="27" height="27"/>
                <rect x="81" y="79" fill="#FF0000" width="27" height="27"/>
                <rect x="81" y="52" fill="#fff" width="27" height="27"/>
                <rect x="108" y="52" fill="#FF7BAC" width="27" height="27"/>
              </svg>
            </a>
        </div>
        <div class="hidden lg:block lg:ml-6">
          <div class="items-center h-full flex space-x-4 font-heading font-semibold tracking-wider">
            @foreach ($items as $path => $label)
              @include('__source.partials.nav-item', ['additionalClass' => 'text-sm'])
            @endforeach
            @if ($searchEnabled)
            <button type="button" class="toggle-search inline-flex justify-center text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-blue-500 dark:hover:text-indigo-300 px-2 sm:px-3 py-2 rounded-md text-sm font-heading font-semibold tracking-wider border-0">
                <span data-i18n-attrs="text" data-i18n-text="search.show" class="sr-only"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
            @endif
          </div>
        </div>
      </div>
    
      <div class="absolute inset-y-0 right-0 flex items-center flex-1 justify-end text-right">
          @if ($searchEnabled)
          <button type="button" class="toggle-search lg:hidden inline-flex justify-center w-full text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-blue-500 dark:hover:text-indigo-300 px-2 sm:px-3 py-2 rounded-md text-sm font-heading font-semibold tracking-wider border-0">
              <span data-i18n-attrs="text" data-i18n-text="search.search" class="sr-only"></span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
          </button>
          @endif
          <div class="relative text-left hover-trigger -mr-2 sm:mr-0 hidden lg:inline-block">
            <button type="button" class="inline-flex justify-center w-full text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-blue-500 dark:hover:text-indigo-300 px-2 sm:px-3 py-2 rounded-md text-sm font-heading font-semibold tracking-wider border-0" id="options-menu" aria-haspopup="true">
              <span data-i18n-attrs="text" data-i18n-text="theme.label" class="sr-only"></span>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-5 mr-1" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="square" d="M2.53,7.17l1,.43M15.17,2.79l.42-1M2.53,14.83l1-.43M7.94,1.76l.43,1" />
                <path stroke-width="2" d="M19.51,20.52a6.3,6.3,0,0,1,0-8.89,6.19,6.19,0,0,1,1.73-1.22,6.28,6.28,0,1,0,0,11.33A6.19,6.19,0,0,1,19.51,20.52Zm-11.28-6A5,5,0,0,1,15.3,7.46" />
              </svg>
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            @include('__source.partials.theme-select', ['positionClass' => 'right-0', 'suffix' => '1'])
          </div>
          <div class="relative text-left hover-trigger -mr-2 sm:mr-0 hidden lg:inline-block">
            <button type="button" class="inline-flex justify-center w-full text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-blue-500 dark:hover:text-indigo-300 px-2 sm:px-3 py-2 rounded-md text-sm font-heading font-semibold tracking-wider border-0" id="options-menu" aria-haspopup="true">
              <span data-i18n-attrs="text" data-i18n-text="theme.label" class="sr-only"></span>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" height="24" width="24" class="mr-1" stroke="currentColor">
                <path xmlns="http://www.w3.org/2000/svg" d="M12 20.7q-1.8 0-3.387-.688-1.588-.687-2.763-1.862-1.175-1.175-1.863-2.763Q3.3 13.8 3.3 12t.687-3.388Q4.675 7.025 5.85 5.85t2.763-1.863Q10.2 3.3 12 3.3t3.388.687q1.587.688 2.762 1.863t1.863 2.762Q20.7 10.2 20.7 12q0 1.8-.687 3.387-.688 1.588-1.863 2.763-1.175 1.175-2.762 1.862Q13.8 20.7 12 20.7Zm0-.675q.95-1.2 1.55-2.325.6-1.125.975-2.55h-5.05q.425 1.525 1 2.65T12 20.025Zm-.875-.075q-.775-.825-1.425-2.138-.65-1.312-.95-2.662h-4.1q.9 2.05 2.625 3.325Q9 19.75 11.125 19.95Zm1.75 0q2.125-.2 3.85-1.475Q18.45 17.2 19.35 15.15h-4.1q-.425 1.375-1.075 2.687-.65 1.313-1.3 2.113Zm-8.5-5.5H8.6q-.125-.65-.175-1.262-.05-.613-.05-1.188t.05-1.188q.05-.612.175-1.262H4.375q-.175.525-.275 1.175Q4 11.375 4 12q0 .625.1 1.275.1.65.275 1.175Zm4.925 0h5.4q.125-.65.175-1.238.05-.587.05-1.212t-.05-1.213q-.05-.587-.175-1.237H9.3q-.125.65-.175 1.237-.05.588-.05 1.213 0 .625.05 1.212.05.588.175 1.238Zm6.1 0h4.225q.175-.525.275-1.175.1-.65.1-1.275 0-.625-.1-1.275-.1-.65-.275-1.175H15.4q.125.65.175 1.262.05.613.05 1.188t-.05 1.188q-.05.612-.175 1.262Zm-.15-5.6h4.1q-.925-2.1-2.587-3.325-1.663-1.225-3.888-1.5.775.95 1.4 2.225.625 1.275.975 2.6Zm-5.775 0h5.05q-.425-1.5-1.037-2.688Q12.875 4.975 12 3.975q-.875 1-1.488 2.187Q9.9 7.35 9.475 8.85Zm-4.825 0h4.1q.35-1.325.975-2.6.625-1.275 1.4-2.225-2.25.275-3.9 1.512Q5.575 6.775 4.65 8.85Z"/>
              </svg>
              <svg class="h-5 w-5" style="margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            @include('__source.partials.language-select', ['positionClass' => 'right-0', 'suffix' => '1'])
          </div>
      </div>
    </div>
  </div>
  <div id="autocomplete-search-container-menu" class="hidden w-full mb-8"></div>
  <div id="js-nav-menu" class="block hidden lg:hidden">
    <div class="pt-2 pb-3 space-y-1 font-heading font-semibold tracking-wider">
      @foreach ($items as $path => $label)
        @include('__source.partials.nav-item', ['additionalClass' => 'block text-base'])
      @endforeach
      <div class="relative hover-trigger">
        <button data-i18n-attrs="text" data-i18n-text="theme.label" class="w-full text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-blue-500 dark:hover:text-indigo-300 flex tracking-wider items-center px-3 py-2 rounded-md text-base border-0">
          <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
        @include('__source.partials.theme-select', ['positionClass' => 'left-0', 'suffix' => '2'])
      </div>
      <div class="relative hover-trigger">
        <button data-i18n-attrs="text" data-i18n-text="language.label" class="w-full text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-blue-500 dark:hover:text-indigo-300 flex tracking-wider items-center px-3 py-2 rounded-md text-base border-0">
          <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
        @include('__source.partials.language-select', ['positionClass' => 'left-0', 'suffix' => '2'])
      </div>
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
