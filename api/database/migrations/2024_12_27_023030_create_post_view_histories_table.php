<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('post_view_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->timestamp('viewed_at');
            $table->timestamps();

            // Add indexes
            $table->index(['post_id', 'viewed_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_view_histories');
    }
};
