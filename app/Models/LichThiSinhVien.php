<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LichThiSinhVien extends Model
{
    use HasFactory;

    protected $table = 'lich_thi_sinh_vien';

    protected $fillable = [
        'lich_thi_id',
        'mssv',
        'da_diem_danh',
        'thoi_gian_diem_danh',
        'phuong_thuc_diem_danh',
        'ghi_chu',
    ];

    protected $casts = [
        'da_diem_danh' => 'boolean',
        'thoi_gian_diem_danh' => 'datetime',
    ];

    /**
     * Quan hệ với bảng lich_this
     */
    public function lichThi()
    {
        return $this->belongsTo(LichThi::class, 'lich_thi_id');
    }

    /**
     * Quan hệ với bảng sinhviens
     */
    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'mssv', 'Mssv');
    }
}
