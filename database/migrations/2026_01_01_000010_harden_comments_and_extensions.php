<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table): void {
            $table->unsignedInteger('reports_count')->default(0)->after('status');
            $table->boolean('is_spam')->default(false)->after('reports_count');
            $table->string('moderation_reason')->nullable()->after('is_spam');
        });

        Schema::create('comment_reports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('comment_id')->constrained('comments')->cascadeOnDelete();
            $table->string('reason', 80)->default('other');
            $table->text('message')->nullable();
            $table->string('ip_hash', 128)->nullable();
            $table->timestamps();
            $table->index(['comment_id', 'reason']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_reports');

        Schema::table('comments', function (Blueprint $table): void {
            $table->dropColumn(['reports_count', 'is_spam', 'moderation_reason']);
        });
    }
};

