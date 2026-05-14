<?xml version="1.0" encoding="UTF-8" ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title>{{ config('app.name') }}</title>
    <id>{{ url('/') }}</id>
    <link href="{{ url('/') }}" />
    <link rel="self" href="{{ route('front.atom') }}" />
    <updated>{{ optional($posts->first()?->updated_at ?? now())->toAtomString() }}</updated>
    @foreach ($posts as $post)
        <entry>
            <title>{{ $post->title }}</title>
            <id>{{ route('front.show', $post->slug) }}</id>
            <link href="{{ route('front.show', $post->slug) }}" />
            <updated>{{ optional($post->updated_at)->toAtomString() }}</updated>
            <summary>{{ $post->excerpt }}</summary>
        </entry>
    @endforeach
</feed>

