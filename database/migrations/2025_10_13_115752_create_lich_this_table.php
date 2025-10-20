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
        Schema::create('lich_this', function (Blueprint $table) {
            $table->string('MaMT')->foreignId('MaMT')->constrained('')->cascadeOnDelete();
            $table->string('Mon_Hoc');
            $table->date('Ngay_Thi');
            $table->time('Gio_Bat_Dau');
            $table->time('Gio_Ket_Thuc');
            $table->foreignId('So_Phong')->nullable()->constrained('phong_this')->nullOnDelete();
            $table->string('Ghi_Chu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_this');
    }
};
