<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SinhVien;
use Illuminate\Http\Request;

class SinhVienController extends Controller
{
    // GET /api/sinhviens
    public function index()
    {
        return response()->json(SinhVien::all());
    }

    // GET /api/sinhviens/{id}
    public function show($id)
    {
        return SinhVien::findOrFail($id); // Trả về 1 sinh viên
    }

    // POST /api/sinhviens
    public function store(Request $request)
    {
        $data = $request->validate([
            'ho' => 'required|string',
            'ten' => 'required|string',
            'mssv' => 'required|string|unique:sinh_viens,mssv',
            'lop' => 'required|string',
        ]);

        return SinhVien::create($data);
    }

    // PUT /api/sinhviens/{id}
    public function update(Request $request, $id)
    {
        $sv = SinhVien::findOrFail($id);

        $data = $request->validate([
            'hovaten' => 'sometimes|string',
            'mssv' => 'sometimes|string|unique:sinh_viens,mssv,'.$id,
            'lop' => 'sometimes|string',
        ]);

        $sv->update($data);

        return $sv;
    }

    // DELETE /api/sinhviens/{id}
    public function destroy($id)
    {
        $sv = SinhVien::findOrFail($id);
        $sv->delete();

        return response()->json(['message' => 'Đã xóa sinh viên']);
    }
}