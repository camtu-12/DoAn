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

    // nếu khóa chính của bảng là STT, khai báo rõ
    protected $primaryKey = 'STT';
    // nếu STT là số nguyên auto-increment để true, nếu không (ví dụ mã chuỗi) đặt false
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'STT', 'Thu', 'Ngay_Thi', 'Gio_Bat_Dau', 'Mon_Hoc',
        'So_Phong', 'DSSV', 'DSGV', 'Ghi_Chu'
    ];

    protected $dates = [
        'Ngay_Thi',
    ];

    // expose id để frontend dùng item.id
    public function getIdAttribute()
    {
        return $this->{$this->getKeyName()};
    }

    public function sinhvien() { return $this->belongsTo(SinhVien::class); }
    public function giangvien() { return $this->belongsTo(GiangVien::class, 'giangvien_id'); }
    public function phongthi() { return $this->belongsTo(PhongThi::class, 'So_Phong'); }
}
