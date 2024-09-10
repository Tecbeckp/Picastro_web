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
        Schema::create('star_card_filters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('star_card_id');
            $table->string('name');
            $table->integer('number_of_subs')->nullable();
            $table->integer('exposure_time')->nullable();
            $table->string('gain')->nullable();
            $table->string('binning')->nullable();
            $table->foreign('star_card_id')->references('id')->on('star_cards')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('star_card_filters');
    }
};
