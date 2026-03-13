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
        Schema::create('earnings', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id', 50)->unique();
            $table->string('terminal', 20);
            $table->string('type', 50); // gaming, browsing, printing
            $table->dateTime('date_and_time');
            $table->decimal('amount', 10, 2);
            $table->string('status', 20)->default('completed'); // completed, pending, refunded
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('earnings');
    }
};

