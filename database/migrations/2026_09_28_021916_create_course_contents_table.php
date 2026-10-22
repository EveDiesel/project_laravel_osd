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
        Schema::create('course_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_level_id')->constrained('course_levels')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->integer('session');
            $table->string('content_type'); // kalau content type = url jadi isi contentnya adalah urlnya dan seterusnya
            $table->string('title');
            $table->text('content');
            $table->timestamps();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_contents');
    }
};
