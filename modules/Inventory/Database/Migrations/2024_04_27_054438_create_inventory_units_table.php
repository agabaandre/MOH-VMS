<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryUnitsTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('abbreviation');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add unit_id to inventory_parts table
        Schema::table('inventory_parts', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->after('qty')->constrained('inventory_units');
        });

        // Seed common units
        $units = [
            ['name' => 'Piece', 'abbreviation' => 'pc'],
            ['name' => 'Litre', 'abbreviation' => 'L'],
            ['name' => 'Milliliter', 'abbreviation' => 'mL'],
            ['name' => 'Kilogram', 'abbreviation' => 'kg'],
            ['name' => 'Gram', 'abbreviation' => 'g'],
            ['name' => 'Can', 'abbreviation' => 'can'],
            ['name' => 'Box', 'abbreviation' => 'box'],
            ['name' => 'Pair', 'abbreviation' => 'pair'],
            ['name' => 'Set', 'abbreviation' => 'set'],
            ['name' => 'Unit', 'abbreviation' => 'unit'],
        ];

        DB::table('inventory_units')->insert(array_map(function ($unit) {
            return $unit + ['created_at' => now(), 'updated_at' => now()];
        }, $units));
    }

    public function down()
    {
        Schema::table('inventory_parts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('unit_id');
        });
        Schema::dropIfExists('inventory_units');
    }
}
