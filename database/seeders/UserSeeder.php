<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [];

        // 1 Admin
        $users[] = [
            'Mssv' => 'ADMIN001',
            'Ho_va_Ten' => 'Quản Trị Viên',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'),
            'role' => 'Admin',
            'lop' => null,  // Thêm cột lop
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 10 Giảng viên
        for ($i = 1; $i <= 10; $i++) {
            $users[] = [
                'Mssv' => 'GV' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'Ho_va_Ten' => 'Giảng viên ' . $i,
                'email' => 'giangvien' . $i . '@example.com',
                'password' => Hash::make('123456'),
                'role' => 'GiangVien',
                'lop' => null,  // Thêm cột lop
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 50 Sinh viên
        $khoas = ['CNTT', 'KTPM', 'KHMT', 'HTTT', 'MMT'];
        $khoaHocs = ['2021', '2022', '2023', '2024'];

        for ($i = 1; $i <= 50; $i++) {
            $khoa = $khoas[array_rand($khoas)];
            $khoaHoc = $khoaHocs[array_rand($khoaHocs)];
            $mssv = $khoaHoc . $khoa . str_pad($i, 3, '0', STR_PAD_LEFT);

            $users[] = [
                'Mssv' => $mssv,
                'Ho_va_Ten' => 'Sinh viên ' . $i,
                'email' => 'sinhvien' . $i . '@example.com',
                'password' => Hash::make('123456'),
                'role' => 'SinhVien',
                'lop' => $khoa . $khoaHoc,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}
