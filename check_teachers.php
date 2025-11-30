<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Total teachers: " . App\Models\GiangVien::count() . PHP_EOL;
echo "First 5 teachers:" . PHP_EOL;

App\Models\GiangVien::take(5)->get(['id', 'MaGV', 'Ho_va_Ten', 'Email'])->each(function($g) {
    echo "ID: {$g->id}, MaGV: {$g->MaGV}, Email: {$g->Email}, Name: {$g->Ho_va_Ten}" . PHP_EOL;
});

echo PHP_EOL . "Users with role GiangVien:" . PHP_EOL;
App\Models\User::where('role', 'GiangVien')->take(5)->get(['Mssv', 'email', 'Ho_va_Ten'])->each(function($u) {
    echo "Mssv: {$u->Mssv}, Email: {$u->email}, Name: {$u->Ho_va_Ten}" . PHP_EOL;
});
