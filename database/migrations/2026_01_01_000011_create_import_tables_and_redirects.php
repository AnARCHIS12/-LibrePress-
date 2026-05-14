<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('import_runs', function (Blueprint $table): void {
            $table->id();
            $table->string('source');
            $table->string('file_path')->nullable();
            $table->string('status', 40)->default('running');
            $table->json('summary')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('redirects', function (Blueprint $table): void {
            $table->id();
            $table->string('source_path')->unique();
            $table->string('target_path');
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
        Schema::dropIfExists('import_runs');
    }
};

