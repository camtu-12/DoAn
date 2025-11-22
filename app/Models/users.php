<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Model;

class users extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // thêm cột phân quyền (admin, giangvien, sinhvien)
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

     /**
     * Kiểu dữ liệu cho các trường.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Kiểm tra người dùng có phải Admin không.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Kiểm tra người dùng có phải Giảng viên không.
     */
    public function isGiangVien()
    {
        return $this->role === 'giangvien';
    }

    /**
     * Kiểm tra người dùng có phải Sinh viên không.
     */
    public function isSinhVien()
    {
        return $this->role === 'sinhvien';
    }
}
