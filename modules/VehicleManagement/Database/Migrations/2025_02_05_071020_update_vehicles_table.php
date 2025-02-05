<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Drop unused columns
            $table->dropColumn('alert_cell_no');
            $table->dropColumn('alert_email');
            
            // Add new columns
            $table->string('image')->nullable()->after('name');
            $table->string('previous_plate')->nullable()->after('license_plate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Revert new columns
            $table->dropColumn('image');
            $table->dropColumn('previous_plate');
            
            // Restore dropped columns
            $table->string('alert_cell_no')->nullable();
            $table->string('alert_email')->nullable();
        });
    }
};
