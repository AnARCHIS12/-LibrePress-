<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('search_documents', function (Blueprint $table): void {
            $table->id();
            $table->morphs('searchable');
            $table->string('type', 40);
            $table->string('locale', 12)->default('fr');
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index(['type', 'locale']);
        });

        Schema::table('media', function (Blueprint $table): void {
            $table->json('meta')->nullable()->after('caption');
        });
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table): void {
            $table->dropColumn('meta');
        });

        Schema::dropIfExists('search_documents');
    }
};

