<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create a new table for PC session tracking (renamed to avoid conflict with Laravel's sessions table).
     */
    public function up(): void
    {
        // Check if pc_sessions table doesn't exist, then create it
        if (!Schema::hasTable('pc_sessions')) {
            Schema::create('pc_sessions', function (Blueprint $table) {
                $table->id();
                $table->string('session_id', 100)->unique();
                $table->string('pc_unit_number', 50);
                $table->string('user_session_name', 100);
                $table->timestamp('start_time');
                $table->integer('remaining_time')->default(0); // in minutes
                $table->enum('status', ['Active', 'Ended'])->default('Active');
                $table->timestamp('end_time')->nullable();
                $table->timestamps();

                $table->index('pc_unit_number');
                $table->index('status');
                $table->index('session_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pc_sessions');
    }
};

