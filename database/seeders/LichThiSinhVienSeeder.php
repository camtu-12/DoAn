<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\LichThi;
use App\Models\SinhVien;

class LichThiSinhVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Phân bổ sinh viên vào các lịch thi một cách ngẫu nhiên
     */
    public function run(): void
    {
        $lichThis = LichThi::all();
        $sinhViens = SinhVien::all();

        if ($lichThis->isEmpty()) {
            echo "⚠️  Không có lịch thi nào trong database. Vui lòng chạy LichThiSeeder trước!\n";
            return;
        }

        if ($sinhViens->isEmpty()) {
            echo "⚠️  Không có sinh viên nào trong database. Vui lòng chạy SinhVienSeeder trước!\n";
            return;
        }

        echo "📚 Bắt đầu phân bổ sinh viên vào lịch thi...\n";
        
        $assignments = [];
        $totalAssignments = 0;

        foreach ($lichThis as $lichThi) {
            // Mỗi lịch thi có từ 20-40 sinh viên tham gia
            $soSinhVienThamGia = rand(20, min(40, $sinhViens->count()));
            
            // Chọn ngẫu nhiên sinh viên
            $selectedSinhViens = $sinhViens->random($soSinhVienThamGia);
            
            foreach ($selectedSinhViens as $sinhVien) {
                $assignments[] = [
                    'lich_thi_id' => $lichThi->id,
                    'mssv' => $sinhVien->Mssv,
                    'da_diem_danh' => false,
                    'thoi_gian_diem_danh' => null,
                    'phuong_thuc_diem_danh' => null,
                    'ghi_chu' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $totalAssignments++;
            }
        }

        // Insert theo batch để tăng hiệu suất
        if (!empty($assignments)) {
            foreach (array_chunk($assignments, 100) as $chunk) {
                try {
                    DB::table('lich_thi_sinh_vien')->insert($chunk);
                } catch (\Exception $e) {
                    // Bỏ qua lỗi duplicate nếu có
                    if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                        echo "⚠️  Lỗi khi insert batch: " . $e->getMessage() . "\n";
                    }
                }
            }
        }

        echo "✅ Đã phân bổ {$totalAssignments} sinh viên vào " . $lichThis->count() . " lịch thi!\n";
        echo "📊 Trung bình mỗi lịch thi có " . round($totalAssignments / $lichThis->count()) . " sinh viên\n";
    }
}
