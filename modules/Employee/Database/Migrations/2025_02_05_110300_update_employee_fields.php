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
        Schema::table('employees', function (Blueprint $table) {
            // Make email required
            $table->string('email')->nullable(false)->change();
            
            // Drop columns
            $table->dropColumn([
                'join_date',
                'permanent_contact',
                'permanent_city',
                'reference_mobile',
                'permanent_address',
                'email2',
                'phone2'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Make email nullable again
            $table->string('email')->nullable()->change();
            
            // Add back removed columns
            $table->date('join_date')->nullable();
            $table->string('permanent_contact')->nullable();
            $table->string('permanent_city')->nullable();
            $table->string('reference_mobile')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('email2')->nullable();
            $table->string('phone2')->nullable();
        });
    }
};
