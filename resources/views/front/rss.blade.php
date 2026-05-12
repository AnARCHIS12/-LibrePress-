<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
    <channel>
        <title>{{ config('app.name') }}</title>
        <link>{{ url('/') }}</link>
        <description>Flux RSS {{ config('app.name') }}</description>
        @foreach ($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ route('front.show', $post->slug) }}</link>
                <guid>{{ route('front.show', $post->slug) }}</guid>
                <description>{{ $post->excerpt }}</description>
                <pubDate>{{ optional($post->published_at)->toRfc2822String() }}</pubDate>
            </item>
        @endforeach
    </channel>
</rss>

