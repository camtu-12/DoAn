<?php

namespace App\Models;

use App\Models\LichThi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GiangVien;
use App\Models\PhongThi;

class PhanCongGiamThi extends Model
{
     use HasFactory;
    protected $fillable = ['MaMT','MaGV','So_Phong'];

    public function giangvien() { return $this->belongsTo(GiangVien::class); }
    public function phongthi() { return $this->belongsTo(PhongThi::class); }
    public function lichthi() { return $this->belongsTo(LichThi::class); }
}
