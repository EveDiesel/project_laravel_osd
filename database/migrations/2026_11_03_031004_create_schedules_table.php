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

        if (!Schema::hasTable('schedules')) {
                Schema::create('schedules', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade'); // Tambahkan ini
                    $table->timestamp('start_at');
                    $table->timestamp('end_at');
                    $table->enum('status', ['active', 'inactive'])->default('active');
                    $table->timestamps();
                });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
