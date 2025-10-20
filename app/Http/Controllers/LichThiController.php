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
