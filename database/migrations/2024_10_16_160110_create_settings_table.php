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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->enum('maintenance_ios', ['0','1'])->default('0');
            $table->enum('maintenance_android', ['0','1'])->default('0');
            $table->string('maintenance_title')->nullable();
            $table->text('maintenance_description')->nullable();
            $table->string('maintenance_ios_version')->nullable();
            $table->string('maintenance_android_version')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
