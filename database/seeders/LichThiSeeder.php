<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LichThiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(LichThiSeeder::class);

        $lichThis = [];

        for ($i = 1; $i <= 10; $i++) {
            $ngayThi = Carbon::now()->addDays(rand(1, 30));

            $lichThis[] = [
                'STT' => $i,
                'Thu' => $ngayThi->dayOfWeek == 0 ? 'Chủ nhật' : 'Thứ ' . ($ngayThi->dayOfWeek + 1),
                'Ngay_Thi' => $ngayThi->toDateString(),
                'Gio_Bat_Dau' => rand(7, 14) . ':00',
                'Mon_Hoc' => 'Môn học ' . $i,
                'So_Phong' => 'P' . rand(101, 305),
                'DSSV' => 'SV00' . rand(1, 50) . ', SV00' . rand(51, 100),
                'DSGV' => 'GV' . rand(1, 10),
                'Ghi_Chu' => rand(0, 1) ? 'Mang theo thẻ sinh viên' : 'Có thể thi online',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('lich_this')->insert($lichThis);
    }
}
