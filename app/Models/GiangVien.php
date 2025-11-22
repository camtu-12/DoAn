<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiangVien extends Model
{
    use HasFactory;

    protected $table = 'giang_viens';
    
    // Khóa chính là 'id' (auto-increment)
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'MaGV',
        'Ho_va_Ten',
        'Email',
        'Sdt'
    ];

    /**
     * Quan hệ nhiều-nhiều với lịch thi qua bảng phanconggiamthis
     */
    public function lichThis()
    {
        return $this->belongsToMany(
            LichThi::class,
            'phanconggiamthis',
            'teacher_id',
            'exam_id',
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
        return $this->hasMany(PhanCongGiamThi::class, 'teacher_id', 'id');
    }
}

