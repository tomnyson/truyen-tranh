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
        Schema::table('posts', function (Blueprint $table) {
            // SEO Meta Title
            $table->string('meta_title')->nullable()->after('id');

            // Status of the post
            $table->string('status')->default('draft')->after('meta_title');

            // Is the post pinned
            $table->boolean('is_pin')->default(false)->after('status'); // Changed to snake_case

            // SEO Meta Description
            $table->text('meta_description')->nullable()->after('is_pin');

            // SEO Meta Keywords
            $table->string('meta_keywords')->nullable()->after('meta_description');

            // SEO Canonical URL
            $table->string('canonical_url')->nullable()->after('meta_keywords');

            // Open Graph Image for SEO
            $table->string('og_image')->nullable()->after('canonical_url');

            // Twitter Image for SEO
            $table->string('twitter_image')->nullable()->after('og_image');

            // Add 'user_id' as a foreign key
            // Removed the duplicate integer declaration
            $table->foreignId('user_id')
            ->nullable() // Allow NULL values
            ->after('status')
            ->constrained()
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['user_id']);

            // Drop the columns
            $table->dropColumn([
                'meta_title',
                'status',
                'is_pin',
                'meta_description',
                'meta_keywords',
                'canonical_url',
                'og_image',
                'twitter_image',
                'user_id',
            ]);
        });
    }
};