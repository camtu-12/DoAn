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
        Schema::create('lich_thi_sinh_vien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lich_thi_id')->constrained('lich_this')->onDelete('cascade');
            $table->string('mssv')->constrained('sinhviens', 'Mssv')->onDelete('cascade');
            $table->boolean('da_diem_danh')->default(false);
            $table->timestamp('thoi_gian_diem_danh')->nullable();
            $table->enum('phuong_thuc_diem_danh', ['qr_code', 'manual', 'face_recognition'])->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
            
            // Đảm bảo một sinh viên chỉ xuất hiện 1 lần trong 1 lịch thi
            $table->unique(['lich_thi_id', 'mssv']);
            
            // Index để tăng tốc độ truy vấn
            $table->index('lich_thi_id');
            $table->index('mssv');
            $table->index('da_diem_danh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_thi_sinh_vien');
    }
};
