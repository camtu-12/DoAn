<?php

/**
 * Script test thêm lịch thi với danh sách sinh viên và giảng viên
 * Chạy: php test_add_schedule.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\LichThi;
use App\Models\SinhVien;
use App\Models\GiangVien;
use App\Models\LichThiSinhVien;
use App\Models\PhanCongGiamThi;

echo "==========================================\n";
echo "TEST THÊM LỊCH THI VỚI DANH SÁCH\n";
echo "==========================================\n\n";

// Test data
$testData = [
    'MaMT' => 'MT_TEST_' . time(),
    'Mon_Hoc' => 'Test Môn Học',
    'Ngay_Thi' => '2025-12-20',
    'Gio_Bat_Dau' => '08:00:00',
    'Gio_Ket_Thuc' => '10:00:00',
    'So_Phong' => 1,
    'Ghi_Chu' => 'Test lịch thi',
];

// Lấy 5 sinh viên ngẫu nhiên
$sinhViens = SinhVien::inRandomOrder()->limit(5)->get();
$dssvString = $sinhViens->pluck('Mssv')->join(',');

// Lấy 2 giảng viên ngẫu nhiên
$giangViens = GiangVien::inRandomOrder()->limit(2)->get();
$dsgvString = $giangViens->pluck('MaGV')->join(',');

echo "📝 Dữ liệu test:\n";
echo "Mã môn thi: {$testData['MaMT']}\n";
echo "Môn học: {$testData['Mon_Hoc']}\n";
echo "Ngày thi: {$testData['Ngay_Thi']}\n";
echo "Giờ: {$testData['Gio_Bat_Dau']} - {$testData['Gio_Ket_Thuc']}\n\n";

echo "👨‍🎓 Danh sách sinh viên:\n";
echo $dssvString . "\n";
echo "Số lượng: " . $sinhViens->count() . "\n\n";

echo "👨‍🏫 Danh sách giảng viên:\n";
echo $dsgvString . "\n";
echo "Số lượng: " . $giangViens->count() . "\n\n";

echo str_repeat("-", 50) . "\n";
echo "🔄 Đang tạo lịch thi...\n";

try {
    // Tạo lịch thi
    $lichThi = LichThi::create($testData);
    echo "✅ Đã tạo lịch thi ID: {$lichThi->id}\n\n";

    // Thêm sinh viên
    echo "🔄 Đang thêm sinh viên vào lịch thi...\n";
    $mssvArray = array_filter(
        array_map('trim', explode(',', $dssvString)),
        fn($mssv) => !empty($mssv)
    );

    $countSV = 0;
    foreach ($mssvArray as $mssv) {
        $sv = SinhVien::where('Mssv', $mssv)->first();
        if ($sv) {
            LichThiSinhVien::create([
                'lich_thi_id' => $lichThi->id,
                'mssv' => $mssv,
                'da_diem_danh' => false,
            ]);
            $countSV++;
            echo "  ✓ Thêm SV: {$mssv} - {$sv->Ho_va_ten}\n";
        }
    }
    echo "✅ Đã thêm {$countSV} sinh viên\n\n";

    // Thêm giảng viên
    echo "🔄 Đang phân công giảng viên giám thị...\n";
    $magvArray = array_filter(
        array_map('trim', explode(',', $dsgvString)),
        fn($magv) => !empty($magv)
    );

    $countGV = 0;
    foreach ($magvArray as $index => $magv) {
        $gv = GiangVien::where('MaGV', $magv)->first();
        if ($gv) {
            PhanCongGiamThi::create([
                'exam_id' => $lichThi->id,
                'teacher_id' => $gv->id,
                'phong_thi_id' => $testData['So_Phong'],
                'role' => $index === 0 ? 'Trưởng phòng' : 'Giám thị',
            ]);
            $countGV++;
            $role = $index === 0 ? 'Trưởng phòng' : 'Giám thị';
            echo "  ✓ Phân công: {$magv} - {$gv->Ho_va_Ten} ({$role})\n";
        }
    }
    echo "✅ Đã phân công {$countGV} giảng viên\n\n";

    echo str_repeat("=", 50) . "\n";
    echo "🎉 HOÀN TẤT!\n";
    echo str_repeat("=", 50) . "\n\n";

    // Kiểm tra lại
    echo "📊 KIỂM TRA DỮ LIỆU:\n";
    echo str_repeat("-", 50) . "\n";
    
    $lichThiCheck = LichThi::with(['sinhViens', 'giangViens'])->find($lichThi->id);
    echo "Lịch thi: {$lichThiCheck->Mon_Hoc}\n";
    echo "Số sinh viên: " . $lichThiCheck->sinhViens->count() . "\n";
    echo "Số giảng viên: " . $lichThiCheck->giangViens->count() . "\n\n";

    echo "Danh sách MSSV (từ DB):\n";
    $dssvFromDB = $lichThiCheck->sinhViens->pluck('Mssv')->join(',');
    echo $dssvFromDB . "\n\n";

    echo "Danh sách Mã GV (từ DB):\n";
    $dsgvFromDB = $lichThiCheck->giangViens->pluck('MaGV')->join(',');
    echo $dsgvFromDB . "\n\n";

    if ($dssvFromDB === $dssvString && $dsgvFromDB === $dsgvString) {
        echo "✅ DỮ LIỆU KHỚP HOÀN TOÀN!\n";
    } else {
        echo "⚠️ Có sự khác biệt trong dữ liệu!\n";
    }

} catch (\Exception $e) {
    echo "❌ LỖI: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "TEST HOÀN TẤT!\n";
echo str_repeat("=", 50) . "\n";
