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
        Schema::create('hoc_sinhs', function (Blueprint $table) {
            $table->id();
            $table->string('Ho', 120);
            $table->string('Ten', 120);
            $table->string('email')->unique();; 
            $table->date('Ngay_Sinh')->nullable();
            $table->string('mssv')->unique();
            $table->string('Lop');
            $table->string('Khoa');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoc_sinhs');
    }
};
