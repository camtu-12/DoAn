<?php
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;    
//Đây là của P thêm   
use Illuminate\Http\Request;


// Import các controller
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\GiangVienController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PhongThiController;
use App\Http\Controllers\LichThiController;
use App\Http\Controllers\PhanCongGiamThiController;
use App\Models\GiangVien;

// Trang chủ
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard — tự động chuyển trang Vue theo vai trò
Route::get('/dashboard', function () {
    /** @var User|null $user */
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    switch ($user->role) {
        case 'Admin':
            return Inertia::render('Admin/Index', ['user' => $user]);
        case 'GiangVien':
            return Inertia::render('GiangVien/Index', ['user' => $user]);
        case 'SinhVien':
            return Inertia::render('SinhVien/Index', ['user' => $user]);
        default:
            return Inertia::render('SinhVien/Index', ['user' => $user]);
    }
})->middleware(['auth', 'verified'])->name('dashboard');

//Logout route (P)
Route::post('/logout', function (Request $request) {
    Auth::logout(); // Đăng xuất user

    // Xóa session & regenerate token
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Quay về trang login
    return redirect()->route('login');
})->name('logout');

Route::resource('sinhviens', SinhVienController::class)->middleware(['auth', 'verified']);
Route::resource('giangviens', GiangVienController::class)->middleware(['auth', 'verified']);
Route::resource('admin', AdminController::class)->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Các route danh sách
Route::get('/sinhviens', [SinhVienController::class, 'index']);
Route::get('/giangviens', [GiangVienController::class, 'index']);
Route::get('/phongthis', [PhongThiController::class, 'index']);
Route::get('/lichthis', [LichThiController::class, 'index']);
Route::get('/phanconggiamthis', [PhanCongGiamThiController::class, 'index']);

// ✅ Nhóm các route cho sinh viên
Route::prefix('sinhvien')->group(function () {
    // Route cần đăng nhập (nếu bạn dùng Sanctum)
    Route::middleware('auth:sanctum')->get('/thongtin', [SinhVienController::class, 'info']);

    // Route công khai (test tạm thời)
    Route::get('/diemdanh', [SinhVienController::class, 'attendance']);
    Route::get('/lichthi', [SinhVienController::class, 'examSchedule']);
});




Route::prefix('giangvien')->group(function () {
    Route::get('/doimatkhau', [GiangVienController::class, 'showDoiMatKhau'])->name('giangvien.doimatkhau');
    Route::post('/doimatkhau', [GiangVienController::class, 'updateMatKhau'])->name('giangvien.updateMatKhau');
});

Route::middleware('auth:sanctum')->get('/giangvien/thongtin', [GiangVienController::class, 'thongTin']);

Route::middleware('auth')->get('/giangvien/lichgac', [GiangVienController::class, 'lichGac']);
Route::middleware('auth')->get('/giangvien/ketqua', [GiangVienController::class, 'ketQua']);


Route::middleware('auth')->group(function () {
    Route::get('/giangvien/thongtin', [GiangVienController::class, 'thongtin']);
    Route::get('/giangvien/lichgac', [GiangVienController::class, 'lichGac']);
    Route::get('/giangvien/ketqua', [GiangVienController::class, 'ketQua']);
});
Route::middleware('auth')->group(function () {
    Route::get('/giangvien/lichgac', [GiangVienController::class, 'lichGac']);
});


Route::middleware('auth')->group(function () {
    Route::get('/admin/lecturers', [AdminController::class, 'lecturers']);
    Route::get('/admin/students',   [AdminController::class, 'students']);
    Route::get('/admin/schedules',  [AdminController::class, 'schedules']);
    Route::get('/admin/attendance', [AdminController::class, 'attendance']);
});

// API nhẹ cho trang Admin
Route::get('/admin/lecturers', [AdminController::class, 'lecturers'])->middleware(['auth','verified']);
Route::get('/admin/students',   [AdminController::class, 'students'])->middleware(['auth','verified']);
Route::get('/admin/schedules',  [AdminController::class, 'schedules'])->middleware(['auth','verified']);
Route::get('/admin/attendance', [AdminController::class, 'attendance'])->middleware(['auth','verified']);

Route::get('/test/admin-schedules-sample', function () {
    return response()->json([
        ['id'=>1,'lecturerCode'=>'gv1@example.com','subjectCode'=>'MT101','subjectName'=>'Cơ sở dữ liệu','date'=>'2025-11-01','start'=>'08:00','end'=>'10:00','note'=>'','room'=>'C103'],
    ]);
});

Route::get('/lecturers', [AdminController::class, 'getLecturers']);
Route::get('/students', [AdminController::class, 'getStudents']);
Route::get('/schedules', [AdminController::class, 'getSchedules']); //lich thi phan cong
Route::post('/schedules/add', [AdminController::class, 'addSchedule']);
Route::put('/schedules/update/{id}', [AdminController::class, 'updateSchedule']);
Route::delete('/schedules/delete/{id}', [AdminController::class, 'deleteSchedule']);

