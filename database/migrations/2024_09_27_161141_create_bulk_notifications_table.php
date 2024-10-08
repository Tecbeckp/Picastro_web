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
        Schema::create('bulk_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('message')->nullable();
            $table->longText('success_user')->nullable();
            $table->longText('faild_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_notifications');
    }
};
