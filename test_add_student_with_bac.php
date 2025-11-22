<?php

/**
 * 🧪 TEST: Thêm sinh viên với field Bac
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SinhVien;

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  🧪 TEST: THÊM SINH VIÊN VỚI FIELD BAC                   ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Test data giống như frontend sẽ gửi
$testData = [
    'Mssv' => 'TEST_' . time(),
    'Ho_va_ten' => 'Nguyễn Văn Test',
    'Ngay_Sinh' => '2000-01-01',
    'Lop' => 'CNTT2021',
    'Khoa' => 'Công nghệ thông tin',
    'Bac' => 'Đại học', // ← Field quan trọng
];

echo "📝 Dữ liệu test:\n";
echo json_encode($testData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n";

try {
    // Simulate validation như trong controller
    echo "🔍 Kiểm tra validation...\n";
    
    $validator = Illuminate\Support\Facades\Validator::make($testData, [
        'Ho_va_ten' => 'required|string',
        'Email' => 'nullable|email',
        'Ngay_Sinh' => 'nullable|date',
        'Mssv' => 'required|string|unique:sinhviens,Mssv',
        'Lop' => 'nullable|string',
        'Khoa' => 'nullable|string',
        'Bac' => 'nullable|string', // ← Validation cho Bac
        'Photo' => 'nullable|string',
    ]);
    
    if ($validator->fails()) {
        echo "❌ Validation failed:\n";
        print_r($validator->errors()->all());
        exit(1);
    }
    
    echo "✅ Validation passed\n\n";
    
    // Tạo sinh viên
    echo "💾 Tạo sinh viên...\n";
    $sv = SinhVien::create($testData);
    
    echo "✅ Đã tạo sinh viên:\n";
    echo "  - MSSV: {$sv->Mssv}\n";
    echo "  - Họ tên: {$sv->Ho_va_ten}\n";
    echo "  - Lớp: {$sv->Lop}\n";
    echo "  - Khoa: {$sv->Khoa}\n";
    echo "  - Bậc: {$sv->Bac}\n";
    echo "  - Ngày sinh: {$sv->Ngay_Sinh}\n\n";
    
    // Kiểm tra trong database
    echo "🔍 Kiểm tra trong database...\n";
    $check = SinhVien::find($sv->Mssv);
    
    if ($check && $check->Bac === $testData['Bac']) {
        echo "✅ Field Bac được lưu đúng: '{$check->Bac}'\n\n";
    } else {
        echo "❌ Field Bac KHÔNG được lưu hoặc sai giá trị!\n";
        echo "  Expected: '{$testData['Bac']}'\n";
        echo "  Got: '{$check->Bac}'\n\n";
        exit(1);
    }
    
    // Test update
    echo "🔄 Test update sinh viên...\n";
    $updateData = ['Bac' => 'Thạc sĩ'];
    
    $validator2 = Illuminate\Support\Facades\Validator::make($updateData, [
        'Ho_va_ten' => 'sometimes|string',
        'Email' => 'sometimes|nullable|email',
        'Ngay_Sinh' => 'sometimes|nullable|date',
        'Mssv' => 'sometimes|string|unique:sinhviens,Mssv,'.$sv->Mssv.',Mssv',
        'Lop' => 'sometimes|nullable|string',
        'Khoa' => 'sometimes|nullable|string',
        'Bac' => 'sometimes|nullable|string', // ← Validation cho Bac trong update
        'Photo' => 'sometimes|nullable|string',
    ]);
    
    if ($validator2->fails()) {
        echo "❌ Update validation failed:\n";
        print_r($validator2->errors()->all());
        exit(1);
    }
    
    $sv->update($updateData);
    $sv->refresh();
    
    if ($sv->Bac === 'Thạc sĩ') {
        echo "✅ Update field Bac thành công: '{$sv->Bac}'\n\n";
    } else {
        echo "❌ Update field Bac FAILED!\n\n";
        exit(1);
    }
    
    // Cleanup
    echo "🗑️  Cleanup test data...\n";
    $sv->delete();
    echo "✅ Đã xóa test record\n\n";
    
    echo "╔════════════════════════════════════════════════════════════╗\n";
    echo "║  ✅ ALL TESTS PASSED!                                    ║\n";
    echo "╚════════════════════════════════════════════════════════════╝\n\n";
    
    echo "🎯 KẾT QUẢ:\n";
    echo "  ✅ Field Bac được thêm vào validation\n";
    echo "  ✅ Tạo sinh viên với Bac thành công\n";
    echo "  ✅ Update Bac thành công\n";
    echo "  ✅ Dữ liệu được lưu đúng vào database\n";
    echo "\n🚀 Chức năng thêm sinh viên hoạt động hoàn hảo!\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
