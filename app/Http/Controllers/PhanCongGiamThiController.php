<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PhanCongGiamThi;
use Illuminate\Http\Request;

class PhanCongGiamThiController extends Controller
{
    public function index()
    {
        return response()->json(PhanCongGiamThi::all());
    }

    public function show($id)
    {
        return PhanCongGiamThi::with('giangvien','phongthi','lichthi')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'giang_vien_id'=>'required|exists:giang_viens,id',
            'phong_thi_id'=>'required|exists:phong_this,id',
            'lich_thi_id'=>'required|exists:lich_this,id',
        ]);
        return PhanCongGiamThi::create($data);
    }

    public function update(Request $request,$id)
    {
        $pc = PhanCongGiamThi::findOrFail($id);
        $data = $request->validate([
            'giang_vien_id'=>'sometimes|exists:giang_viens,id',
            'phong_thi_id'=>'sometimes|exists:phong_this,id',
            'lich_thi_id'=>'sometimes|exists:lich_this,id',
        ]);
        $pc->update($data);
        return $pc;
    }

    public function destroy($id)
    {
        PhanCongGiamThi::findOrFail($id)->delete();
        return response()->json(['message'=>'Đã xóa phân công giám thị']);
    }
}
