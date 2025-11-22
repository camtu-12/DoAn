<?php

/**
 * Script test relationships giữa các bảng
 * Chạy: php test_relationships.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\LichThi;
use App\Models\SinhVien;
use App\Models\GiangVien;

echo "==========================================\n";
echo "TEST RELATIONSHIPS - LỊCH THI SINH VIÊN\n";
echo "==========================================\n\n";

// Test 1: Lấy một lịch thi và xem danh sách sinh viên
echo "📋 TEST 1: Lấy lịch thi và danh sách sinh viên\n";
echo str_repeat("-", 50) . "\n";

$lichThi = LichThi::first();
if ($lichThi) {
    echo "Lịch thi: {$lichThi->Mon_Hoc}\n";
    echo "Mã môn thi: {$lichThi->MaMT}\n";
    echo "Ngày thi: {$lichThi->Ngay_Thi}\n";
    echo "Giờ: {$lichThi->Gio_Bat_Dau} - {$lichThi->Gio_Ket_Thuc}\n\n";
    
    $sinhViens = $lichThi->sinhViens;
    echo "Số sinh viên tham gia: " . $sinhViens->count() . "\n";
    
    if ($sinhViens->count() > 0) {
        echo "\n5 sinh viên đầu tiên:\n";
        foreach ($sinhViens->take(5) as $sv) {
            $diemDanh = $sv->pivot->da_diem_danh ? '✅ Đã điểm danh' : '❌ Chưa điểm danh';
            echo "  - {$sv->Mssv}: {$sv->Ho_va_ten} ({$sv->Lop}) - {$diemDanh}\n";
        }
    }
} else {
    echo "⚠️ Không có lịch thi nào!\n";
}

echo "\n" . str_repeat("=", 50) . "\n\n";

// Test 2: Lấy một sinh viên và xem lịch thi của sinh viên đó
echo "👨‍🎓 TEST 2: Lấy sinh viên và lịch thi của sinh viên\n";
echo str_repeat("-", 50) . "\n";

$sinhVien = SinhVien::first();
if ($sinhVien) {
    echo "Sinh viên: {$sinhVien->Ho_va_ten}\n";
    echo "MSSV: {$sinhVien->Mssv}\n";
    echo "Lớp: {$sinhVien->Lop}\n\n";
    
    $lichThis = $sinhVien->lichThis;
    echo "Số lịch thi: " . $lichThis->count() . "\n";
    
    if ($lichThis->count() > 0) {
        echo "\n3 lịch thi đầu tiên:\n";
        foreach ($lichThis->take(3) as $lt) {
            $diemDanh = $lt->pivot->da_diem_danh ? '✅' : '❌';
            echo "  {$diemDanh} {$lt->Mon_Hoc} - {$lt->Ngay_Thi} ({$lt->Gio_Bat_Dau})\n";
        }
    }
} else {
    echo "⚠️ Không có sinh viên nào!\n";
}

echo "\n" . str_repeat("=", 50) . "\n\n";

// Test 3: Lấy lịch thi với giảng viên giám thị
echo "👨‍🏫 TEST 3: Lấy lịch thi và giảng viên giám thị\n";
echo str_repeat("-", 50) . "\n";

$lichThi2 = LichThi::with('giangViens')->first();
if ($lichThi2) {
    echo "Lịch thi: {$lichThi2->Mon_Hoc}\n";
    echo "Giảng viên giám thị:\n";
    
    foreach ($lichThi2->giangViens as $gv) {
        $role = $gv->pivot->role ?? 'Giám thị';
        echo "  - {$gv->MaGV}: {$gv->Ho_va_Ten} ({$role})\n";
    }
}

echo "\n" . str_repeat("=", 50) . "\n\n";

// Test 4: Thống kê tổng quan
echo "📊 TEST 4: Thống kê tổng quan\n";
echo str_repeat("-", 50) . "\n";

$totalLichThi = LichThi::count();
$totalSinhVien = SinhVien::count();
$totalGiangVien = GiangVien::count();
$totalAssignments = \DB::table('lich_thi_sinh_vien')->count();
$totalPhanCong = \DB::table('phanconggiamthis')->count();

echo "Tổng số lịch thi: {$totalLichThi}\n";
echo "Tổng số sinh viên: {$totalSinhVien}\n";
echo "Tổng số giảng viên: {$totalGiangVien}\n";
echo "Tổng lượt sinh viên đăng ký thi: {$totalAssignments}\n";
echo "Tổng lượt phân công giám thị: {$totalPhanCong}\n";
echo "Trung bình sinh viên/lịch thi: " . round($totalAssignments / $totalLichThi, 1) . "\n";

echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ TEST HOÀN TẤT!\n";
echo str_repeat("=", 50) . "\n";
