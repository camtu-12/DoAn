<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\GiangVienController;
use App\Http\Controllers\PhongThiController;
use App\Http\Controllers\LichThiController;
use App\Http\Controllers\PhanCongGiamThiController;

Route::get('/sinhviens', [SinhVienController::class, 'index']);
Route::get('/giangviens', [GiangVienController::class, 'index']);
Route::get('/phongthis', [PhongThiController::class, 'index']);
Route::get('/lichthis', [LichThiController::class, 'index']);
Route::get('/phanconggiamthis', [PhanCongGiamThiController::class, 'index']);
