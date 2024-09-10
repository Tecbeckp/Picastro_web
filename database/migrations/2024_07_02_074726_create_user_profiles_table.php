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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name')->nullable();
            $table->string('experience_level')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('pronouns')->nullable();
            $table->text('bio')->nullable();
            $table->string('web_site_one')->nullable();
            $table->string('web_site_two')->nullable();
            $table->string('web_site_link')->nullable();
            $table->string('drive_link')->nullable();
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
        Schema::dropIfExists('user_profiles');
    }
};
