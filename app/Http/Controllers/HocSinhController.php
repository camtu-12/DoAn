<?php

namespace App\Http\Controllers;

use App\Models\HocSinh;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class HocSinhController extends Controller
{
    /**
     * Hiển thị danh sách sinh viên
     */
    public function index()
    {
        $hocsinhs = HocSinh::all();
        return Inertia::render('HocSinh/Index', [
            'hocsinhs' => $hocsinhs
        ]);
    }

    /**
     * Form thêm sinh viên
     */
    public function create()
    {
        return Inertia::render('HocSinh/Create');
    }

    /**
     * Lưu sinh viên mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Ho' => 'required|string|max:255',
            'Ten' => 'required|string|max:255',
            'email' => 'required|email|unique:hoc_sinhs,email',
            'Ngay_Sinh' => 'required|date',
            'mssv' => 'required|string|unique:hoc_sinhs,mssv',
            'Lop' => 'required|string|max:255',
            'Khoa' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo'] = $path;
        }

        HocSinh::create($validated);

        return redirect()->route('hocsinh.index')->with('success', 'Thêm sinh viên thành công');
    }

    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit(HocSinh $hocSinh)
    {
        return Inertia::render('HocSinh/Edit', [
            'hocSinh' => $hocSinh
        ]);
    }

    /**
     * Cập nhật sinh viên
     */
    public function update(Request $request, HocSinh $hocSinh)
    {
        $validated = $request->validate([
            'Ho' => 'required|string|max:255',
            'Ten' => 'required|string|max:255',
            'email' => 'required|email|unique:hoc_sinhs,email,' . $hocSinh->id,
            'Ngay_Sinh' => 'required|date',
            'mssv' => 'required|string|unique:hoc_sinhs,mssv,' . $hocSinh->id,
            'Lop' => 'required|string|max:255',
            'Khoa' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Xóa ảnh cũ
            if ($hocSinh->photo && Storage::disk('public')->exists($hocSinh->photo)) {
                Storage::disk('public')->delete($hocSinh->photo);
            }

            // Lưu ảnh mới
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo'] = $path;
        }

        $hocSinh->update($validated);

        return redirect()->route('hocsinh.index')->with('success', 'Cập nhật sinh viên thành công');
    }

    /**
     * Xóa sinh viên
     */
    public function destroy(HocSinh $hocSinh)
    {
        if ($hocSinh->photo && Storage::disk('public')->exists($hocSinh->photo)) {
            Storage::disk('public')->delete($hocSinh->photo);
        }

        $hocSinh->delete();

        return redirect()->route('hocsinh.index')->with('success', 'Xóa sinh viên thành công');
    }
}
