<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('off_board_lot_number')->nullable()->after('off_board_remarks');
            $table->string('off_board_buyer')->nullable()->after('off_board_lot_number');
            $table->decimal('off_board_amount', 15, 2)->nullable()->after('off_board_buyer');
            $table->string('off_board_reason')->nullable()->after('off_board_remarks');
        });
    }

    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'off_board_lot_number',
                'off_board_buyer',
                'off_board_amount',
                'off_board_reason'
            ]);
        });
    }
};
