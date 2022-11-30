<div class="metabox">
    <ul class="list-none pl-0 flex gap-6 font-semibold font-heading leading-tight tracking-wide flex-wrap text-indigo-600 dark:text-purple-400">
        @foreach($meta as $key => $value)
            <li><span data-i18n-attrs="text" data-i18n-text="{{ $key }}" class="text-indigo-500 dark:text-purple-500 uppercase text-xs font-extrabold tracking-wider"></span><br> {{ $value }}</li>
        @endforeach
    </ul>
</div>