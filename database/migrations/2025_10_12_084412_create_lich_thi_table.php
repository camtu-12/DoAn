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
        Schema::create('lich_thi', function (Blueprint $table) {
            $table->id();
            $table->string('Mon hoc');
            $table->date('Ngay thi');
            $table->time('Gio bat dau');
            $table->time('Gio ket thuc');
            $table->foreignId('So Phong')->nullable()->constrained('phong_thi')->nullOnDelete();
            $table->string('Ghi chu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_thi');
    }
};
