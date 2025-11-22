<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SinhVien extends Model
{
    use HasFactory;
    
    protected $table = 'sinhviens';
    
    // Bảng sử dụng Mssv làm khóa chính (chuỗi)
    protected $primaryKey = 'Mssv';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'Mssv',
        'Ho_va_ten',
        'Email',
        'Ngay_Sinh',
        'Lop',
        'Khoa',
        'Bac',
        'Photo',
        'KQDD'
    ];

    protected $casts = [
        'Ngay_Sinh' => 'date',
        'KQDD' => 'boolean',
    ];

    /**
     * Quan hệ nhiều-nhiều với lịch thi qua bảng lich_thi_sinh_vien
     */
    public function lichThis()
    {
        return $this->belongsToMany(
            LichThi::class,
            'lich_thi_sinh_vien',
            'mssv',
            'lich_thi_id',
            'Mssv',
            'id'
        )->withPivot('da_diem_danh', 'thoi_gian_diem_danh', 'phuong_thuc_diem_danh', 'ghi_chu')
          ->withTimestamps();
    }

    /**
     * Danh sách lịch thi của sinh viên (relationship trực tiếp)
     */
    public function lichThiSinhViens()
    {
        return $this->hasMany(LichThiSinhVien::class, 'mssv', 'Mssv');
    }
}
