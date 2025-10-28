<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SinhVien;
use Illuminate\Http\Request;

class SinhVienController extends Controller
{
    // GET /sinhviens
    public function index()
    {
        return response()->json(SinhVien::all());
    }

    // GET /sinhviens/{id}
    public function show($id)
    {
        return SinhVien::findOrFail($id); // Trả về 1 sinh viên
    }

    // POST /sinhviens
    public function store(Request $request)
    {
        $data = $request->validate([
            'ho' => 'required|string',
            'ten' => 'required|string',
            'mssv' => 'required|string|unique:sinhviens,mssv',
            'lop' => 'required|string',
        ]);

        return SinhVien::create($data);
    }

    // PUT /sinhviens/{id}
    public function update(Request $request, $id)
    {
        $sv = SinhVien::findOrFail($id);

        $data = $request->validate([
            'hovaten' => 'sometimes|string',
            'mssv' => 'sometimes|string|unique:sinhviens,mssv,'.$id,
            'lop' => 'sometimes|string',
        ]);

        $sv->update($data);

        return $sv;
    }

    // DELETE /sinhviens/{id}
    public function destroy($id)
    {
        $sv = SinhVien::findOrFail($id);
        $sv->delete();

        return response()->json(['message' => 'Đã xóa sinh viên']);
    }
// use App\Models\SinhVien; use App\Models\User;

public function info(Request $request)
{
    $user = $request->user();

    $sinhvien = \App\Models\SinhVien::where('Email', $user->email)->first();

    if (!$sinhvien) {
        return response()->json(['message' => 'Không tìm thấy sinh viên'], 404);
    }

    return response()->json([
        'ho' => $sinhvien->Ho,
        'ten' => $sinhvien->Ten,
        'email' => $sinhvien->Email,
        'ngay_sinh' => $sinhvien->Ngay_Sinh,
        'mssv' => $sinhvien->Mssv,
        'lop' => $sinhvien->Lop,
        'khoa' => $sinhvien->Khoa,
        'photo' => $sinhvien->Photo,
    ]);
}


public function attendance() {
    return response()->json([
        ['monhoc' => 'Lập trình Web', 'trangthai' => 'Có mặt'],
        ['monhoc' => 'CSDL', 'trangthai' => 'Vắng'],
    ]);
}

public function examSchedule() {
    return response()->json([
        ['monhoc' => 'Lập trình Web', 'ngay' => '2025-10-25', 'phong' => 'B201'],
        ['monhoc' => 'CSDL', 'ngay' => '2025-10-30', 'phong' => 'C103'],
    ]);
}

  
    /**
     * Xóa sinh viên bởi id (dùng bởi frontend Admin)
     */
    public function deleteStudent($id)
    {
        // Nếu $id là số (primary key), thử find theo PK
        $sv = null;
        if (is_numeric($id)) {
            $sv = SinhVien::find($id);
        }

        // Nếu chưa tìm được, thử dò theo các cột MSSV/Mssv/mssv phổ biến
        if (! $sv) {
            $sv = SinhVien::where('Mssv', $id)
                ->orWhere('MSSV', $id)
                ->orWhere('mssv', $id)
                ->orWhere('MSSV', '=', $id) // defensive duplicate won't hurt
                ->first();
        }

        if (! $sv) {
            return response()->json(['message' => 'Không tìm thấy sinh viên'], 404);
        }

        try {
            $sv->delete();
            return response()->json(['message' => 'Đã xóa sinh viên'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Xóa thất bại', 'error' => $e->getMessage()], 500);
        }
    }

    /**
         * Thêm sinh viên mới (từ Admin UI)
         */
        public function addStudent(Request $request)
        {
            $data = $request->validate([
                'Ho_va_ten' => 'required|string',
                'Email' => 'nullable|email',
                'Ngay_Sinh' => 'nullable|date',
                'Mssv' => 'required|string|unique:sinhviens,Mssv',
                'Lop' => 'nullable|string',
                'Khoa' => 'nullable|string',
                'Photo' => 'nullable|string',
            ]);

            try {
                $sv = SinhVien::create($data);
                return response()->json($sv, 201);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Tạo sinh viên thất bại', 'error' => $e->getMessage()], 500);
            }
        }

        /**
         * Cập nhật sinh viên (tìm theo khóa chính Mssv)
         */
        public function updateStudent(Request $request, $id)
        {
            // vì model đã thiết lập primaryKey = Mssv nên find sẽ tìm theo Mssv
            $sv = SinhVien::find($id);
            if (! $sv) {
                return response()->json(['message' => 'Không tìm thấy sinh viên'], 404);
            }

            $data = $request->validate([
                'Ho_va_ten' => 'sometimes|string',
                'Email' => 'sometimes|nullable|email',
                'Ngay_Sinh' => 'sometimes|nullable|date',
                'Mssv' => 'sometimes|string|unique:sinhviens,Mssv,'.$id.',Mssv',
                'Lop' => 'sometimes|nullable|string',
                'Khoa' => 'sometimes|nullable|string',
                'Photo' => 'sometimes|nullable|string',
            ]);

            try {
                $sv->update($data);
                return response()->json($sv, 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Cập nhật thất bại', 'error' => $e->getMessage()], 500);
            }
        }

}
