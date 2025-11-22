<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Thứ tự quan trọng: phải seed bảng cha trước bảng con
        $this->call([
            PhongThiSeeder::class,          // Bảng phong_this
            GiangVienSeeder::class,         // Bảng giang_viens
            SinhVienSeeder::class,          // Bảng sinhviens
            LichThiSeeder::class,           // Bảng lich_this (cần phong_this)
            PhanCongGiamThiSeeder::class,   // Bảng phanconggiamthis (cần lich_this, giang_viens)
            LichThiSinhVienSeeder::class,   // Bảng lich_thi_sinh_vien (cần lich_this, sinhviens)
            UserSeeder::class,              // Bảng users
        ]);

        $this->command->info('✅ Đã seed thành công:');
        $this->command->info('   - Phòng thi');
        $this->command->info('   - 50 giảng viên');
        $this->command->info('   - 100 sinh viên');
        $this->command->info('   - Lịch thi');
        $this->command->info('   - Phân công giám thị');
        $this->command->info('   - Phân bổ sinh viên vào lịch thi');
        $this->command->info('   - Users (Admin + Giảng viên + Sinh viên)');
    }
}
