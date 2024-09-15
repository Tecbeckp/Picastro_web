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
        Schema::create('main_setups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name')->nullable();
            $table->string('telescope_name')->nullable();
            $table->string('scope_type')->nullable();
            $table->string('mount_name')->nullable();
            $table->string('camera_lens')->nullable();
            $table->string('imaging_camera')->nullable();
            $table->string('guide_camera')->nullable();
            $table->string('guide_scope')->nullable();
            $table->string('filter_wheel')->nullable();
            $table->string('reducer_name')->nullable();
            $table->string('autofocuser')->nullable();
            $table->string('other_accessories')->nullable();
            $table->string('barlow_lens')->nullable();
            $table->string('filters')->nullable();
            $table->string('acquistion_software')->nullable();
            $table->string('processing')->nullable();
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
        Schema::dropIfExists('main_setups');
    }
};
