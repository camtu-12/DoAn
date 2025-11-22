<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class sinhviens extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        for ($i = 1; $i <= 30; $i++) {
            DB::table('sinhviens')->insert([
                'Ho_va_ten' => $faker->name(),
                'Email' => $faker->unique()->safeEmail(),
                'Ngay_Sinh' => $faker->date('Y-m-d', '2005-12-31'),
                'Mssv' => 'SV' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'Lop' => 'CNTT' . rand(1, 5),
                'Khoa' => $faker->randomElement(['Công nghệ thông tin', 'Kinh tế', 'Ngôn ngữ Anh', 'Điện tử viễn thông']),
                'Photo' => 'uploads/sinhvien/photo' . $i . '.jpg',
                'KQDD' => $faker->boolean(80), // 80% có điểm danh
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
