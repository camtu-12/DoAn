<?php

/**
 * 🧪 TEST: Import lịch thi từ Excel
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\LichThi;
use App\Models\SinhVien;
use App\Models\GiangVien;

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  🧪 TEST: IMPORT LỊCH THI TỪ EXCEL                      ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Đếm số lịch thi trước khi import
$countBefore = LichThi::count();
echo "📊 Số lịch thi trước import: {$countBefore}\n\n";

// Simulate file upload (direct call to controller logic)
$filePath = __DIR__ . '/public/templates/test_import_lich_thi.xlsx';

if (!file_exists($filePath)) {
    echo "❌ File test không tồn tại: {$filePath}\n";
    exit(1);
}

echo "📁 File test: {$filePath}\n";
echo "📏 Kích thước: " . round(filesize($filePath) / 1024, 2) . " KB\n\n";

try {
    // Load Excel file
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $sheet = $spreadsheet->getSheetByName('Lịch Thi Template') ?? $spreadsheet->getActiveSheet();
    
    $rows = $sheet->toArray(null, true, true, true);
    
    // Remove header
    array_shift($rows);
    
    echo "📋 Tổng số dòng: " . count($rows) . "\n\n";
    echo str_repeat("=", 60) . "\n";
    
    $imported = 0;
    $errors = [];
    $skipped = 0;

    foreach ($rows as $rowIndex => $row) {
        echo "\n🔄 Xử lý dòng {$rowIndex}...\n";
        echo "  Môn học: {$row['A']}\n";
        
        try {
            if (empty(trim($row['A'] ?? ''))) {
                echo "  ⚠️ Dòng trống - bỏ qua\n";
                $skipped++;
                continue;
            }

            // Parse date
            $ngayThi = $row['B'] ?? '';
            if (!empty($ngayThi)) {
                if (is_numeric($ngayThi)) {
                    $ngayThi = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($ngayThi)->format('Y-m-d');
                } else {
                    $ngayThi = \Carbon\Carbon::parse($ngayThi)->format('Y-m-d');
                }
            }
            echo "  Ngày thi: {$ngayThi}\n";

            // Auto-generate MaMT
            $lastLichThi = LichThi::orderBy('id', 'desc')->first();
            $nextNumber = $lastLichThi ? ($lastLichThi->id + 1) : 1;
            $maMT = 'MT' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            echo "  MaMT: {$maMT}\n";

            // Create lịch thi
            $lichThi = LichThi::create([
                'MaMT' => $maMT,
                'Mon_Hoc' => $row['A'] ?? '',
                'Ngay_Thi' => $ngayThi,
                'Gio_Bat_Dau' => $row['C'] ?? '08:00:00',
                'Gio_Ket_Thuc' => $row['D'] ?? '10:00:00',
                'So_Phong' => $row['E'] ?? 1,
                'Ghi_Chu' => $row['H'] ?? '',
            ]);

            echo "  ✅ Đã tạo lịch thi ID: {$lichThi->id}\n";

            // Parse DSSV
            $dssvInput = $row['F'] ?? '';
            if (!empty($dssvInput)) {
                echo "  📝 DSSV input: " . substr($dssvInput, 0, 50) . "...\n";
                
                $dssvArray = array_filter(
                    array_map('trim', preg_split('/[,\n]/', $dssvInput)),
                    fn($s) => !empty($s)
                );

                echo "  📊 Parsed " . count($dssvArray) . " items\n";
                
                $svCount = 0;
                foreach ($dssvArray as $item) {
                    $sinhVien = SinhVien::where('Mssv', $item)->first();
                    
                    if (!$sinhVien) {
                        $sinhVien = SinhVien::where('Ho_va_ten', 'like', '%' . $item . '%')->first();
                    }

                    if ($sinhVien) {
                        // Check if already exists to avoid duplicate
                        $exists = \App\Models\LichThiSinhVien::where('lich_thi_id', $lichThi->id)
                            ->where('mssv', $sinhVien->Mssv)
                            ->exists();
                        
                        if (!$exists) {
                            \App\Models\LichThiSinhVien::create([
                                'lich_thi_id' => $lichThi->id,
                                'mssv' => $sinhVien->Mssv,
                                'da_diem_danh' => false,
                            ]);
                            $svCount++;
                        }
                    } else {
                        echo "    ⚠️ Không tìm thấy SV: {$item}\n";
                    }
                }
                echo "  ✅ Đã thêm {$svCount} sinh viên\n";
            }

            // Parse DSGV
            $dsgvInput = $row['G'] ?? '';
            if (!empty($dsgvInput)) {
                echo "  👨‍🏫 DSGV input: " . substr($dsgvInput, 0, 50) . "...\n";
                
                $dsgvArray = array_filter(
                    array_map('trim', preg_split('/[,\n]/', $dsgvInput)),
                    fn($s) => !empty($s)
                );

                echo "  📊 Parsed " . count($dsgvArray) . " items\n";
                
                $gvCount = 0;
                foreach ($dsgvArray as $index => $item) {
                    $giangVien = GiangVien::where('MaGV', $item)->first();
                    
                    if (!$giangVien) {
                        $giangVien = GiangVien::where('Ho_va_Ten', 'like', '%' . $item . '%')->first();
                    }

                    if ($giangVien) {
                        // Check if already exists to avoid duplicate
                        $exists = \App\Models\PhanCongGiamThi::where('exam_id', $lichThi->id)
                            ->where('teacher_id', $giangVien->id)
                            ->exists();
                        
                        if (!$exists) {
                            \App\Models\PhanCongGiamThi::create([
                                'exam_id' => $lichThi->id,
                                'teacher_id' => $giangVien->id,
                                'phong_thi_id' => $row['E'] ?? 1,
                                'role' => $index === 0 ? 'Trưởng phòng' : 'Giám thị',
                            ]);
                            $gvCount++;
                        }
                    } else {
                        echo "    ⚠️ Không tìm thấy GV: {$item}\n";
                    }
                }
                echo "  ✅ Đã phân công {$gvCount} giảng viên\n";
            }

            $imported++;

        } catch (\Exception $e) {
            $errors[] = "Dòng {$rowIndex}: " . $e->getMessage();
            echo "  ❌ Lỗi: " . $e->getMessage() . "\n";
        }
    }

    echo "\n" . str_repeat("=", 60) . "\n";
    echo "\n📊 KẾT QUẢ:\n";
    echo "  ✅ Import thành công: {$imported} lịch thi\n";
    echo "  ⚠️ Bỏ qua: {$skipped} dòng\n";
    echo "  ❌ Lỗi: " . count($errors) . "\n";

    if (count($errors) > 0) {
        echo "\n❌ CHI TIẾT LỖI:\n";
        foreach ($errors as $error) {
            echo "  - {$error}\n";
        }
    }

    $countAfter = LichThi::count();
    echo "\n📊 Số lịch thi sau import: {$countAfter}\n";
    echo "📈 Tăng: " . ($countAfter - $countBefore) . "\n";

    echo "\n╔════════════════════════════════════════════════════════════╗\n";
    echo "║  ✅ TEST COMPLETED!                                      ║\n";
    echo "╚════════════════════════════════════════════════════════════╝\n";

} catch (\Exception $e) {
    echo "❌ CRITICAL ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
