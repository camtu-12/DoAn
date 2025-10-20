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
}
