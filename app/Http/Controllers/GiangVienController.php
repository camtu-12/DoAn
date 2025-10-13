<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GiangVien;
use Illuminate\Http\Request;

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
}
