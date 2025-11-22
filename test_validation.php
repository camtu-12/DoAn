<?php

/**
 * 🧪 TEST: Validation của addSchedule API
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  🧪 TEST VALIDATION - ADD SCHEDULE API                   ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Test case 1: Missing MaMT (should fail)
echo "TEST 1: Missing MaMT (should return 422)\n";
echo str_repeat("-", 60) . "\n";

$data1 = [
    'Mon_Hoc' => 'Test Subject',
    'Ngay_Thi' => '2025-12-25',
    'Gio_Bat_Dau' => '08:00:00',
    'Gio_Ket_Thuc' => '10:00:00',
    'So_Phong' => 1,
];

$validator1 = \Illuminate\Support\Facades\Validator::make($data1, [
    'MaMT' => 'required|string|unique:lich_this,MaMT',
    'Mon_Hoc' => 'required|string',
    'Ngay_Thi' => 'required|date',
    'Gio_Bat_Dau' => 'required',
    'Gio_Ket_Thuc' => 'required',
    'So_Phong' => 'nullable|exists:phong_this,id',
    'Ghi_Chu' => 'nullable|string',
    'DSSV' => 'nullable|string',
    'DSGV' => 'nullable|string',
]);

if ($validator1->fails()) {
    echo "❌ VALIDATION FAILED (as expected):\n";
    foreach ($validator1->errors()->all() as $error) {
        echo "  - {$error}\n";
    }
} else {
    echo "✅ Validation passed (unexpected!)\n";
}

echo "\n";

// Test case 2: Missing Gio_Ket_Thuc (should fail)
echo "TEST 2: Missing Gio_Ket_Thuc (should return 422)\n";
echo str_repeat("-", 60) . "\n";

$data2 = [
    'MaMT' => 'TEST_MT_' . time(),
    'Mon_Hoc' => 'Test Subject',
    'Ngay_Thi' => '2025-12-25',
    'Gio_Bat_Dau' => '08:00:00',
    'So_Phong' => 1,
];

$validator2 = \Illuminate\Support\Facades\Validator::make($data2, [
    'MaMT' => 'required|string|unique:lich_this,MaMT',
    'Mon_Hoc' => 'required|string',
    'Ngay_Thi' => 'required|date',
    'Gio_Bat_Dau' => 'required',
    'Gio_Ket_Thuc' => 'required',
    'So_Phong' => 'nullable|exists:phong_this,id',
    'Ghi_Chu' => 'nullable|string',
    'DSSV' => 'nullable|string',
    'DSGV' => 'nullable|string',
]);

if ($validator2->fails()) {
    echo "❌ VALIDATION FAILED (as expected):\n";
    foreach ($validator2->errors()->all() as $error) {
        echo "  - {$error}\n";
    }
} else {
    echo "✅ Validation passed (unexpected!)\n";
}

echo "\n";

// Test case 3: All required fields present (should pass)
echo "TEST 3: All required fields present (should pass)\n";
echo str_repeat("-", 60) . "\n";

$data3 = [
    'MaMT' => 'TEST_MT_' . time(),
    'Mon_Hoc' => 'Test Subject',
    'Ngay_Thi' => '2025-12-25',
    'Gio_Bat_Dau' => '08:00:00',
    'Gio_Ket_Thuc' => '10:00:00',
    'So_Phong' => 1,
    'Ghi_Chu' => 'Test note',
    'DSSV' => '2021CNTT001,2021CNTT002',
    'DSGV' => 'GV001,GV002',
];

$validator3 = \Illuminate\Support\Facades\Validator::make($data3, [
    'MaMT' => 'required|string|unique:lich_this,MaMT',
    'Mon_Hoc' => 'required|string',
    'Ngay_Thi' => 'required|date',
    'Gio_Bat_Dau' => 'required',
    'Gio_Ket_Thuc' => 'required',
    'So_Phong' => 'nullable|exists:phong_this,id',
    'Ghi_Chu' => 'nullable|string',
    'DSSV' => 'nullable|string',
    'DSGV' => 'nullable|string',
]);

if ($validator3->fails()) {
    echo "❌ VALIDATION FAILED (unexpected!):\n";
    foreach ($validator3->errors()->all() as $error) {
        echo "  - {$error}\n";
    }
} else {
    echo "✅ VALIDATION PASSED (as expected)\n";
    echo "Data:\n";
    foreach ($data3 as $key => $value) {
        echo "  - {$key}: {$value}\n";
    }
}

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  ✅ VALIDATION TESTS COMPLETED                           ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";

echo "\n📋 REQUIRED FIELDS:\n";
echo "  ✅ MaMT (Mã môn thi) - required, unique\n";
echo "  ✅ Mon_Hoc (Môn học) - required\n";
echo "  ✅ Ngay_Thi (Ngày thi) - required, date format\n";
echo "  ✅ Gio_Bat_Dau (Giờ bắt đầu) - required\n";
echo "  ✅ Gio_Ket_Thuc (Giờ kết thúc) - required\n";

echo "\n📋 OPTIONAL FIELDS:\n";
echo "  ⭕ So_Phong (Số phòng) - must exist in phong_this table\n";
echo "  ⭕ Ghi_Chu (Ghi chú)\n";
echo "  ⭕ DSSV (Danh sách sinh viên)\n";
echo "  ⭕ DSGV (Danh sách giảng viên)\n";
