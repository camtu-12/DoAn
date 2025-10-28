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
        'Ho_va_ten', 'Email', 'Ngay_Sinh', 'Mssv', 'Lop', 'Khoa', 'Photo', 'KQDD'
    ];
}
