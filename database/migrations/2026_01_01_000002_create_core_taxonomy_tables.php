<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('taxonomies', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('owner')->default('core');
            $table->timestamps();
        });

        Schema::create('terms', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('taxonomy_id')->constrained('taxonomies')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('terms')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['taxonomy_id', 'slug']);
        });

        Schema::create('content_terms', function (Blueprint $table): void {
            $table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            $table->foreignId('term_id')->constrained('terms')->cascadeOnDelete();
            $table->primary(['content_id', 'term_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_terms');
        Schema::dropIfExists('terms');
        Schema::dropIfExists('taxonomies');
    }
};

