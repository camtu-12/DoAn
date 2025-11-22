<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

// Tạo Admin
User::create([
    'Mssv' => 'ADMIN001',
    'Ho_va_Ten' => 'Quản Trị Viên',
    'email' => 'admin@example.com',
    'password' => bcrypt('123456'),
    'role' => 'Admin'
]);

// Tạo Giảng viên
User::create([
    'Mssv' => 'GV001',
    'Ho_va_Ten' => 'Nguyễn Văn A',
    'email' => 'giangvien@example.com',
    'password' => bcrypt('123456'),
    'role' => 'GiangVien'
]);

// Tạo Sinh viên
User::create([
    'Mssv' => 'SV001',
    'Ho_va_Ten' => 'Trần Thị B',
    'email' => 'sinhvien@example.com',
    'password' => bcrypt('123456'),
    'role' => 'SinhVien'
]);

echo "✅ Đã tạo 3 tài khoản thành công!\n";
echo "- Admin: admin@example.com / 123456\n";
echo "- Giảng viên: giangvien@example.com / 123456\n";
echo "- Sinh viên: sinhvien@example.com / 123456\n";
