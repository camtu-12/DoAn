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
        Schema::table('phanconggiamthis', function (Blueprint $table) {
            // Thêm cột status để giảng viên xác nhận/từ chối lịch gác
            $table->enum('status', ['pending', 'confirmed', 'rejected'])
                  ->default('pending')
                  ->after('role')
                  ->comment('Trạng thái xác nhận: pending (chờ), confirmed (đã xác nhận), rejected (từ chối)');
            
            // Thêm timestamp để theo dõi thời gian xác nhận/từ chối
            $table->timestamp('confirmed_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phanconggiamthis', function (Blueprint $table) {
            $table->dropColumn(['status', 'confirmed_at']);
        });
    }
};
