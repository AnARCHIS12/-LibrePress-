<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 40);
            $table->string('status', 40)->default('draft');
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('contents')->nullOnDelete();
            $table->uuid('translation_group_id')->nullable()->index();
            $table->string('locale', 12)->default('fr');
            $table->string('slug');
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->json('body_json');
            $table->longText('body_html')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();

            $table->unique(['type', 'locale', 'slug']);
            $table->index(['status', 'published_at']);
        });

        Schema::create('content_revisions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->json('body_json');
            $table->longText('body_html')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_revisions');
        Schema::dropIfExists('contents');
    }
};

