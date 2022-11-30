@extends('__source.layouts.master', ['lang' => $lang ?? NULL])

@section('body')
<div class="container max-w-6xl mx-auto px-6 py-4">
    <main class="flex flex-col lg:flex-row">
        <div class="DocSearch-content w-full break-words pb-16">
            @yield('content')
        </div>
    </main>
</div>
@endsection
