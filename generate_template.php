<?php

/**
 * 📝 Generate Excel Template cho Import Lịch Thi
 */

require __DIR__.'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set title
$sheet->setTitle('Lịch Thi Template');

// Headers
$headers = [
    'A1' => 'Môn học',
    'B1' => 'Ngày thi',
    'C1' => 'Giờ bắt đầu',
    'D1' => 'Giờ kết thúc',
    'E1' => 'Số phòng',
    'F1' => 'Danh sách sinh viên',
    'G1' => 'Danh sách giảng viên',
    'H1' => 'Ghi chú',
];

// Set headers
foreach ($headers as $cell => $value) {
    $sheet->setCellValue($cell, $value);
}

// Style headers
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
        'size' => 12,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4472C4'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
];

$sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

// Set column widths
$sheet->getColumnDimension('A')->setWidth(30); // Môn học
$sheet->getColumnDimension('B')->setWidth(15); // Ngày thi
$sheet->getColumnDimension('C')->setWidth(12); // Giờ bắt đầu
$sheet->getColumnDimension('D')->setWidth(12); // Giờ kết thúc
$sheet->getColumnDimension('E')->setWidth(10); // Số phòng
$sheet->getColumnDimension('F')->setWidth(40); // DSSV
$sheet->getColumnDimension('G')->setWidth(40); // DSGV
$sheet->getColumnDimension('H')->setWidth(25); // Ghi chú

// Example data (2 rows)
$exampleData = [
    [
        'Lập trình Web với Laravel',
        '2025-12-25',
        '08:00',
        '10:00',
        '1',
        "2021CNTT001\n2021CNTT002\n2021CNTT003",
        "GV001\nGV002",
        'Ca sáng - Phòng A1',
    ],
    [
        'Cơ sở dữ liệu',
        '2025-12-26',
        '13:00',
        '15:00',
        '2',
        "Nguyễn Văn A, Trần Thị B, Lê Văn C",
        "Hoa Triệu, Nhậm Tuấn",
        'Ca chiều - Phòng B2',
    ],
];

$row = 2;
foreach ($exampleData as $data) {
    $col = 'A';
    foreach ($data as $value) {
        $sheet->setCellValue($col . $row, $value);
        
        // Enable text wrap for DSSV and DSGV columns
        if ($col === 'F' || $col === 'G') {
            $sheet->getStyle($col . $row)->getAlignment()->setWrapText(true);
        }
        
        $col++;
    }
    
    // Set row height to accommodate wrapped text
    $sheet->getRowDimension($row)->setRowHeight(60);
    
    $row++;
}

// Style data rows
$dataStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => 'CCCCCC'],
        ],
    ],
    'alignment' => [
        'vertical' => Alignment::VERTICAL_TOP,
    ],
];

$sheet->getStyle('A2:H' . ($row - 1))->applyFromArray($dataStyle);

// Add instruction sheet
$instructionSheet = $spreadsheet->createSheet();
$instructionSheet->setTitle('Hướng dẫn');

$instructions = [
    ['HƯỚNG DẪN IMPORT LỊCH THI'],
    [''],
    ['1. CẤU TRÚC FILE:'],
    ['   - Mỗi dòng = 1 lịch thi'],
    ['   - Không được xóa dòng tiêu đề (dòng 1)'],
    ['   - Mã môn thi (MaMT) sẽ tự động tạo'],
    [''],
    ['2. CÁC CỘT:'],
    ['   A. Môn học (bắt buộc)'],
    ['   B. Ngày thi (format: YYYY-MM-DD hoặc DD/MM/YYYY)'],
    ['   C. Giờ bắt đầu (format: HH:MM, VD: 08:00)'],
    ['   D. Giờ kết thúc (format: HH:MM, VD: 10:00)'],
    ['   E. Số phòng (ID phòng thi)'],
    ['   F. Danh sách sinh viên'],
    ['   G. Danh sách giảng viên'],
    ['   H. Ghi chú'],
    [''],
    ['3. DANH SÁCH SINH VIÊN (cột F):'],
    ['   - Có thể nhập MSSV hoặc Tên sinh viên'],
    ['   - Cách nhau bởi:'],
    ['     + Dấu phẩy: 2021CNTT001, 2021CNTT002'],
    ['     + Xuống dòng (nhấn Alt+Enter trong Excel)'],
    ['     + Kết hợp cả hai'],
    ['   - VD 1: 2021CNTT001, 2021CNTT002, 2021CNTT003'],
    ['   - VD 2: Nguyễn Văn A, Trần Thị B'],
    [''],
    ['4. DANH SÁCH GIẢNG VIÊN (cột G):'],
    ['   - Có thể nhập Mã GV hoặc Tên giảng viên'],
    ['   - Cách nhau bởi dấu phẩy hoặc xuống dòng'],
    ['   - VD 1: GV001, GV002'],
    ['   - VD 2: Hoa Triệu, Nhậm Tuấn'],
    [''],
    ['5. LƯU Ý:'],
    ['   - Nếu nhập tên, hệ thống sẽ tự động tìm MSSV/MaGV tương ứng'],
    ['   - Tên không cần chính xác 100%, hệ thống sẽ tìm gần đúng'],
    ['   - Nếu không tìm thấy, sinh viên/giảng viên đó sẽ bị bỏ qua'],
    [''],
    ['6. SAU KHI ĐIỀN XONG:'],
    ['   - Lưu file (Ctrl+S)'],
    ['   - Vào trang web → Lịch gác thi → Thêm file'],
    ['   - Kéo thả hoặc chọn file để upload'],
];

$instructionRow = 1;
foreach ($instructions as $instruction) {
    $instructionSheet->setCellValue('A' . $instructionRow, $instruction[0]);
    $instructionRow++;
}

// Style instruction title
$instructionSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$instructionSheet->getColumnDimension('A')->setWidth(70);

// Save file
$outputPath = __DIR__ . '/public/templates/';
if (!is_dir($outputPath)) {
    mkdir($outputPath, 0777, true);
}

$filename = 'lich_thi_template.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($outputPath . $filename);

echo "✅ Đã tạo file template: {$outputPath}{$filename}\n";
echo "📊 Gồm 2 sheets:\n";
echo "   1. Lịch Thi Template (với 2 ví dụ)\n";
echo "   2. Hướng dẫn (chi tiết cách sử dụng)\n";
