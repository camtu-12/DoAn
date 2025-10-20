<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LichThiSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        for ($i = 1; $i <= 20; $i++) {
            DB::table('lich_this')->insert([
                'Mon_Hoc' => $faker->randomElement([
                    'Cơ sở dữ liệu',
                    'Lập trình web',
                    'Mạng máy tính',
                    'Trí tuệ nhân tạo',
                    'Phân tích hệ thống',
                    'An toàn thông tin',
                    'Cấu trúc dữ liệu',
                    'Hệ điều hành',
                    'Lập trình hướng đối tượng',
                    'Công nghệ phần mềm',
                ]),
                'Ngay_Thi' => $faker->dateTimeBetween('2025-11-01', '2025-12-30')->format('Y-m-d'),
                'Gio_Bat_Dau' => $faker->randomElement(['07:30:00', '08:00:00', '13:00:00', '14:00:00']),
                'Gio_Ket_Thuc' => $faker->randomElement(['09:30:00', '10:00:00', '15:00:00', '16:00:00']),
                'So_Phong' => $faker->numberBetween(1, 10),
                'Ghi_Chu' => $faker->optional()->sentence(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
