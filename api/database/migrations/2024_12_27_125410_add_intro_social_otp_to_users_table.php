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
        Schema::table('users', function (Blueprint $table) {
            $table->string('intro')->nullable()->after('email'); // Add 'intro' column
            $table->json('social')->nullable()->after('intro');  // Add 'social' column
            $table->string('otp_code', 6)->nullable()->after('social'); // Add 'otp_code' column
            $table->string('avatar')->nullable()->after('otp_code'); // Add 'otp_code' column
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['intro', 'social', 'otp_code', 'avatar']); // Remove added columns
        });
    }
};
