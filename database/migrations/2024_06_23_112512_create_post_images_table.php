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
        Schema::create('post_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('object_type_id')->nullable();
            $table->unsignedBigInteger('bortle_id')->nullable();
            $table->unsignedBigInteger('observer_location_id')->nullable();
            $table->unsignedBigInteger('approx_lunar_phase_id')->nullable();
            $table->integer('video_length')->nullable();
            $table->integer('number_of_frame')->nullable();
            $table->integer('number_of_video')->nullable();
            $table->integer('exposure_time')->nullable();
            $table->integer('total_hours')->nullable();
            $table->integer('additional_minutes')->nullable();
            $table->string('catalogue_number')->nullable();
            $table->string('object_name')->nullable();
            $table->string('ir_pass')->nullable();
            $table->string('planet_name')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_images');
    }
};
