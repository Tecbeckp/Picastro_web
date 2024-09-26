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
        Schema::create('vote_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trophy_id');
            $table->unsignedBigInteger('post_image_id');
            $table->unsignedBigInteger('post_user_id');
            $table->unsignedBigInteger('user_id');
            $table->string('month')->nullable();
            $table->integer('IOT')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('trophy_id')->references('id')->on('trophies')->onDelete('cascade');
            $table->foreign('post_image_id')->references('id')->on('post_images')->onDelete('cascade');
            $table->foreign('post_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vote_images');
    }
};
