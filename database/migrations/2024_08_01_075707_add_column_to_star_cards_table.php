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
        Schema::table('star_cards', function (Blueprint $table) {
            $table->string('telescope_name')->nullable()->after('cooling');
            $table->string('scope_type')->nullable()->after('telescope_name');
            $table->string('mount_name')->nullable()->after('scope_type');
            $table->string('camera_lens')->nullable()->after('mount_name');
            $table->string('imaging_camera')->nullable()->after('camera_lens');
            $table->string('guide_camera')->nullable()->after('imaging_camera');
            $table->string('guide_scope')->nullable()->after('guide_camera');
            $table->string('filter_wheel')->nullable()->after('guide_scope');
            $table->string('reducer_name')->nullable()->after('filter_wheel');
            $table->string('autofocuser')->nullable()->after('reducer_name');
            $table->string('other_accessories')->nullable()->after('autofocuser');
            $table->string('barlow_lens')->nullable()->after('other_accessories');
            $table->string('filters')->nullable()->after('barlow_lens');
            $table->string('acquistion_software')->nullable()->after('filters');
            $table->string('processing')->nullable()->after('acquistion_software');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('star_cards', function (Blueprint $table) {
            //
        });
    }
};
