<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhongThi extends Model
{
    use HasFactory;

    protected $table = 'phong_this';

    protected $fillable = [
        'So_Phong',
        'So_Luong_Ghe_Ngoi'
    ];

    /**
     * Quan hệ với lịch thi
     */
    public function lichThis()
    {
        return $this->hasMany(LichThi::class, 'So_Phong', 'id');
    }

    /**
     * Quan hệ với phân công giám thị
     */
    public function phanCongGiamThis()
    {
        return $this->hasMany(PhanCongGiamThi::class, 'phong_thi_id', 'id');
    }
}
