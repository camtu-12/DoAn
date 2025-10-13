<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class admin extends Model
{
    protected $fillable = ['Ho', 'Ten', 'email', 'sdt', 'Ngay_Sinh'];

     public function getFullNameAttribute()
    {
        return trim($this->Ho . ' ' . $this->Ten);
    }
}
