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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id', 50)->unique();
            $table->string('pc_unit_number', 50);
            $table->decimal('coins_inserted', 10, 2);
            $table->integer('minutes_given');
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->date('date');
            $table->string('status', 20)->default('completed'); // completed, active, cancelled
            $table->timestamps();

            $table->index('pc_unit_number');
            $table->index('date');
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

