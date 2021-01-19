<li class="pl-4">
    {{-- Menu item with URL--}}
    <a href="{{ $item->getPath() }}"
        class="{{ $page->isActive($item->getPath()) ? 'active font-semibold text-blue-500' : '' }} nav-menu__item hover:text-blue-500"
    >
        {{ $item->title }}
    </a>
</li>
