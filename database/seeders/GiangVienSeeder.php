<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GiangVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN'); // Vietnamese locale
        $lecturers = [];

        for ($i = 1; $i <= 50; $i++) {
            $lecturers[] = [
                'MaGV' => 'GV' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'Ho_va_Ten' => $faker->name(),
                'Email' => 'gv' . $i . '@hcmus.edu.vn',
                'Sdt' => $faker->numerify('09########'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('giang_viens')->insert($lecturers);
    }
}
