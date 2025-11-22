<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SinhVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $sinhViens = [];

        $khoas = ['CNTT', 'KTPM', 'KHMT', 'HTTT', 'MMT'];
        $khoaHocs = ['2021', '2022', '2023', '2024'];

        for ($i = 1; $i <= 100; $i++) {
            $khoa = $khoas[array_rand($khoas)];
            $khoaHoc = $khoaHocs[array_rand($khoaHocs)];
            $mssv = $khoaHoc . $khoa . str_pad($i, 3, '0', STR_PAD_LEFT);

            $sinhViens[] = [
                'Mssv' => $mssv,
                'Ho_va_ten' => $faker->name(),
                'Email' => strtolower($mssv) . '@student.hcmus.edu.vn',
                'Ngay_Sinh' => $faker->date('Y-m-d', '2004-12-31'),
                'Lop' => $khoa . $khoaHoc,
                'Khoa' => $khoa,
                'Bac' => 'Đại học',
                'KQDD' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('sinhviens')->insert($sinhViens);
    }
}
