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
        Schema::create('giang_viens', function (Blueprint $table) {
            $table->id(); 
            $table->string('Ho', 120);
            $table->string('Ten', 120);
            $table->string('Email')->unique()->nullable(); 
            $table->string('Sdt')->nullable();
            $table->string('Bo_Mon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giang_viens');
    }
};
