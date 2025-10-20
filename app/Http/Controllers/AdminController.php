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
    public function show(admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(admin $admin)
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

   public function getSchedules()
   {
     $schedules = LichThi::all();
    return response()->json($schedules->toArray());
   }

   public function addSchedule(Request $request) {
        $schedule = LichThi::create($request->all());
        return response()->json($schedule, 201);
    }
    public function updateSchedule(Request $request, $id)
{
    $schedule = LichThi::findOrFail($id);
    $schedule->update($request->all());
    return response()->json($schedule, 200);
}
public function getSchedule($id)
{
    $schedule = LichThi::find($id);

    if (!$schedule) {
        return response()->json(['message' => 'Không tìm thấy lịch thi'], 404);
    }

    return response()->json($schedule, 200);
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

}
