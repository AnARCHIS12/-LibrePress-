<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ImportRun;
use App\Models\Media;
use App\Models\MenuItem;
use App\Models\Redirect;
use App\Models\Term;
use App\Models\User;
use App\Services\WordPress\WxrImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class WordPressImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_wxr_import_creates_authors_terms_media_menus_redirects_and_content(): void
    {
        $this->seed();
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();
        $path = sys_get_temp_dir().'/librepress-wxr-test.xml';
        file_put_contents($path, $this->wxr());

        $summary = app(WxrImporter::class)->import($path, $admin);

        $this->assertSame(1, $summary['authors']);
        $this->assertSame(1, $summary['contents']);
        $this->assertSame(1, $summary['attachments']);
        $this->assertSame(1, $summary['menus']);
        $this->assertDatabaseHas('contents', ['slug' => 'hello-world', 'title' => 'Hello world']);
        $this->assertDatabaseHas('users', ['email' => 'writer@example.test']);
        $this->assertTrue(Term::query()->where('slug', 'news')->exists());
        $this->assertTrue(Media::query()->where('path', 'https://example.test/uploads/photo.jpg')->exists());
        $this->assertTrue(MenuItem::query()->where('url', '/hello-world')->exists());
        $this->assertTrue(Redirect::query()->where('source_path', '/old/hello-world/')->exists());
        $this->assertSame('finished', ImportRun::query()->latest()->first()?->status);
    }

    private function wxr(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:wp="http://wordpress.org/export/1.2/">
  <channel>
    <wp:author>
      <wp:author_login>writer</wp:author_login>
      <wp:author_email>writer@example.test</wp:author_email>
      <wp:author_display_name>Writer</wp:author_display_name>
    </wp:author>
    <wp:category>
      <wp:term_id>7</wp:term_id>
      <wp:category_nicename>news</wp:category_nicename>
      <wp:cat_name>News</wp:cat_name>
    </wp:category>
    <item>
      <title>Hello world</title>
      <link>https://example.test/old/hello-world/</link>
      <dc:creator>writer</dc:creator>
      <category domain="category" nicename="news">News</category>
      <content:encoded><![CDATA[<p>Hello from WordPress.</p>]]></content:encoded>
      <excerpt:encoded><![CDATA[Imported excerpt]]></excerpt:encoded>
      <wp:post_id>42</wp:post_id>
      <wp:post_name>hello-world</wp:post_name>
      <wp:post_type>post</wp:post_type>
      <wp:status>publish</wp:status>
    </item>
    <item>
      <title>Photo</title>
      <wp:post_id>43</wp:post_id>
      <wp:post_type>attachment</wp:post_type>
      <wp:attachment_url>https://example.test/uploads/photo.jpg</wp:attachment_url>
    </item>
    <item>
      <title>Hello menu</title>
      <wp:post_id>44</wp:post_id>
      <wp:post_type>nav_menu_item</wp:post_type>
      <wp:postmeta><wp:meta_key>_menu_item_object_id</wp:meta_key><wp:meta_value>42</wp:meta_value></wp:postmeta>
    </item>
  </channel>
</rss>
XML;
    }
}

