<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GiangVien;
use App\Models\PhongThi;
use App\Models\SinhVien;

class LichThi extends Model
{
     use HasFactory;
    protected $fillable = ['Mon_Thi', 'Ngay_Thi', 'Gio_Bat_Dau', 'Gio_Ket_Thuc', 'So_Phong','Ghi_Chu'];

    public function sinhvien() { return $this->belongsTo(SinhVien::class); }
    public function giangvien() { return $this->belongsTo(GiangVien::class); }
    public function phongthi() { return $this->belongsTo(PhongThi::class); }
}
