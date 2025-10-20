<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiangVien extends Model
{
    use HasFactory;

    protected $table = 'giang_viens'; // 👈 đúng tên bảng trong DB
    protected $primaryKey = 'MaGV'; // 👈 đúng tên khóa chính nếu có
    public $timestamps = false; // tắt created_at / updated_at nếu không dùng
    public $incrementing = false;     // <--- ADD THIS
    protected $keyType = 'string';    // <--- ADD THIS
    protected $fillable = [
   'MaGV','Ho_va_Ten','Email','Sdt','Bo_Mon'
    ];
}

