<div class="hidden hover-target origin-top-right absolute {{$positionClass}} w-56 rounded-lg shadow-lg bg-gray-100 dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-10">
    <div class="p-2 flex flex-col" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
        <input class="w-0 h-0 invisible theme_auto" name="theme_{{ $suffix }}" id="theme_auto_{{ $suffix }}" type="radio" onchange="document.querySelectorAll('.theme_auto').forEach(el => el.checked = true); el = document.getElementById('stylesheet_link'); el.href = el.dataset.mainsheeturl; document.documentElement.classList.remove('dark'); localStorage.removeItem('theme')">
        <label data-i18n-attrs="text" data-i18n-text="theme.auto" for="theme_auto_{{ $suffix }}" class="cursor-pointer radio-label text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-blue-500 dark:hover:text-indigo-300 px-3 py-2 rounded-md text-sm font-heading font-semibold tracking-wider border-0 flex" role="menuitem">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-5 mr-2" stroke="currentColor">
            <path stroke-width="2" stroke-linecap="square" d="M20.07,3.93l-.79.79M23,11H21.89M13,1V2.11" />
            <path stroke-width="2" d="M5.67,13A9.44,9.44,0,0,1,5,9.5a9.6,9.6,0,0,1,.71-3.61,9,9,0,1,0,12.4,12.4A9.6,9.6,0,0,1,14.5,19a10,10,0,0,1-1.35-.09" />
            <path stroke-width="2" stroke-linejoin="bevel" d="M12.14,5.17a5.5,5.5,0,0,1,6.71,6.62M8.08,16.38l3.5-8.76,3.5,8.76M9.34,14h4.48" />
        </svg>
        </label>
        <input class="w-0 h-0 invisible theme_light" name="theme_{{ $suffix }}" id="theme_light_{{ $suffix }}" type="radio" onchange="document.querySelectorAll('.theme_light').forEach(el => el.checked = true); el = document.getElementById('stylesheet_link'); el.href = el.dataset.manualmodesheeturl; document.documentElement.classList.remove('dark'); localStorage.theme = 'light'">
        <label data-i18n-attrs="text" data-i18n-text="theme.light" for="theme_light_{{ $suffix }}" class="cursor-pointer radio-label text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-blue-500 dark:hover:text-indigo-300 px-3 py-2 rounded-md text-sm font-heading font-semibold tracking-wider border-0 flex" role="menuitem">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-5 mr-2" stroke="currentColor">
            <path stroke-linecap="square" stroke-width="2" d="M5.72,18.28l-.79.79M19.07,4.93l-.79.79m-12.56,0-.79-.79M19.07,19.07l-.79-.79M3.11,12H2m20,0H20.89M12,20.89V22M12,2V3.11M12,7a5,5,0,1,0,5,5A5,5,0,0,0,12,7Z" />
        </svg>
        </label>
        <input class="w-0 h-0 invisible theme_dark" name="theme_{{ $suffix }}" id="theme_dark_{{ $suffix }}" type="radio" onchange="document.querySelectorAll('.theme_dark').forEach(el => el.checked = true); el = document.getElementById('stylesheet_link'); el.href = el.dataset.manualmodesheeturl; document.documentElement.classList.add('dark'); localStorage.theme = 'dark'">
        <label data-i18n-attrs="text" data-i18n-text="theme.dark" for="theme_dark_{{ $suffix }}" class="cursor-pointer radio-label text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-blue-500 dark:hover:text-indigo-300 px-3 py-2 rounded-md text-sm font-heading font-semibold tracking-wider border-0 flex" role="menuitem">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-5 mr-2" stroke="currentColor">
            <path stroke-width="2" d="M17.5,15.5a9,9,0,0,1-9-9,8.89,8.89,0,0,1,.52-3A9,9,0,1,0,20.48,15,8.89,8.89,0,0,1,17.5,15.5Z" />
        </svg>
        </label>
    </div>
</div>