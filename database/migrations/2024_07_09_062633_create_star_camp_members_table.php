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
        Schema::create('star_camp_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('star_camp_id');
            $table->unsignedBigInteger('member_id');
            $table->foreign('member_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('star_camp_id')->references('id')->on('star_camps')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('star_camp_members');
    }
};
