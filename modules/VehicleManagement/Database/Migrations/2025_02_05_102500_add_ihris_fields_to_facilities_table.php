<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIhrisFieldsToFacilitiesTable extends Migration
{
    public function up()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('facility_id')->nullable()->after('id')->comment('iHRIS facility ID');
            $table->string('district')->nullable()->after('name');
            $table->string('region')->nullable()->after('district');
        });
    }

    public function down()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn([
                'facility_id',
                'district',
                'region'
            ]);
        });
    }
}
