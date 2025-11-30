<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LichThi;
use Illuminate\Http\Request;

class LichThiController extends Controller
{
    public function index()
    {
        return response()->json(LichThi::all());
    }

    public function show($id)
    {
        return LichThi::with('sinhvien','giangvien','phongthi')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sinh_vien_id'=>'required|exists:sinhviens,id',
            'giang_vien_id'=>'required|exists:giang_viens,id',
            'phong_thi_id'=>'required|exists:phong_this,id',
            'ngay_gio'=>'required|date',
        ]);
        return LichThi::create($data);
    }

    public function update(Request $request,$id)
    {
        $lt = LichThi::findOrFail($id);
        $data = $request->validate([
            'sinh_vien_id'=>'sometimes|exists:sinhviens,id',
            'giang_vien_id'=>'sometimes|exists:giang_viens,id',
            'phong_thi_id'=>'sometimes|exists:phong_this,id',
            'ngay_gio'=>'sometimes|date',
        ]);
        $lt->update($data);
        return $lt;
    }

    public function destroy($id)
    {
        LichThi::findOrFail($id)->delete();
        return response()->json(['message'=>'Đã xóa lịch thi']);
    }


    public function addSchedule(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'Mon_Hoc' => 'required|string|max:255',
                'Ngay_Thi' => 'required|date',
                'Gio_Bat_Dau' => 'required',
                'Gio_Ket_Thuc' => 'required',
                'So_Phong' => 'required|exists:phong_this,id',
                'Ghi_Chu' => 'nullable|string',
                'DSSV' => 'nullable|string',
                'DSGV' => 'nullable|string',
            ]);

            // Auto-generate MaMT (MT0001, MT0002, ...)
            $lastLichThi = LichThi::orderBy('id', 'desc')->first();
            $nextNumber = $lastLichThi ? ($lastLichThi->id + 1) : 1;
            $validated['MaMT'] = 'MT' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Tạo lịch thi
            $schedule = LichThi::create([
                'MaMT' => $validated['MaMT'],
                'Mon_Hoc' => $validated['Mon_Hoc'],
                'Ngay_Thi' => $validated['Ngay_Thi'],
                'Gio_Bat_Dau' => $validated['Gio_Bat_Dau'],
                'Gio_Ket_Thuc' => $validated['Gio_Ket_Thuc'],
                'So_Phong' => $validated['So_Phong'],
                'Ghi_Chu' => $validated['Ghi_Chu'],
            ]);

            // Xử lý danh sách sinh viên
            if (!empty($validated['DSSV'])) {
                // Hỗ trợ cả dấu phẩy và xuống dòng
                $mssvArray = array_filter(
                    array_map('trim', preg_split('/[,\n]/', $validated['DSSV'])),
                    fn($mssv) => !empty($mssv)
                );

                // Thêm sinh viên vào bảng lich_thi_sinh_vien
                foreach ($mssvArray as $mssv) {
                    // Kiểm tra sinh viên có tồn tại không
                    $sinhVien = \App\Models\SinhVien::where('Mssv', $mssv)->first();
                    if ($sinhVien) {
                        \App\Models\LichThiSinhVien::create([
                            'lich_thi_id' => $schedule->id,
                            'mssv' => $mssv,
                            'da_diem_danh' => false,
                        ]);
                    }
                }
            }

            // Xử lý danh sách giảng viên (phân công giám thị)
            if (!empty($validated['DSGV'])) {
                // Hỗ trợ cả dấu phẩy và xuống dòng
                $magvArray = array_filter(
                    array_map('trim', preg_split('/[,\n]/', $validated['DSGV'])),
                    fn($magv) => !empty($magv)
                );

                foreach ($magvArray as $index => $magv) {
                    // Tìm giảng viên theo MaGV
                    $giangVien = \App\Models\GiangVien::where('MaGV', $magv)->first();
                    if ($giangVien) {
                        // Tạo phân công với status = 'pending' để giảng viên xác nhận
                        \App\Models\PhanCongGiamThi::create([
                            'exam_id' => $schedule->id,
                            'teacher_id' => $giangVien->id,
                            'phong_thi_id' => $validated['So_Phong'] ?? 1,
                            'role' => $index === 0 ? 'Trưởng phòng' : 'Giám thị',
                            'status' => 'pending', // Chờ giảng viên xác nhận
                        ]);

                        // Tạo tài khoản user cho giảng viên nếu chưa có
                        try {
                            $existingUser = \App\Models\User::where('email', $giangVien->Email)
                                ->orWhere('Mssv', $giangVien->MaGV)
                                ->first();
                            
                            if (!$existingUser && $giangVien->Email) {
                                \App\Models\User::create([
                                    'Mssv' => $giangVien->MaGV,
                                    'Ho_va_Ten' => $giangVien->Ho_va_Ten,
                                    'email' => $giangVien->Email,
                                    'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                                    'role' => 'GiangVien',
                                ]);
                            }
                        } catch (\Exception $e) {
                            // Bỏ qua lỗi duplicate key - user đã tồn tại
                            \Log::info("User already exists for teacher: " . $giangVien->MaGV);
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Thêm lịch thi thành công',
                'data' => $schedule
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm lịch thi: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateSchedule(Request $request, $id)
    {
        try {
            $schedule = LichThi::findOrFail($id);

            // Validate dữ liệu
            $validated = $request->validate([
                'MaMT' => 'sometimes|string|unique:lich_this,MaMT,' . $id,
                'Mon_Hoc' => 'sometimes|string',
                'Ngay_Thi' => 'sometimes|date',
                'Gio_Bat_Dau' => 'sometimes',
                'Gio_Ket_Thuc' => 'sometimes',
                'So_Phong' => 'nullable|exists:phong_this,id',
                'Ghi_Chu' => 'nullable|string',
                'DSSV' => 'nullable|string',
                'DSGV' => 'nullable|string',
            ]);

            // Cập nhật thông tin lịch thi
            $schedule->update([
                'MaMT' => $validated['MaMT'] ?? $schedule->MaMT,
                'Mon_Hoc' => $validated['Mon_Hoc'] ?? $schedule->Mon_Hoc,
                'Ngay_Thi' => $validated['Ngay_Thi'] ?? $schedule->Ngay_Thi,
                'Gio_Bat_Dau' => $validated['Gio_Bat_Dau'] ?? $schedule->Gio_Bat_Dau,
                'Gio_Ket_Thuc' => $validated['Gio_Ket_Thuc'] ?? $schedule->Gio_Ket_Thuc,
                'So_Phong' => $validated['So_Phong'] ?? $schedule->So_Phong,
                'Ghi_Chu' => $validated['Ghi_Chu'] ?? $schedule->Ghi_Chu,
            ]);

            // Cập nhật danh sách sinh viên nếu có thay đổi
            if (isset($validated['DSSV'])) {
                // Xóa danh sách cũ
                \App\Models\LichThiSinhVien::where('lich_thi_id', $schedule->id)->delete();

                // Thêm danh sách mới - Hỗ trợ cả dấu phẩy và xuống dòng
                $mssvArray = array_filter(
                    array_map('trim', preg_split('/[,\n]/', $validated['DSSV'])),
                    fn($mssv) => !empty($mssv)
                );

                foreach ($mssvArray as $mssv) {
                    $sinhVien = \App\Models\SinhVien::where('Mssv', $mssv)->first();
                    if ($sinhVien) {
                        \App\Models\LichThiSinhVien::create([
                            'lich_thi_id' => $schedule->id,
                            'mssv' => $mssv,
                            'da_diem_danh' => false,
                        ]);
                    }
                }
            }

            // Cập nhật danh sách giảng viên nếu có thay đổi
            if (isset($validated['DSGV'])) {
                // Xóa danh sách cũ
                \App\Models\PhanCongGiamThi::where('exam_id', $schedule->id)->delete();

                // Thêm danh sách mới - Hỗ trợ cả dấu phẩy và xuống dòng
                $magvArray = array_filter(
                    array_map('trim', preg_split('/[,\n]/', $validated['DSGV'])),
                    fn($magv) => !empty($magv)
                );

                foreach ($magvArray as $index => $magv) {
                    $giangVien = \App\Models\GiangVien::where('MaGV', $magv)->first();
                    if ($giangVien) {
                        \App\Models\PhanCongGiamThi::create([
                            'exam_id' => $schedule->id,
                            'teacher_id' => $giangVien->id,
                            'phong_thi_id' => $validated['So_Phong'] ?? $schedule->So_Phong,
                            'role' => $index === 0 ? 'Trưởng phòng' : 'Giám thị',
                            'status' => 'pending', // Chờ giảng viên xác nhận
                        ]);

                        // Tạo tài khoản user cho giảng viên nếu chưa có
                        try {
                            $existingUser = \App\Models\User::where('email', $giangVien->Email)
                                ->orWhere('Mssv', $giangVien->MaGV)
                                ->first();
                            
                            if (!$existingUser && $giangVien->Email) {
                                \App\Models\User::create([
                                    'Mssv' => $giangVien->MaGV,
                                    'Ho_va_Ten' => $giangVien->Ho_va_Ten,
                                    'email' => $giangVien->Email,
                                    'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                                    'role' => 'GiangVien',
                                ]);
                            }
                        } catch (\Exception $e) {
                            // Bỏ qua lỗi duplicate key - user đã tồn tại
                            \Log::info("User already exists for teacher: " . $giangVien->MaGV);
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật lịch thi thành công',
                'data' => $schedule
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lịch thi'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật lịch thi: ' . $e->getMessage()
            ], 500);
        }
    }
public function deleteSchedule($id)
{
    $schedule = LichThi::find($id);

    if (!$schedule) {
        return response()->json(['message' => 'Không tìm thấy lịch thi'], 404);
    }

    $schedule->delete();

    return response()->json(['message' => 'Đã xóa lịch thi thành công'], 200);
}

    /**
     * Download Excel template cho import lịch thi
     */
    public function downloadTemplate()
    {
        $templatePath = public_path('templates/lich_thi_template.xlsx');
        
        if (!file_exists($templatePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File template không tồn tại'
            ], 404);
        }

        return response()->download($templatePath, 'lich_thi_template.xlsx');
    }

    /**
     * Import lịch thi từ file Excel
     */
    public function importSchedules(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls|max:10240', // Max 10MB
            ]);

            $file = $request->file('file');
            
            // Load Excel file
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getSheetByName('Lịch Thi Template') ?? $spreadsheet->getActiveSheet();
            
            $rows = $sheet->toArray(null, true, true, true); // Get all rows with column letters as keys
            
            // Remove header row
            array_shift($rows);
            
            $imported = 0;
            $errors = [];
            $skipped = 0;

            foreach ($rows as $rowIndex => $row) {
                try {
                    // Skip empty rows
                    if (empty(trim($row['A'] ?? ''))) {
                        $skipped++;
                        continue;
                    }

                    // Parse date (support multiple formats)
                    $ngayThi = $row['B'] ?? '';
                    if (!empty($ngayThi)) {
                        // Try to parse as Excel date number
                        if (is_numeric($ngayThi)) {
                            $ngayThi = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($ngayThi)->format('Y-m-d');
                        } else {
                            // Try to parse as string date
                            try {
                                $ngayThi = \Carbon\Carbon::parse($ngayThi)->format('Y-m-d');
                            } catch (\Exception $e) {
                                throw new \Exception("Ngày thi không hợp lệ: {$ngayThi}");
                            }
                        }
                    }

                    // Auto-generate MaMT
                    $lastLichThi = LichThi::orderBy('id', 'desc')->first();
                    $nextNumber = $lastLichThi ? ($lastLichThi->id + 1) : 1;
                    $maMT = 'MT' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

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

                    // Parse DSSV (support comma and newline)
                    $dssvInput = $row['F'] ?? '';
                    if (!empty($dssvInput)) {
                        $dssvArray = array_filter(
                            array_map('trim', preg_split('/[,\n]/', $dssvInput)),
                            fn($s) => !empty($s)
                        );

                        foreach ($dssvArray as $item) {
                            $sinhVien = null;
                            
                            // Try to find by MSSV first
                            $sinhVien = \App\Models\SinhVien::where('Mssv', $item)->first();
                            
                            // If not found, try to find by name
                            if (!$sinhVien) {
                                $sinhVien = \App\Models\SinhVien::where('Ho_va_ten', 'like', '%' . $item . '%')->first();
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
                                }
                            }
                        }
                    }

                    // Parse DSGV (support comma and newline)
                    $dsgvInput = $row['G'] ?? '';
                    if (!empty($dsgvInput)) {
                        $dsgvArray = array_filter(
                            array_map('trim', preg_split('/[,\n]/', $dsgvInput)),
                            fn($s) => !empty($s)
                        );

                        foreach ($dsgvArray as $index => $item) {
                            $giangVien = null;
                            
                            // Try to find by MaGV first
                            $giangVien = \App\Models\GiangVien::where('MaGV', $item)->first();
                            
                            // If not found, try to find by name
                            if (!$giangVien) {
                                $giangVien = \App\Models\GiangVien::where('Ho_va_Ten', 'like', '%' . $item . '%')->first();
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
                                }
                            }
                        }
                    }

                    $imported++;

                } catch (\Exception $e) {
                    $errors[] = "Dòng {$rowIndex}: " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Import thành công {$imported} lịch thi",
                'imported' => $imported,
                'skipped' => $skipped,
                'errors' => $errors,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi import: ' . $e->getMessage()
            ], 500);
        }
    }

}
