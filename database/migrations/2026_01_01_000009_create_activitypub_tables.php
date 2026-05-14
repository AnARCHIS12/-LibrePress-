<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activitypub_actors', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('username')->unique();
            $table->string('type')->default('Person');
            $table->text('public_key')->nullable();
            $table->text('private_key')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('activitypub_objects', function (Blueprint $table): void {
            $table->id();
            $table->string('uri')->unique();
            $table->string('type', 80);
            $table->json('payload');
            $table->timestamps();
        });

        Schema::create('activitypub_inbox', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('activitypub_actors')->nullOnDelete();
            $table->string('remote_actor')->nullable()->index();
            $table->json('payload');
            $table->string('status', 40)->default('received');
            $table->timestamps();
        });

        Schema::create('activitypub_outbox', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('actor_id')->constrained('activitypub_actors')->cascadeOnDelete();
            $table->json('payload');
            $table->string('status', 40)->default('pending');
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        Schema::create('activitypub_followers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('actor_id')->constrained('activitypub_actors')->cascadeOnDelete();
            $table->string('remote_actor');
            $table->string('status', 40)->default('accepted');
            $table->timestamps();
            $table->unique(['actor_id', 'remote_actor']);
        });

        Schema::create('activitypub_blocks', function (Blueprint $table): void {
            $table->id();
            $table->string('domain')->nullable()->index();
            $table->string('actor')->nullable()->index();
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activitypub_blocks');
        Schema::dropIfExists('activitypub_followers');
        Schema::dropIfExists('activitypub_outbox');
        Schema::dropIfExists('activitypub_inbox');
        Schema::dropIfExists('activitypub_objects');
        Schema::dropIfExists('activitypub_actors');
    }
};

