<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_are_available(): void
    {
        $this->seed();

        $this->get('/')->assertOk()->assertSee('LibrePress');
        $this->get('/blog')->assertOk()->assertSee('Premier article');
        $this->get('/premier-article')->assertOk()->assertSee('Premier article');
        $this->get('/feed.xml')->assertOk()->assertHeader('content-type', 'application/rss+xml; charset=UTF-8');
        $this->get('/atom.xml')->assertOk()->assertHeader('content-type', 'application/atom+xml; charset=UTF-8');
        $this->get('/sitemap.xml')->assertOk()->assertSee('premier-article');
        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap:');
    }

    public function test_search_and_api_return_published_content(): void
    {
        $this->seed();

        $this->get('/search?q=Premier')->assertOk()->assertSee('Premier article');
        $this->assertDatabaseHas('search_documents', ['title' => 'Premier article']);
        $this->getJson('/api/v1/posts')->assertOk()->assertJsonPath('data.data.0.slug', 'premier-article');
    }
}
