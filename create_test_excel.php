<?php

/**
 * 🧪 Tạo file Excel test để import
 */

require __DIR__.'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Lịch Thi Template');

// Headers
$headers = ['Môn học', 'Ngày thi', 'Giờ bắt đầu', 'Giờ kết thúc', 'Số phòng', 'Danh sách sinh viên', 'Danh sách giảng viên', 'Ghi chú'];
$sheet->fromArray([$headers], null, 'A1');

// Test data với nhiều case khác nhau
$testData = [
    // Case 1: MSSV và MaGV (dấu phẩy)
    [
        'Cơ sở dữ liệu nâng cao',
        '2025-12-28',
        '08:00',
        '10:00',
        '1',
        '2021CNTT001, 2021CNTT002, 2021CNTT017',
        'GV001, GV002',
        'Test case 1: MSSV + MaGV với dấu phẩy',
    ],
    
    // Case 2: Tên sinh viên và tên giảng viên (dấu phẩy)
    [
        'Lập trình di động',
        '2025-12-29',
        '13:00',
        '15:00',
        '2',
        'Tòng Hảo, Thạch Nguyệt, Đinh Đạt Kiện',
        'Hoa Triệu, Nhậm Tuấn',
        'Test case 2: Tên SV + Tên GV với dấu phẩy',
    ],
    
    // Case 3: Mixed MSSV và Tên (xuống dòng)
    [
        'Trí tuệ nhân tạo',
        '2025-12-30',
        '08:00',
        '10:00',
        '3',
        "2021CNTT001\nTòng Hảo\n2021CNTT019",
        "GV001\nHoa Triệu",
        'Test case 3: Mixed MSSV/Tên với xuống dòng',
    ],
    
    // Case 4: Kết hợp dấu phẩy và xuống dòng
    [
        'An toàn thông tin',
        '2025-12-31',
        '13:00',
        '15:00',
        '1',
        "2021CNTT001, 2021CNTT002\nThạch Nguyệt\n2021CNTT044",
        "GV001, GV002\nChị. Cam Miên",
        'Test case 4: Kết hợp dấu phẩy + xuống dòng',
    ],
];

$sheet->fromArray($testData, null, 'A2');

// Enable text wrap for DSSV and DSGV columns
for ($row = 2; $row <= count($testData) + 1; $row++) {
    $sheet->getStyle('F' . $row)->getAlignment()->setWrapText(true);
    $sheet->getStyle('G' . $row)->getAlignment()->setWrapText(true);
    $sheet->getRowDimension($row)->setRowHeight(60);
}

// Set column widths
$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(12);
$sheet->getColumnDimension('D')->setWidth(12);
$sheet->getColumnDimension('E')->setWidth(10);
$sheet->getColumnDimension('F')->setWidth(35);
$sheet->getColumnDimension('G')->setWidth(35);
$sheet->getColumnDimension('H')->setWidth(40);

// Save file
$outputPath = __DIR__ . '/public/templates/';
$filename = 'test_import_lich_thi.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($outputPath . $filename);

echo "✅ Đã tạo file test: {$outputPath}{$filename}\n\n";
echo "📋 Các test case:\n";
echo "  1. MSSV + MaGV với dấu phẩy\n";
echo "  2. Tên SV + Tên GV với dấu phẩy\n";
echo "  3. Mixed MSSV/Tên với xuống dòng\n";
echo "  4. Kết hợp dấu phẩy + xuống dòng\n\n";
echo "🧪 Sử dụng file này để test import!\n";
