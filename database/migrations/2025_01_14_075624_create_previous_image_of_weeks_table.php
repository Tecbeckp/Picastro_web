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
        Schema::create('previous_image_of_weeks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->integer('place')->nullable();
            $table->double('vote')->nullable();
            $table->double('star')->nullable();
            $table->double('comment')->nullable();
            $table->double('total')->nullable();
            $table->timestamp('weeek')->nullable();
            $table->foreign('post_id')->references('id')->on('post_images')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('previous_image_of_weeks');
    }
};
