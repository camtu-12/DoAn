<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PhongThi;
use Illuminate\Http\Request;

class PhongThiController extends Controller
{
    public function index()
    {
        return response()->json(PhongThi::all());
    }
    public function show($id) { return PhongThi::findOrFail($id); }
    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_phong'=>'required|string',
            'suc_chua'=>'required|integer',
        ]);
        return PhongThi::create($data);
    }
    public function update(Request $request,$id)
    {
        $pt = PhongThi::findOrFail($id);
        $data = $request->validate([
            'ten_phong'=>'sometimes|string',
            'suc_chua'=>'sometimes|integer',
        ]);
        $pt->update($data);
        return $pt;
    }
    public function destroy($id)
    {
        PhongThi::findOrFail($id)->delete();
        return response()->json(['message'=>'Đã xóa phòng thi']);
    }
}

