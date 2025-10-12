<?php

namespace App\Http\Controllers;

use App\Models\GiangVien;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GiangVienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         // Ví dụ: Lấy toàn bộ danh sách giảng viên từ database
    $giangviens = GiangVien::all();

    // Trả về JSON (nếu dùng API) hoặc view
    return response()->json($giangviens);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Nếu dùng Vue hoặc Inertia, có thể trả về form thêm mới
        return Inertia::render('GiangVien/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
        'ten' => 'required|string|max:100',
        'email' => 'required|email|unique:giang_viens,email',
        'khoa' => 'required|string|max:100',
        'photo' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('giangvien', 'public');
        $validated['photo'] = $path;
    }

    GiangVien::create($validated);

    return response()->json(['message' => 'Thêm giảng viên thành công!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(GiangVien $giangVien)
    {
          return response()->json($giangVien);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GiangVien $giangVien)
    {
         return Inertia::render('GiangVien/Edit', ['giangVien' => $giangVien]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GiangVien $giangVien)
    {
         $validated = $request->validate([
        'ten' => 'required|string|max:100',
        'email' => 'required|email|unique:giang_viens,email,' . $giangVien->id,
        'khoa' => 'required|string|max:100',
        'photo' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('giangvien', 'public');
        $validated['photo'] = $path;
    }

    $giangVien->update($validated);

    return response()->json(['message' => 'Cập nhật thành công!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GiangVien $giangVien)
    {
        $giangVien->delete();
    return response()->json(['message' => 'Xóa giảng viên thành công!']);
    }
}
