<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Coin Insert Dataset - Records every coin inserted in the machine.
     * Purpose: Track income and time usage.
     * Example: ₱1 = 5 minutes
     */
    public function up(): void
    {
        Schema::create('coin_inserts', function (Blueprint $table) {
            $table->id();
            $table->string('coin_id', 50)->unique();
            $table->string('pc_unit_id', 50);
            $table->decimal('coin_value', 10, 2);
            $table->integer('minutes_given');
            $table->timestamp('inserted_time');
            $table->timestamps();

            $table->index('pc_unit_id');
            $table->index('inserted_time');
            $table->index('coin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_inserts');
    }
};

