<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyLegalDocumentationsTable extends Migration
{
    public function up()
    {
        Schema::table('legal_documentations', function (Blueprint $table) {
            $table->dropColumn('commission');
            $table->dateTime('expiry_date')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('legal_documentations', function (Blueprint $table) {
            $table->decimal('commission', 10, 2)->nullable();
            $table->dateTime('expiry_date')->nullable(false)->change();
        });
    }
}
