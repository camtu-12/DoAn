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
        Schema::create('phanconggiamthis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('lich_thi')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('giang_viens')->onDelete('cascade');
            $table->string('role')->default('giám thị'); // ví dụ: giám thị, trưởng phòng
            $table->timestamps();
            $table->unique(['exam_id','teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phanconggiamthis');
    }
};
