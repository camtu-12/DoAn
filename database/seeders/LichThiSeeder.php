<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LichThi;
use App\Models\PhongThi;
use Carbon\Carbon;

class LichThiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $danhSachMon = [
            ['code' => 'CNTT101', 'name' => 'Nhập môn lập trình'],
            ['code' => 'CNTT102', 'name' => 'Cấu trúc dữ liệu và giải thuật'],
            ['code' => 'CNTT201', 'name' => 'Lập trình hướng đối tượng'],
            ['code' => 'CNTT202', 'name' => 'Cơ sở dữ liệu'],
            ['code' => 'CNTT301', 'name' => 'Mạng máy tính'],
            ['code' => 'CNTT302', 'name' => 'Hệ điều hành'],
            ['code' => 'CNTT401', 'name' => 'Công nghệ phần mềm'],
            ['code' => 'CNTT402', 'name' => 'Trí tuệ nhân tạo'],
            ['code' => 'TOAN101', 'name' => 'Toán cao cấp A1'],
            ['code' => 'TOAN102', 'name' => 'Toán rời rạc'],
            ['code' => 'TOAN201', 'name' => 'Xác suất thống kê'],
            ['code' => 'ANH101', 'name' => 'Tiếng Anh 1'],
            ['code' => 'ANH102', 'name' => 'Tiếng Anh 2'],
            ['code' => 'KHMT101', 'name' => 'Kiến trúc máy tính'],
            ['code' => 'KHMT201', 'name' => 'Đồ họa máy tính'],
        ];

        $phongThis = PhongThi::all();
        $ngayBatDau = Carbon::parse('2025-12-15');
        
        $caThis = [
            ['start' => '07:00:00', 'end' => '09:00:00', 'name' => 'Ca 1'],
            ['start' => '09:30:00', 'end' => '11:30:00', 'name' => 'Ca 2'],
            ['start' => '13:00:00', 'end' => '15:00:00', 'name' => 'Ca 3'],
            ['start' => '15:30:00', 'end' => '17:30:00', 'name' => 'Ca 4'],
        ];

        $counter = 1;
        
        // Tạo lịch thi cho 15 ngày, mỗi ngày 4 ca thi
        for ($day = 0; $day < 15; $day++) {
            $ngayThi = $ngayBatDau->copy()->addDays($day);
            
            foreach ($caThis as $ca) {
                // Mỗi ca thi có 3-5 môn thi
                $soMonTrongCa = rand(3, 5);
                
                for ($mon = 0; $mon < $soMonTrongCa && $counter <= count($danhSachMon) * 3; $mon++) {
                    $monHoc = $danhSachMon[($counter - 1) % count($danhSachMon)];
                    
                    LichThi::create([
                        'MaMT' => 'MT' . str_pad($counter, 4, '0', STR_PAD_LEFT),
                        'Mon_Hoc' => $monHoc['name'],
                        'Ngay_Thi' => $ngayThi->format('Y-m-d'),
                        'Gio_Bat_Dau' => $ca['start'],
                        'Gio_Ket_Thuc' => $ca['end'],
                        'So_Phong' => $phongThis->random()->id,
                        'Ghi_Chu' => $ca['name'] . ' - ' . $monHoc['code'],
                    ]);
                    
                    $counter++;
                }
            }
        }
    }
}
