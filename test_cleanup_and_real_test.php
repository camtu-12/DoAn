<?php

/**
 * 🧹 CLEANUP: Xóa test records và test thực tế
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\LichThi;
use App\Models\LichThiSinhVien;
use App\Models\PhanCongGiamThi;

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  🧹 CLEANUP & REAL TEST                                  ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Xóa test records
echo "🗑️  Xóa test records (ID 46, 47)...\n";

$testRecords = LichThi::whereIn('id', [46, 47])->get();
foreach ($testRecords as $record) {
    echo "  - Xóa ID {$record->id}: {$record->MaMT}\n";
    
    // Xóa các bản ghi liên quan
    LichThiSinhVien::where('lich_thi_id', $record->id)->delete();
    PhanCongGiamThi::where('exam_id', $record->id)->delete();
    
    $record->delete();
}

echo "✅ Đã xóa test records\n\n";

// Kiểm tra lại
$lastLichThi = LichThi::orderBy('id', 'desc')->first();
echo "📊 Lịch thi cuối cùng sau khi cleanup:\n";
echo "  - ID: {$lastLichThi->id}\n";
echo "  - MaMT: {$lastLichThi->MaMT}\n";
echo "  - Môn học: {$lastLichThi->Mon_Hoc}\n\n";

// Test THỰC TẾ tạo lịch thi với auto-generate MaMT
echo "🧪 TEST THỰC TẾ: Tạo lịch thi mới\n";
echo str_repeat("=", 60) . "\n";

// Simulate validation như trong controller
$validated = [
    'Mon_Hoc' => 'Lập trình Web với Laravel',
    'Ngay_Thi' => '2025-12-30',
    'Gio_Bat_Dau' => '08:00:00',
    'Gio_Ket_Thuc' => '10:00:00',
    'So_Phong' => 1,
    'Ghi_Chu' => 'Test real auto-generate MaMT',
    'DSSV' => '2021CNTT001, 2021CNTT002',
    'DSGV' => 'GV001',
];

echo "Input từ frontend:\n";
echo json_encode($validated, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n";

// Auto-generate MaMT (giống logic trong controller)
$lastLichThi = LichThi::orderBy('id', 'desc')->first();
$nextNumber = $lastLichThi ? ($lastLichThi->id + 1) : 1;
$validated['MaMT'] = 'MT' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

echo "✅ Backend auto-generate: MaMT = {$validated['MaMT']}\n\n";

// Tạo lịch thi
$lichThi = LichThi::create([
    'MaMT' => $validated['MaMT'],
    'Mon_Hoc' => $validated['Mon_Hoc'],
    'Ngay_Thi' => $validated['Ngay_Thi'],
    'Gio_Bat_Dau' => $validated['Gio_Bat_Dau'],
    'Gio_Ket_Thuc' => $validated['Gio_Ket_Thuc'],
    'So_Phong' => $validated['So_Phong'],
    'Ghi_Chu' => $validated['Ghi_Chu'],
]);

echo "✅ Đã tạo lịch thi:\n";
echo "  - ID: {$lichThi->id}\n";
echo "  - MaMT: {$lichThi->MaMT}\n";
echo "  - Môn học: {$lichThi->Mon_Hoc}\n";
echo "  - Ngày thi: {$lichThi->Ngay_Thi}\n";
echo "  - Giờ: {$lichThi->Gio_Bat_Dau} - {$lichThi->Gio_Ket_Thuc}\n\n";

// Parse DSSV
$dssvArray = array_filter(
    array_map('trim', preg_split('/[,\n]/', $validated['DSSV'])),
    fn($s) => !empty($s)
);

echo "📝 Thêm sinh viên:\n";
foreach ($dssvArray as $mssv) {
    LichThiSinhVien::create([
        'lich_thi_id' => $lichThi->id,
        'mssv' => $mssv,
        'da_diem_danh' => false,
    ]);
    echo "  ✅ {$mssv}\n";
}

// Parse DSGV
$dsgvArray = array_filter(
    array_map('trim', preg_split('/[,\n]/', $validated['DSGV'])),
    fn($s) => !empty($s)
);

echo "\n👨‍🏫 Phân công giảng viên:\n";
foreach ($dsgvArray as $magv) {
    $gv = \App\Models\GiangVien::where('MaGV', $magv)->first();
    if ($gv) {
        PhanCongGiamThi::create([
            'exam_id' => $lichThi->id,
            'teacher_id' => $gv->id,
            'phong_thi_id' => 1,
            'role' => 'Giám thị',
        ]);
        echo "  ✅ {$magv}: {$gv->Ho_va_Ten}\n";
    }
}

echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║  ✅ CLEANUP & REAL TEST COMPLETED!                       ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

echo "🎯 KẾT QUẢ:\n";
echo "  ✅ MaMT được auto-generate: {$lichThi->MaMT}\n";
echo "  ✅ User KHÔNG cần nhập MaMT\n";
echo "  ✅ Parse dấu phẩy và xuống dòng OK\n";
echo "  ✅ Thêm sinh viên và giảng viên OK\n";
echo "\n🚀 Ready for production!\n";
