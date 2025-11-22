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

    // tìm giảng viên theo email hoặc user_id
    $gv = GiangVien::where('Email', $user->email)->first()
        ?? GiangVien::where('user_id', $user->id)->first();

    if (! $gv) {
        return response()->json([], 200);
    }

    // Nếu có bảng liên kết phân công (gac_thi hoặc phan_cong_giam_this) join với lichthi
    if (\Schema::hasTable('gac_thi') && \Schema::hasTable('lichthi')) {
        $rows = \DB::table('gac_thi')
            ->where('gac_thi.giangvien_id', $gv->id)
            ->join('lichthi', 'gac_thi.lichthi_id', '=', 'lichthi.id')
            ->select(
                'gac_thi.id as id',
                'lichthi.ngay as date',
                'gac_thi.phong as room',
                'lichthi.mon_hoc as subject',
                'gac_thi.gio_bat_dau as start_time',
                'gac_thi.gio_ket_thuc as end_time'
            )->get();
    } elseif (\Schema::hasTable('phan_cong_giam_this') && \Schema::hasTable('lichthi')) {
        $rows = \DB::table('phan_cong_giam_this')
            ->where('phan_cong_giam_this.giangvien_id', $gv->id)
            ->join('lichthi', 'phan_cong_giam_this.lichthi_id', '=', 'lichthi.id')
            ->select(
                'phan_cong_giam_this.id as id',
                'lichthi.ngay as date',
                'phan_cong_giam_this.phong as room',
                'lichthi.mon_hoc as subject',
                'phan_cong_giam_this.gio_bat_dau as start_time',
                'phan_cong_giam_this.gio_ket_thuc as end_time'
            )->get();
    } else {
        // Fallback: lấy tất cả lịch thi cùng bộ môn/khoa của giảng viên (nếu không có bảng phân công)
        $rows = \DB::table('lichthi')
            ->where(function($q) use ($gv) {
                if (!empty($gv->Bo_Mon)) $q->where('bo_mon', $gv->Bo_Mon);
                if (!empty($gv->khoa)) $q->orWhere('khoa', $gv->khoa);
            })
            ->select(
                'id',
                'ngay as date',
                'phong as room',
                'mon_hoc as subject',
                'gio_bat_dau as start_time',
                'gio_ket_thuc as end_time'
            )->get();
    }

    $data = collect($rows)->map(function ($r) {
        return [
            'id' => $r->id ?? null,
            'date' => $r->date ?? null,
            'room' => $r->room ?? '',
            'subject' => $r->subject ?? '',
            'start_time' => $r->start_time ?? null,
            'end_time' => $r->end_time ?? null,
        ];
    })->values();

    return response()->json($data);
}




public function ketQua(Request $request)
{
    $user = $request->user();

    // tương tự: lấy kết quả theo giangvien
    $gv = $user->giangvien ?? $user->teacher ?? GiangVien::where('user_id', $user->id)->first()
          ?? GiangVien::where('email', $user->email)->first();

    if (! $gv) {
        return response()->json([], 200);
    }

    if (method_exists($gv, 'results')) {
        $rows = $gv->results()->get();
    } else {
        $rows = \DB::table('attendance_results')
            ->where('giangvien_id', $gv->id)
            ->get();
    }

    $data = $rows->map(function ($r) {
        return [
            'studentId' => $r->student_id ?? $r->mssv ?? null,
            'name'      => $r->name ?? $r->ho_ten ?? null,
            'date'      => $r->date ?? $r->ngay ?? null,
            'status'    => $r->status ?? $r->trang_thai ?? null,
        ];
    });

    return response()->json($data);
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
}
