<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhongThiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phongThis = [];

        // Tòa A - 20 phòng
        for ($i = 101; $i <= 120; $i++) {
            $phongThis[] = [
                'So_Phong' => 'A' . $i,
                'So_Luong_Ghe_Ngoi' => rand(30, 50),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Tòa B - 20 phòng
        for ($i = 201; $i <= 220; $i++) {
            $phongThis[] = [
                'So_Phong' => 'B' . $i,
                'So_Luong_Ghe_Ngoi' => rand(25, 45),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Tòa C - 15 phòng
        for ($i = 301; $i <= 315; $i++) {
            $phongThis[] = [
                'So_Phong' => 'C' . $i,
                'So_Luong_Ghe_Ngoi' => rand(40, 60),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('phong_this')->insert($phongThis);
    }
}
