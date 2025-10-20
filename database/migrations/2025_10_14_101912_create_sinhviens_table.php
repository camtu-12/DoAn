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
        Schema::create('sinhviens', function (Blueprint $table) {
            
            $table->string('Ho_va_ten', 120);
            $table->string('Email',191)->unique();; 
            $table->date('Ngay_Sinh')->nullable();
            $table->string('Mssv')->primary()->unique();
            $table->string('Lop');
            $table->string('Khoa');
            $table->string('Photo')->nullable();
            $table->boolean('KQDD')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sinhviens');
    }
};
