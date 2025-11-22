<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhanCongGiamThiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lichThis = DB::table('lich_this')->get();
        $giangViens = DB::table('giang_viens')->get();

        if ($lichThis->isEmpty() || $giangViens->isEmpty()) {
            echo "Không có dữ liệu lịch thi hoặc giảng viên để phân công!\n";
            return;
        }

        $phanCongs = [];
        $usedCombos = [];

        foreach ($lichThis as $lichThi) {
            // Mỗi ca thi cần 2 giảng viên giám thị
            $soGiamThi = min(2, $giangViens->count());
            $selectedGVs = $giangViens->random($soGiamThi);
            
            foreach ($selectedGVs as $index => $gv) {
                // Kiểm tra unique constraint
                $comboKey = $lichThi->id . '-' . $gv->id;
                
                if (!in_array($comboKey, $usedCombos)) {
                    $phanCongs[] = [
                        'exam_id' => $lichThi->id,
                        'teacher_id' => $gv->id,
                        'phong_thi_id' => $lichThi->So_Phong,
                        'role' => $index === 0 ? 'Trưởng phòng' : 'Giám thị',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    
                    $usedCombos[] = $comboKey;
                }
            }
        }

        if (!empty($phanCongs)) {
            // Insert theo batch để tránh lỗi
            foreach (array_chunk($phanCongs, 50) as $chunk) {
                DB::table('phanconggiamthis')->insert($chunk);
            }
            echo "Đã phân công " . count($phanCongs) . " lượt giám thị!\n";
        }
    }
}
