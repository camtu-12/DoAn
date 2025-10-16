<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SinhVien extends Model
{
    use HasFactory; 
    protected $fillable = 
    ['Ho_va_ten','Email','NgaySinh','Mssv','Lop','Khoa','Photo'];
}
