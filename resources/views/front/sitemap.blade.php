<?xml version="1.0" encoding="UTF-8" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    @foreach ($contents as $content)
        <url>
            <loc>{{ route('front.show', $content->slug) }}</loc>
            <lastmod>{{ $content->updated_at->toDateString() }}</lastmod>
            <changefreq>{{ $content->type === 'post' ? 'weekly' : 'monthly' }}</changefreq>
            <priority>{{ $content->type === 'post' ? '0.8' : '0.7' }}</priority>
        </url>
    @endforeach
</urlset>

