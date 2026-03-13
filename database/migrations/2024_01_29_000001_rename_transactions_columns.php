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
        // Rename columns in transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('pc_unit_number', 'pc_unit_id');
            $table->renameColumn('coins_inserted', 'total_coins');
            $table->renameColumn('minutes_given', 'total_minutes');
            $table->renameColumn('date', 'transaction_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('pc_unit_id', 'pc_unit_number');
            $table->renameColumn('total_coins', 'coins_inserted');
            $table->renameColumn('total_minutes', 'minutes_given');
            $table->renameColumn('transaction_date', 'date');
        });
    }
};

