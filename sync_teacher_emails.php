<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Syncing user emails with teacher emails..." . PHP_EOL;

$teachers = App\Models\GiangVien::all();
$updated = 0;

foreach ($teachers as $teacher) {
    $user = App\Models\User::where('Mssv', $teacher->MaGV)->first();
    
    if ($user && $user->email !== $teacher->Email) {
        echo "Updating {$user->Mssv}: {$user->email} -> {$teacher->Email}" . PHP_EOL;
        $user->email = $teacher->Email;
        $user->save();
        $updated++;
    }
}

echo PHP_EOL . "Updated {$updated} user emails." . PHP_EOL;
