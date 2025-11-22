<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\GiangVien;
use App\Models\SinhVien;
use App\Models\LichThi;

class AdminController extends Controller
{
    public function getLecturers()
{
    $lecturers = GiangVien::all();
    return response()->json($lecturers->toArray());
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($admin)
    {
        //
    }

    // danh sách giảng viên (dùng bởi frontend)
    public function lecturers()
    {
        if (! Schema::hasTable('giang_viens')) {
            return response()->json([], 200);
        }

        $rows = DB::table('giang_viens')->select(
            DB::raw("COALESCE(MaGV, id) as code"),
            DB::raw("COALESCE(CONCAT(COALESCE(Ho,''),' ',COALESCE(Ten,'')), COALESCE(Ten,'')) as name"),
            'Email as email',
            DB::raw("COALESCE(Sdt, Phone, '') as phone")
        )->get();

        return response()->json($rows);
    }

    // danh sách sinh viên
    public function students()
    {
        if (! Schema::hasTable('sinh_viens') && ! Schema::hasTable('sinhvien') && ! Schema::hasTable('sinh_viên')) {
            return response()->json([], 200);
        }

        // cố gắng đọc các tên bảng phổ biến
        $table = Schema::hasTable('sinh_viens') ? 'sinh_viens' : (Schema::hasTable('sinhvien') ? 'sinhvien' : 'sinh_viên');

        $rows = DB::table($table)->select(
            DB::raw("COALESCE(MSSV, Mssv, mssv, id) as mssv"),
            DB::raw("COALESCE(Ho_Ten, Ho_va_ten, Ho, Ten, name) as name"),
            DB::raw("COALESCE(Email,'') as email"),
            DB::raw("COALESCE(Ngay_Sinh, birthday, '') as birthday"),
            DB::raw("COALESCE(Lop, class, '') as class"),
            DB::raw("COALESCE(Khoa, faculty, '') as faculty"),
            DB::raw("COALESCE(Photo, photo, '') as photo")
        )->get();

        return response()->json($rows);
    }

    // lịch (lấy trực tiếp từ lich_this để đảm bảo có dữ liệu)
    public function schedules()
    {
        if (! Schema::hasTable('lich_this')) {
            return response()->json([], 200);
        }

        $rows = DB::table('lich_this')->select(
            DB::raw("COALESCE(id, 0) as id"),
            DB::raw("COALESCE(MaMT, Ma_MT, '') as MaMT"),
            DB::raw("COALESCE(Mon_Hoc, Mon_Thi, MonThi, '') as Mon_Hoc"),
            DB::raw("COALESCE(Ngay_Thi, NgayThi, '') as Ngay_Thi"),
            DB::raw("COALESCE(Gio_Bat_Dau, GioBatDau, '') as Gio_Bat_Dau"),
            DB::raw("COALESCE(Gio_Ket_Thuc, GioKetThuc, '') as Gio_Ket_Thuc"),
            DB::raw("COALESCE(So_Phong, SoPhong, '') as So_Phong"),
            DB::raw("COALESCE(Ghi_Chu, GhiChu, '') as Ghi_Chu"),
            // nếu có cột Email sẽ trả để liên kết giảng viên
            DB::raw( Schema::hasColumn('lich_this', 'Email') ? 'Email' : "NULL as Email" )
        )->get();

        $data = $rows->map(function ($r) {
            return [
                'id' => $r->id,
                'lecturerCode' => $r->Email ?? null,
                'subjectCode'  => $r->MaMT ?? null,
                'subjectName'  => $r->Mon_Hoc ?? null,
                'date'         => $r->Ngay_Thi ? (string)$r->Ngay_Thi : null,
                'start'        => $r->Gio_Bat_Dau ?? null,
                'end'          => $r->Gio_Ket_Thuc ?? null,
                'room'         => $r->So_Phong ?? null,
                'note'         => $r->Ghi_Chu ?? null,
            ];
        })->values();

        return response()->json($data);
    }

    // điểm danh (nếu chưa có bảng, trả rỗng)
    public function attendance()
    {
        // nếu có bảng diemdanh thì thay bằng query tương ứng ở đây
        return response()->json([]);
    }

    public function getStudents()
   {
     $students = SinhVien::all();
    return response()->json($students->toArray());
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
                'Bac' => 'nullable|string',
                'Photo' => 'nullable|string',
            ]);

            try {
                $sv = SinhVien::create($data);
                return response()->json([
                    'success' => true,
                    'data' => $sv,
                    'message' => 'Thêm sinh viên thành công'
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tạo sinh viên thất bại',
                    'error' => $e->getMessage()
                ], 500);
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
                'Bac' => 'sometimes|nullable|string',
                'Photo' => 'sometimes|nullable|string',
            ]);

            try {
                $sv->update($data);
                return response()->json([
                    'success' => true,
                    'data' => $sv,
                    'message' => 'Cập nhật sinh viên thành công'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cập nhật thất bại',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

   public function getSchedules()
   {
        // Lấy tất cả lịch thi với relationships
        $schedules = LichThi::with(['sinhViens', 'giangViens', 'phongThi'])->get();

        // Format dữ liệu để frontend dễ sử dụng
        $formattedSchedules = $schedules->map(function($schedule) {
            // Lấy danh sách MSSV
            $dssv = $schedule->sinhViens->pluck('Mssv')->toArray();
            
            // Lấy danh sách Mã GV
            $dsgv = $schedule->giangViens->pluck('MaGV')->toArray();

            return [
                'id' => $schedule->id,
                'MaMT' => $schedule->MaMT,
                'Mon_Hoc' => $schedule->Mon_Hoc,
                'Ngay_Thi' => $schedule->Ngay_Thi,
                'Gio_Bat_Dau' => $schedule->Gio_Bat_Dau,
                'Gio_Ket_Thuc' => $schedule->Gio_Ket_Thuc,
                'So_Phong' => $schedule->So_Phong,
                'Ghi_Chu' => $schedule->Ghi_Chu,
                'DSSV' => implode(',', $dssv), // String phân cách bằng dấu phẩy
                'DSGV' => implode(',', $dsgv), // String phân cách bằng dấu phẩy
                'created_at' => $schedule->created_at,
                'updated_at' => $schedule->updated_at,
            ];
        });

        return response()->json($formattedSchedules->toArray());
   }



public function getSchedule($id)
{
    $schedule = LichThi::find($id);

    if (!$schedule) {
        return response()->json(['message' => 'Không tìm thấy lịch thi'], 404);
    }

    return response()->json($schedule, 200);
}

    /**
     * Show form đổi mật khẩu cho admin
     */
    public function showChangePassword()
    {
        return inertia('Admin/ChangePassword');
    }

    /**
     * Xử lý đổi mật khẩu cho admin
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        $user = $request->user();

        // Kiểm tra mật khẩu hiện tại
        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng!']);
        }

        // Cập nhật mật khẩu mới
        $user->password = \Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

}
