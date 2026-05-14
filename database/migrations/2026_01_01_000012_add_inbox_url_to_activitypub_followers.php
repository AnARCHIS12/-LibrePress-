<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('activitypub_followers', function (Blueprint $table): void {
            $table->string('inbox_url')->nullable()->after('remote_actor');
        });
    }

    public function down(): void
    {
        Schema::table('activitypub_followers', function (Blueprint $table): void {
            $table->dropColumn('inbox_url');
        });
    }
};

