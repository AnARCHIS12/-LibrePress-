@extends('themes.nova.layout')

@section('content')
    <article class="mx-auto max-w-3xl px-4 py-10">
        <h1 class="text-4xl font-semibold tracking-normal text-slate-950 dark:text-white">
            {{ $content->title }}
        </h1>

        <div class="prose prose-slate mt-8 dark:prose-invert">
            {!! $renderedBlocks !!}
        </div>
    </article>
@endsection

