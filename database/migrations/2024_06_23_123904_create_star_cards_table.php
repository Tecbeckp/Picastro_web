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
        Schema::create('star_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('post_image_id');
            $table->string('camera_type')->nullable();
            $table->unsignedBigInteger('setup')->nullable();
            $table->integer('light_frame_number')->nullable();
            $table->integer('light_frame_exposure')->nullable();
            $table->integer('number_of_darks')->nullable();
            $table->integer('number_of_flats')->nullable();
            $table->integer('number_of_dark_flats')->nullable();
            $table->integer('number_of_bias')->nullable();
            $table->integer('iso')->nullable();
            $table->string('ratio')->nullable();
            $table->string('focal_length')->nullable();
            $table->string('cooling')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('post_image_id')->references('id')->on('post_images')->onDelete('cascade');
            $table->foreign('setup')->references('id')->on('main_setups')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('star_cards');
    }
};
