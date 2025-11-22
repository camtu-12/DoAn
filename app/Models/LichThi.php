<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichThi extends Model
{
    use HasFactory;

    protected $table = 'lich_this';
    
    // Khóa chính là 'id' (auto-increment)
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'MaMT',
        'Mon_Hoc',
        'Ngay_Thi',
        'Gio_Bat_Dau',
        'Gio_Ket_Thuc',
        'So_Phong',
        'Ghi_Chu'
    ];

    protected $casts = [
        'Ngay_Thi' => 'date',
    ];

    /**
     * Quan hệ với bảng phong_this
     */
    public function phongThi()
    {
        return $this->belongsTo(PhongThi::class, 'So_Phong', 'id');
    }

    /**
     * Quan hệ nhiều-nhiều với sinh viên qua bảng lich_thi_sinh_vien
     */
    public function sinhViens()
    {
        return $this->belongsToMany(
            SinhVien::class,
            'lich_thi_sinh_vien',
            'lich_thi_id',
            'mssv',
            'id',
            'Mssv'
        )->withPivot('da_diem_danh', 'thoi_gian_diem_danh', 'phuong_thuc_diem_danh', 'ghi_chu')
          ->withTimestamps();
    }

    /**
     * Quan hệ nhiều-nhiều với giảng viên qua bảng phanconggiamthis
     */
    public function giangViens()
    {
        return $this->belongsToMany(
            GiangVien::class,
            'phanconggiamthis',
            'exam_id',
            'teacher_id',
            'id',
            'id'
        )->withPivot('phong_thi_id', 'role')
          ->withTimestamps();
    }

    /**
     * Danh sách phân công giám thị
     */
    public function phanCongGiamThis()
    {
        return $this->hasMany(PhanCongGiamThi::class, 'exam_id', 'id');
    }

    /**
     * Danh sách sinh viên thi (relationship trực tiếp)
     */
    public function lichThiSinhViens()
    {
        return $this->hasMany(LichThiSinhVien::class, 'lich_thi_id', 'id');
    }
}
