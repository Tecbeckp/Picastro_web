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
        Schema::create('payment_method_statuses', function (Blueprint $table) {
            $table->id();
            $table->enum('paypal_android',['0','1'])->default('1');
            $table->enum('paypal_ios',['0','1'])->default('1');
            $table->enum('stripe_android',['0','1'])->default('1');
            $table->enum('stripe_ios',['0','1'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_method_statuses');
    }
};
