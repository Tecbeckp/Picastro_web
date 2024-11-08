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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('follow',['0','1'])->default('1');
            $table->enum('post',['0','1'])->default('1');
            $table->enum('trophy',['1','0'])->default('1');
            $table->enum('star',['1','0'])->default('1');
            $table->enum('comment',['1','0'])->default('1');
            $table->enum('comment_reply',['1','0'])->default('1');
            $table->enum('like_comment',['1','0'])->default('1');
            $table->enum('other',['1','0'])->default('1');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
