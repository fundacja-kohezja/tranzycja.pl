<a href="{{ $path }}"
    class="{{ $additionalClass }} px-3 py-2 rounded-md border-0

        {{ ($label && $label[0] === '*')
        ? 'bg-white hover:bg-gray-200 dark:bg-gray-850 dark:hover:bg-gray-900 font-extrabold'
        :          'hover:bg-gray-200                  dark:hover:bg-gray-850'
        }}

        {{ $isActive($page, $path)
        ? 'text-indigo-600 hover:text-indigo-600 dark:text-purple-400 dark:hover:text-purple-400 font-extrabold'
        : 'text-gray-700   hover:text-blue-500   dark:text-gray-300   dark:hover:text-indigo-300'
        }}
    "
>
    {{ ltrim($label, '*') }}
</a>