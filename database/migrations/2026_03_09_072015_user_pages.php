<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_pages', function (Blueprint $table) {
            $table->id();

            // Relacje główne
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('page_id')
                ->constrained('pages')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Uprawnienia do strony / resource
            $table->boolean('can_get')->default(true);
            $table->boolean('can_post')->default(false);
            $table->boolean('can_patch')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->boolean('is_admin')->default(false);

            // Field-level ACL
            $table->text('view_restricted_fields')->nullable();
            $table->text('edit_restricted_fields')->nullable();

            // Audyt
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();

            // Jeden wpis uprawnień per user + page
            $table->unique(['user_id', 'page_id'], 'uq_user_pages_access');

            // Indeksy pomocnicze
            $table->index('user_id', 'idx_user_pages_user');
            $table->index('page_id', 'idx_user_pages_page');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_pages');
    }
};
