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

    // bảng migration của bạn là `lich_this`
    protected $table = 'lich_this';

    protected $fillable = [
        'id',
        'Mon_Hoc',
        'Ngay_Thi',
        'Gio_Bat_Dau',
        'Gio_Ket_Thuc',
        'So_Phong',
        'Ghi_Chu',      // nếu bạn thêm cột Email liên kết giảng viên
    ];

    protected $dates = [
        'Ngay_Thi',
    ];

    public function sinhvien() { return $this->belongsTo(SinhVien::class); }
    public function giangvien() { return $this->belongsTo(GiangVien::class, 'giangvien_id'); }
    public function phongthi() { return $this->belongsTo(PhongThi::class, 'So_Phong'); }
}
