<div class="hidden hover-target origin-top-right absolute {{$positionClass}} w-56 rounded-lg shadow-lg bg-gray-100 dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-10">
    <div class="p-2 flex flex-col" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
        <input class="w-0 h-0 invisible" name="lang_pl" id="lang_pl" type="radio" onclick="window.location = '/';"  {{ strpos($page->getPath(), 'en/') === FALSE ? 'checked' : ''}}>
        <label data-i18n-attrs="text" data-i18n-text="language.polish" for="lang_pl" class="cursor-pointer radio-label text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-blue-500 dark:hover:text-indigo-300 px-3 py-2 rounded-md text-sm font-heading font-semibold tracking-wider border-0 flex" role="menuitem"></label>
        
        <input class="w-0 h-0 invisible" name="lang_en" id="lang_en" type="radio" onclick="window.location = '/en';" {{ strpos($page->getPath(), 'en/') === FALSE ? '' : 'checked'}} >
        <label data-i18n-attrs="text" data-i18n-text="language.english" for="lang_en" class="cursor-pointer radio-label text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-blue-500 dark:hover:text-indigo-300 px-3 py-2 rounded-md text-sm font-heading font-semibold tracking-wider border-0 flex" role="menuitem"></label>
    </div>
</div>