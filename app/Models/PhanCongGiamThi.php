<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhanCongGiamThi extends Model
{
    use HasFactory;

    protected $table = 'phanconggiamthis';

    protected $fillable = [
        'exam_id',
        'teacher_id',
        'phong_thi_id',
        'role'
    ];

    /**
     * Quan hệ với lịch thi
     */
    public function lichThi()
    {
        return $this->belongsTo(LichThi::class, 'exam_id', 'id');
    }

    /**
     * Quan hệ với giảng viên
     */
    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'teacher_id', 'id');
    }

    /**
     * Quan hệ với phòng thi
     */
    public function phongThi()
    {
        return $this->belongsTo(PhongThi::class, 'phong_thi_id', 'id');
    }
}
