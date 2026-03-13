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
        Schema::create('pc_units', function (Blueprint $table) {
            $table->id();
            $table->string('pc_number', 20)->unique();
            $table->unsignedBigInteger('branch_id')->default(1);
            $table->string('ip_address', 45)->nullable();
            $table->enum('status', ['available', 'in_use', 'offline'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_activity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pc_units');
    }
};

