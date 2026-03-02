<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {

            $table->id();

            // Technical identity
            $table->string('name')->unique();
            $table->string('endpoint');
            $table->string('frontend_url')->nullable();

            // Navigation grouping
            $table->string('group_key')->nullable()->index();
            $table->string('group_label')->nullable();
            $table->integer('menu_order')->default(0);

            $table->string('label');

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('pages')
                ->nullOnDelete();

            $table->boolean('is_menu')->default(true);
            $table->boolean('is_active')->default(true)->index();

            // Security
            $table->boolean('requires_auth')->default(true);

            // Runtime policy
            $table->boolean('log_requests')->default(false);
            $table->unsignedInteger('cache_days')->nullable();

            // Meta
            $table->text('description')->nullable();
            $table->json('settings')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
