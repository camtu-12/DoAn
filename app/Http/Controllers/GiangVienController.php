<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GiangVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Container\Attributes\Auth;


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GiangVien; // model của bảng giảng viên


class GiangVienController extends Controller
{
    public function index()
    {
        return response()->json(GiangVien::all());
    }
    public function show($id) { return GiangVien::findOrFail($id); }
    public function store(Request $request)
    {
        $data = $request->validate([
            'ho'=>'required|string',
            'ten'=>'required|string',
            'email'=>'required|email|unique:giang_viens,email',
        ]);
        return GiangVien::create($data);
    }
    public function update(Request $request, $id)
    {
        $gv = GiangVien::findOrFail($id);
        $data = $request->validate([
            'ho'=>'sometimes|string',
            'ten'=>'sometimes|string',
            'email'=>'sometimes|email|unique:giang_viens,email,'.$id,
        ]);
        $gv->update($data);
        return $gv;
    }
    public function destroy($id)
    {
        GiangVien::findOrFail($id)->delete();
        return response()->json(['message'=>'Đã xóa giảng viên']);
    }

    public function showDoiMatKhau()
    {
        return inertia('GiangVien/DoiMatKhau');
    }

    public function updateMatKhau(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Mật khẩu cũ không đúng!']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
     public function thongTin(Request $request)
    {
        $user = $request->user();

        // nếu bạn có quan hệ user->teacher hoặc user->giangvien, dùng nó:
        $teacher = $user->teacher ?? $user;

        // map các field bạn muốn trả về
        return response()->json([
            'Ten'     => $teacher->Ho_va_Ten ?? $teacher->name ?? $user->name,
            'Email'   => $teacher->Email ?? $teacher->Email ?? $user->email,
            'Sdt'     => $teacher->Sdt ?? $teacher->Sdt ?? 'Không có',
            'Bo_Mon'  => $teacher->Bo_Mon ?? $teacher->Bo_Mon ?? 'Không có',
        ]);
    }





public function lichGac(Request $request)
{
    $user = $request->user();
    
    \Log::info('lichGac called for user:', [
        'user_id' => $user->id,
        'email' => $user->email,
        'role' => $user->role
    ]);

    // Tìm giảng viên theo email hoặc Mssv (MaGV)
    $gv = GiangVien::where('Email', $user->email)
        ->orWhere('MaGV', $user->Mssv)
        ->first();

    if (!$gv) {
        \Log::warning('Teacher not found for user:', [
            'email' => $user->email,
            'Mssv' => $user->Mssv
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy thông tin giảng viên'
        ], 404);
    }
    
    \Log::info('Found teacher:', ['teacher_id' => $gv->id, 'MaGV' => $gv->MaGV]);

    // Lấy tham số status từ request (mặc định lấy tất cả)
    $status = $request->input('status', 'all');

    // Query phân công giám thị
    $query = \App\Models\PhanCongGiamThi::with(['lichThi', 'phongThi'])
        ->where('teacher_id', $gv->id);

    // Filter theo status
    if ($status !== 'all') {
        $query->where('status', $status);
    }

    $phanCongList = $query->orderBy('created_at', 'desc')->get();
    
    \Log::info('Found assignments:', ['count' => $phanCongList->count()]);

    // Map dữ liệu để trả về frontend
    $data = $phanCongList->map(function ($pc) {
        $lichThi = $pc->lichThi;
        $phongThi = $pc->phongThi;

        // Đếm số lượng sinh viên đã điểm danh
        $attendedCount = \App\Models\LichThiSinhVien::where('lich_thi_id', $pc->exam_id)
            ->where('da_diem_danh', true)
            ->count();
        
        $totalStudents = \App\Models\LichThiSinhVien::where('lich_thi_id', $pc->exam_id)
            ->count();

        return [
            'id' => $pc->id,
            'exam_id' => $pc->exam_id,
            'mon_hoc' => $lichThi->Mon_Hoc ?? '',
            'ma_mon' => $lichThi->MaMT ?? '',
            'ngay_thi' => $lichThi->Ngay_Thi ?? '',
            'thu' => $lichThi->Ngay_Thi ? \Carbon\Carbon::parse($lichThi->Ngay_Thi)->locale('vi')->dayName : '',
            'gio_bat_dau' => $lichThi->Gio_Bat_Dau ?? '',
            'gio_ket_thuc' => $lichThi->Gio_Ket_Thuc ?? '',
            'so_phong' => $phongThi->So_Phong ?? '',
            'toa_nha' => $phongThi->Toa_Nha ?? '',
            'role' => $pc->role,
            'status' => $pc->status,
            'confirmed_at' => $pc->confirmed_at ? $pc->confirmed_at->format('Y-m-d H:i:s') : null,
            'ghi_chu' => $lichThi->Ghi_Chu ?? '',
            'attended_count' => $attendedCount,
            'total_students' => $totalStudents,
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $data
    ], 200);
}




public function ketQua(Request $request)
{
    $user = $request->user();
    
    \Log::info('ketQua called for user:', [
        'user_id' => $user->id,
        'email' => $user->email
    ]);

    // Tìm giảng viên theo email hoặc Mssv
    $gv = GiangVien::where('Email', $user->email)
        ->orWhere('MaGV', $user->Mssv)
        ->first();

    if (!$gv) {
        \Log::warning('Teacher not found in ketQua for user:', ['email' => $user->email]);
        return response()->json([], 200);
    }

    // TODO: Implement kết quả điểm danh
    // Hiện tại trả về mảng rỗng
    return response()->json([], 200);
}
public function addLecturer(Request $request) {
        $themgv = GiangVien::create($request->all());
        return response()->json($themgv, 201);
    }
public function deleteLecturer($id) {
        $gv = GiangVien::find($id);
        if (!$gv) {
            return response()->json(['message' => 'Giảng viên không tồn tại'], 404);
        }
        $gv->delete();
        return response()->json(['message' => 'Đã xóa giảng viên'], 200);
    }

public function updateLecturer(Request $request, $id) {
        $gv = GiangVien::find($id);
        if (!$gv) {
            return response()->json(['message' => 'Giảng viên không tồn tại'], 404);
        }
        $gv->update($request->all());
        return response()->json($gv, 200);
    }

    /**
     * Xác nhận lịch gác thi
     */
    public function confirmAssignment($id)
    {
        try {
            \Log::info("confirmAssignment called", ['id' => $id, 'user_id' => auth()->id()]);
            
            $phanCong = \App\Models\PhanCongGiamThi::findOrFail($id);
            \Log::info("Found phanCong", ['phanCong' => $phanCong]);
            
            // Tạm thời bỏ kiểm tra quyền để debug
            $phanCong->update([
                'status' => 'confirmed',
                'confirmed_at' => now()
            ]);

            \Log::info("Updated successfully");

            return response()->json([
                'success' => true,
                'message' => 'Đã xác nhận lịch gác thi thành công',
                'data' => $phanCong
            ], 200);

        } catch (\Exception $e) {
            \Log::error("Error in confirmAssignment", ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xác nhận: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Từ chối lịch gác thi
     */
    public function rejectAssignment(Request $request, $id)
    {
        try {
            $phanCong = \App\Models\PhanCongGiamThi::findOrFail($id);
            
            // Tạm thời bỏ kiểm tra quyền để debug
            $lyDo = $request->input('ly_do', null);
            
            $phanCong->update([
                'status' => 'rejected',
                'confirmed_at' => now(),
                'ghi_chu' => $lyDo
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã từ chối lịch gác thi',
                'data' => $phanCong
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi từ chối: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách lịch gác chờ xác nhận
     */
    public function getPendingAssignments(Request $request)
    {
        try {
            $user = $request->user();
            $giangVien = GiangVien::where('Email', $user->email)->first();

            if (!$giangVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin giảng viên'
                ], 404);
            }

            // Lấy danh sách lịch gác pending
            $pendingAssignments = \App\Models\PhanCongGiamThi::with(['lichThi', 'phongThi'])
                ->where('teacher_id', $giangVien->id)
                ->where('status', 'pending')
                ->get()
                ->map(function ($pc) {
                    return [
                        'id' => $pc->id,
                        'exam_id' => $pc->exam_id,
                        'mon_hoc' => $pc->lichThi->Mon_Hoc ?? '',
                        'ngay_thi' => $pc->lichThi->Ngay_Thi ?? '',
                        'gio_bat_dau' => $pc->lichThi->Gio_Bat_Dau ?? '',
                        'gio_ket_thuc' => $pc->lichThi->Gio_Ket_Thuc ?? '',
                        'so_phong' => $pc->phongThi->So_Phong ?? '',
                        'toa_nha' => $pc->phongThi->Toa_Nha ?? '',
                        'role' => $pc->role,
                        'status' => $pc->status,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $pendingAssignments
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin sinh viên theo MSSV
     */
    public function getSinhVienByMssv($mssv)
    {
        try {
            $sinhVien = \App\Models\SinhVien::where('Mssv', $mssv)->first();

            if (!$sinhVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sinh viên'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'student' => $sinhVien
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Điểm danh sinh viên
     */
    public function diemDanh(Request $request)
    {
        try {
            $validated = $request->validate([
                'lich_thi_id' => 'required|exists:lich_this,id',
                'mssv' => 'required|exists:sinhviens,Mssv',
                'phuong_thuc' => 'required|in:qr_code,manual'
            ]);

            // Kiểm tra sinh viên có trong danh sách thi không
            $lichThiSinhVien = \App\Models\LichThiSinhVien::where('lich_thi_id', $validated['lich_thi_id'])
                ->where('mssv', $validated['mssv'])
                ->first();

            if (!$lichThiSinhVien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sinh viên không có trong danh sách thi này'
                ], 400);
            }

            // Kiểm tra đã điểm danh chưa
            if ($lichThiSinhVien->da_diem_danh) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sinh viên đã được điểm danh trước đó lúc ' . 
                                $lichThiSinhVien->thoi_gian_diem_danh->format('H:i:s d/m/Y')
                ], 400);
            }

            // Cập nhật điểm danh
            $lichThiSinhVien->update([
                'da_diem_danh' => true,
                'thoi_gian_diem_danh' => now(),
                'phuong_thuc_diem_danh' => $validated['phuong_thuc']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Điểm danh thành công',
                'data' => $lichThiSinhVien
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi điểm danh: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách sinh viên theo lịch thi
     */
    public function getDanhSachSinhVien($lichThiId)
    {
        try {
            $lichThi = \App\Models\LichThi::findOrFail($lichThiId);

            $danhSachSinhVien = \App\Models\LichThiSinhVien::with('sinhVien')
                ->where('lich_thi_id', $lichThiId)
                ->get()
                ->map(function ($item) {
                    $sv = $item->sinhVien;
                    return [
                        'mssv' => $item->mssv,
                        'ho_va_ten' => $sv->Ho_va_ten ?? 'N/A',
                        'lop' => $sv->Lop ?? null,
                        'ngay_sinh' => $sv->Ngay_sinh ?? null,
                        'da_diem_danh' => $item->da_diem_danh,
                        'thoi_gian_diem_danh' => $item->thoi_gian_diem_danh,
                        'phuong_thuc_diem_danh' => $item->phuong_thuc_diem_danh,
                        'ghi_chu' => $item->ghi_chu
                    ];
                });

            // Đếm số lượng đã điểm danh
            $attendedCount = $danhSachSinhVien->filter(fn($s) => $s['da_diem_danh'])->count();
            $totalCount = $danhSachSinhVien->count();

            return response()->json([
                'success' => true,
                'students' => $danhSachSinhVien,
                'summary' => [
                    'attended' => $attendedCount,
                    'total' => $totalCount
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lịch thi'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
