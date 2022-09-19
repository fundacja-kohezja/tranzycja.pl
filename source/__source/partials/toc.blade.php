<aside class="toc-container">
    <details id="toc" class="bg-gray-100 dark:bg-gray-800 rounded-lg px-4 py-1 lg:py-0 toc lg:my-4">
        <summary>{{ $label }}</summary>
        <nav>
            <ul class="{{ $pagesBefore || $pagesAfter ? '' : 'list-none ' }}pl-0 mt-0">
                @if($pagesBefore || $pagesAfter)
                    @foreach($pagesBefore as $slug => $page)
                        <li>
                            <a
                                class="block leading-tight font-bold border-b-0 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md"
                                href="{{ $slug }}"
                            >
                                {{ $page }}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <ul class="list-none pl-0 mt-0">
                            @foreach($headings as $h)
                                @if($h['level'] == 1)
                                    <li>
                                        <a
                                            onclick="if(window.matchMedia('(max-width: 1023px)').matches)this.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.open = false"
                                            class="block leading-tight font-extrabold border-b-0 text-pink-700 dark:text-purple-400 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md"
                                            href="#{{ $h['slug'] }}"
                                        >
                                            {!! $h['text'] !!}
                                        </a>
                                    </li>
                                @elseif($h['level'] <= $maxLevel)
                                    <li{!! $h['level'] > 2 ? ' class="foldable" ' : '' !!}>
                                        <a
                                            onclick="if(window.matchMedia('(max-width: 1023px)').matches)this.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.open = false"
                                            class="block leading-tight font-light sm:text-xs border-b-0 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md"
                                            style="padding-left: {{ $h['level'] - 1 }}.5rem"
                                            href="#{{ $h['slug'] }}"
                                        >
                                            {!! $h['text'] !!}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                    @foreach($pagesAfter as $slug => $page)
                        <li>
                            <a
                                class="block leading-tight font-bold border-b-0 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md"
                                href="{{ $slug }}"
                            >
                                {{ $page }}
                            </a>
                        </li>
                    @endforeach
                @else
                    @foreach($headings as $h)
                        @if($h['level'] == 1)
                            <li>
                                <a
                                    onclick="if(window.matchMedia('(max-width: 1023px)').matches)this.parentElement.parentElement.parentElement.parentElement.open = false"
                                    class="block leading-tight font-bold border-b-0 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md"
                                    href="#{{ $h['slug'] }}"
                                >
                                    {!! $h['text'] !!}
                                </a>
                            </li>
                        @elseif($h['level'] <= $maxLevel)
                            <li{!! $h['level'] > 2 ? ' class="foldable" ' : '' !!}>
                                <a
                                    onclick="if(window.matchMedia('(max-width: 1023px)').matches)this.parentElement.parentElement.parentElement.parentElement.open = false"
                                    class="block leading-tight font-light sm:text-xs border-b-0 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-850 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 rounded-md"
                                    style="padding-left: {{ $h['level'] - 1 }}rem" href="#{{ $h['slug'] }}"
                                >
                                    {!! $h['text'] !!}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </nav>
    </details>
</aside>