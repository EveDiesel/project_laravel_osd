<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('name');
            $table->integer('level');
            $table->integer('duration');
            $table->integer('order_index');
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

//  mwah i lop yu, yu lop mi

    public function down(): void
    {
        Schema::dropIfExists('course_levels');
    }
};