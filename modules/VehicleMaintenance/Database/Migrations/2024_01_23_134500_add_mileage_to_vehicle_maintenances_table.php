<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMileageToVehicleMaintenancesTable extends Migration
{
  public function up()
  {
    Schema::table('vehicle_maintenances', function (Blueprint $table) {
      $table->decimal('mileage', 10, 2)->nullable()->after('date');
    });
  }

  public function down()
  {
    Schema::table('vehicle_maintenances', function (Blueprint $table) {
      $table->dropColumn('mileage');
    });
  }
}
