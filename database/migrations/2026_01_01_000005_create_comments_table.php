<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('author_name', 120);
            $table->string('author_email_hash', 128)->nullable();
            $table->text('body');
            $table->string('status', 40)->default('pending');
            $table->string('ip_hash', 128)->nullable();
            $table->string('user_agent_hash', 128)->nullable();
            $table->timestamps();

            $table->index(['content_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};

