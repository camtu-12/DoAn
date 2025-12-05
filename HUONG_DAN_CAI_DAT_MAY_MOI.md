# 🚀 HƯỚNG DẪN CÀI ĐẶT DỰ ÁN TRÊN MÁY MỚI

## 📋 MỤC LỤC
1. [Yêu cầu hệ thống](#yêu-cầu-hệ-thống)
2. [Cài đặt môi trường](#cài-đặt-môi-trường)
3. [Cài đặt dự án](#cài-đặt-dự-án)
4. [Thiết lập cơ sở dữ liệu](#thiết-lập-cơ-sở-dữ-liệu)
5. [Chạy dự án](#chạy-dự-án)
6. [Xử lý lỗi thường gặp](#xử-lý-lỗi-thường-gặp)

---

## 🔧 YÊU CẦU HỆ THỐNG

### Phần mềm cần cài đặt:
- **XAMPP** (bao gồm Apache, MySQL, PHP 8.x)
- **Composer** (PHP dependency manager)
- **Node.js** (v18 trở lên) + npm
- **Git** (tùy chọn, để clone project)

---

## 📦 CÀI ĐẶT MÔI TRƯỜNG

### 1. Cài đặt XAMPP
1. Download XAMPP từ: https://www.apachefriends.org/download.html
2. Cài đặt vào `C:\xampp` hoặc `D:\xampp`
3. Khởi động **Apache** và **MySQL** trong XAMPP Control Panel

### 2. Enable PHP Zip Extension
1. Mở file `php.ini`:
   - Đường dẫn: `C:\xampp\php\php.ini` hoặc `D:\xampp\php\php.ini`
2. Tìm dòng `;extension=zip`
3. Bỏ dấu `;` để thành: `extension=zip`
4. Lưu file và **Restart Apache** trong XAMPP Control Panel
5. Kiểm tra: `php -m | findstr zip` (phải hiển thị "zip")

### 3. Cài đặt Composer
1. Download từ: https://getcomposer.org/download/
2. Chạy file cài đặt và chọn đường dẫn PHP của XAMPP
   - Ví dụ: `C:\xampp\php\php.exe`
3. Kiểm tra: Mở CMD/Terminal gõ `composer --version`

### 4. Cài đặt Node.js
1. Download từ: https://nodejs.org/
2. Cài đặt bản LTS (Long Term Support)
3. Kiểm tra: 
   ```bash
   node -v
   npm -v
   ```

---

## 📁 CÀI ĐẶT DỰ ÁN

### 1. Copy dự án vào máy mới
```bash
# Giải nén hoặc copy folder dự án vào ổ đĩa
# Ví dụ: D:\An\DoAn-master\DoAn-master
```

### 2. Cài đặt PHP Dependencies
```bash
# Mở Terminal/CMD tại thư mục dự án
cd D:\An\DoAn-master\DoAn-master

# Cài đặt Laravel dependencies
composer install

# Nếu gặp lỗi với zip extension:
composer install --ignore-platform-req=ext-zip
```

### 3. Cài đặt JavaScript Dependencies
```bash
# Cài đặt Node.js packages
npm install
```

### 4. Cấu hình môi trường
```bash
# Copy file .env.example thành .env (nếu chưa có)
copy .env.example .env

# Tạo Application Key
php artisan key:generate
```

### 5. Chỉnh sửa file `.env`
Mở file `.env` và cấu hình database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=doanchuyennganh
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🗄️ THIẾT LẬP CƠ SỞ DỮ LIỆU

### Cách 1: Tạo database mới và seed dữ liệu mẫu (KHUYẾN NGHỊ)

#### Bước 1: Tạo database
1. Mở trình duyệt, truy cập: http://localhost/phpmyadmin
2. Click tab **"SQL"**
3. Chạy lệnh:
```sql
CREATE DATABASE doanchuyennganh CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Bước 2: Chạy Migration để tạo các bảng
```bash
# Tạo các bảng trong database theo thứ tự:
# - sessions, cache, cache_locks
# - users (bảng người dùng chính)
# - admins
# - giang_viens (giảng viên)
# - phong_this (phòng thi)
# - sinhviens (sinh viên)
# - lich_this (lịch thi)
# - phanconggiamthis (phân công giám thị)
# - lich_thi_sinh_vien (phân bổ sinh viên vào lịch thi)

php artisan migrate
```

#### Bước 3: Seed dữ liệu mẫu
```bash
# Tạo dữ liệu mẫu bao gồm:
# - 1 Admin (email: admin@example.com, pass: 123456)
# - 50 Giảng viên
# - 100 Sinh viên
# - Phòng thi
# - Lịch thi
# - Phân công giám thị
# - Danh sách sinh viên trong lịch thi

php artisan db:seed
```

**Kết quả sau khi seed:**
- ✅ **1 tài khoản Admin**: admin@example.com / 123456
- ✅ **10 tài khoản Giảng viên**: giangvien1@example.com đến giangvien10@example.com / 123456
- ✅ **50 tài khoản Sinh viên**: sinhvien1@example.com đến sinhvien50@example.com / 123456
- ✅ **50 Giảng viên** trong bảng `giang_viens`
- ✅ **100 Sinh viên** trong bảng `sinhviens`
- ✅ **Phòng thi** đã được tạo
- ✅ **Lịch thi** với phân công giám thị và danh sách sinh viên

---

### Cách 2: Import database có sẵn

#### Bước 1: Drop database cũ (nếu có)
1. Mở phpMyAdmin: http://localhost/phpmyadmin
2. Click vào database `doanchuyennganh` ở sidebar (nếu có)
3. Click tab **"Operations"**
4. Kéo xuống phần **"Remove database"**
5. Click **"Drop the database (DROP)"** và xác nhận

**HOẶC** dùng SQL:
```sql
DROP DATABASE IF EXISTS doanchuyennganh;
CREATE DATABASE doanchuyennganh CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Bước 2: Import file SQL
1. Click vào database `doanchuyennganh` vừa tạo
2. Click tab **"Import"**
3. Click **"Choose File"** và chọn file `.sql` của bạn
4. Click **"Go"** để import

**HOẶC** dùng Command Line:
```bash
# Nếu có file backup database (ví dụ: backup.sql)
mysql -u root -p doanchuyennganh < D:\An\DoAn-master\backup.sql

# Hoặc không cần password (XAMPP mặc định):
mysql -u root doanchuyennganh < D:\An\DoAn-master\backup.sql
```

---

### 📊 CẤU TRÚC DATABASE

Dự án sử dụng các bảng chính sau:

#### 1. **users** - Tài khoản đăng nhập
- Lưu thông tin đăng nhập của Admin, Giảng viên, Sinh viên
- Columns: id, Mssv, Ho_va_Ten, email, password, role, lop, created_at, updated_at

#### 2. **giang_viens** - Thông tin giảng viên
- Columns: id, MaGV, Ho_va_Ten, Email, Sdt, Bo_Mon, Chuc_Vu, created_at, updated_at

#### 3. **sinhviens** - Thông tin sinh viên
- Columns: Mssv (PK), Ho_va_ten, Ngay_Sinh, Lop, Khoa, Bac, created_at, updated_at

#### 4. **phong_this** - Danh sách phòng thi
- Columns: id, So_Phong, Toa_Nha, Suc_Chua, created_at, updated_at

#### 5. **lich_this** - Lịch thi
- Columns: id, MaMT, Mon_Hoc, Ngay_Thi, Gio_Bat_Dau, Gio_Ket_Thuc, So_Phong, Ghi_Chu, created_at, updated_at

#### 6. **phanconggiamthis** - Phân công giám thị
- Columns: id, exam_id (FK→lich_this), teacher_id (FK→giang_viens), phong_thi_id (FK→phong_this), role, created_at, updated_at

#### 7. **lich_thi_sinh_vien** - Danh sách sinh viên trong lịch thi
- Columns: id, lich_thi_id (FK→lich_this), mssv (FK→sinhviens), da_diem_danh, created_at, updated_at

#### 8. **admins** - Bảng admin (legacy, có thể không dùng)

#### 9. **sessions**, **cache**, **cache_locks** - Bảng hệ thống Laravel

---

### Cách 3: Reset database hoàn toàn (XÓA TẤT CẢ DỮ LIỆU)

⚠️ **CẢNH BÁO**: Các lệnh sau sẽ **XÓA TẤT CẢ DỮ LIỆU** trong database!

```bash
# Xóa tất cả bảng, tạo lại VÀ seed dữ liệu mẫu
# Lệnh này sẽ:
# 1. Drop tất cả các bảng hiện có
# 2. Chạy lại tất cả migrations để tạo bảng mới
# 3. Tự động seed dữ liệu mẫu (1 admin + 10 giảng viên + 50 sinh viên)
php artisan migrate:fresh --seed
```

**HOẶC** reset từng bước:

```bash
# Bước 1: Xóa tất cả bảng và tạo lại (KHÔNG seed)
php artisan migrate:fresh

# Bước 2: Seed dữ liệu mẫu (chỉ khi cần)
php artisan db:seed
```

**Thứ tự seed tự động:**
1. ✅ PhongThiSeeder → Tạo phòng thi
2. ✅ GiangVienSeeder → Tạo 50 giảng viên
3. ✅ SinhVienSeeder → Tạo 100 sinh viên
4. ✅ LichThiSeeder → Tạo lịch thi
5. ✅ PhanCongGiamThiSeeder → Phân công giám thị
6. ✅ LichThiSinhVienSeeder → Phân bổ sinh viên vào lịch thi
7. ✅ UserSeeder → Tạo tài khoản đăng nhập (1 Admin + 10 Giảng viên + 50 Sinh viên)

---

## 🚀 CHẠY DỰ ÁN

### 1. Build Frontend (một lần đầu tiên)
```bash
npm run build
```

### 2. Khởi động Laravel Development Server
```bash
# Chạy local (chỉ máy này truy cập được)
php artisan serve

# HOẶC cho phép máy khác truy cập qua mạng/ngrok
php artisan serve --host=0.0.0.0 --port=8000
```
Dự án sẽ chạy tại: **http://127.0.0.1:8000**

### 2.1. (Tùy chọn) Chia sẻ qua Ngrok
Nếu muốn chia sẻ dự án cho người khác qua internet:

**Bước 1**: Cài đặt Ngrok
- Download từ: https://ngrok.com/download
- Giải nén và copy `ngrok.exe` vào thư mục dự án hoặc thêm vào PATH

**Bước 2**: Chạy Laravel với host 0.0.0.0
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

**Bước 3**: Mở terminal mới, chạy Ngrok
```bash
ngrok http 8000
```

**Bước 4**: Copy URL ngrok (ví dụ: `https://abcd-1234-5678.ngrok-free.app`)

**Bước 5**: Update file `.env`
```env
# Thay đổi từ:
APP_URL=http://localhost

# Thành (thay URL ngrok của bạn):
APP_URL=https://abcd-1234-5678.ngrok-free.app
```

**Bước 6**: Clear config cache
```bash
php artisan config:clear
```

**Bước 7**: Chia sẻ URL ngrok cho người khác!

⚠️ **Lưu ý**: 
- Mỗi lần restart ngrok, URL sẽ thay đổi (bản free)
- Nhớ đổi lại `APP_URL=http://localhost` khi dùng local
- Ngrok free có giới hạn 40 connections/phút

### 3. (Tùy chọn) Chạy Vite Dev Server
Nếu đang phát triển và muốn hot-reload:
```bash
npm run dev
```

### 4. Đảm bảo XAMPP đang chạy
- ✅ Apache: Running
- ✅ MySQL: Running

---

## 🔐 TÀI KHOẢN MẶC ĐỊNH

Sau khi chạy `php artisan db:seed`, bạn có thể đăng nhập với các tài khoản sau:

### 👤 Admin:
- **Email**: admin@example.com
- **Password**: 123456
- **MSSV**: ADMIN001
- **Vai trò**: Quản trị viên hệ thống

### 👨‍🏫 Giảng viên (10 tài khoản):
- **Email**: giangvien1@example.com đến giangvien10@example.com
- **Password**: 123456
- **MSSV**: GV001 đến GV010
- **Vai trò**: Giảng viên/Giám thị

**Ví dụ đăng nhập:**
- Email: giangvien1@example.com / Password: 123456
- Email: giangvien5@example.com / Password: 123456

### 👨‍🎓 Sinh viên (50 tài khoản):
- **Email**: sinhvien1@example.com đến sinhvien50@example.com
- **Password**: 123456
- **MSSV**: Theo định dạng {NămKhóa}{Khoa}{STT}
  - Ví dụ: 2021CNTT001, 2022KTPM015, 2023KHMT032...
- **Vai trò**: Sinh viên

**Ví dụ đăng nhập:**
- Email: sinhvien1@example.com / Password: 123456
- Email: sinhvien20@example.com / Password: 123456

### 📊 Dữ liệu mẫu được tạo:
- ✅ **Bảng `giang_viens`**: 50 giảng viên
- ✅ **Bảng `sinhviens`**: 100 sinh viên (với thông tin: MSSV, họ tên, ngày sinh, lớp, khoa, bậc)
- ✅ **Bảng `users`**: 61 tài khoản (1 admin + 10 giảng viên + 50 sinh viên)
- ✅ **Bảng `phong_this`**: Danh sách phòng thi
- ✅ **Bảng `lich_this`**: Lịch thi với thông tin môn học, ngày, giờ, phòng
- ✅ **Bảng `phanconggiamthis`**: Phân công giám thị cho từng lịch thi
- ✅ **Bảng `lich_thi_sinh_vien`**: Danh sách sinh viên trong từng lịch thi

---

## ❌ XỬ LÝ LỖI THƯỜNG GẶP

### 1. Lỗi: "Class 'ZipArchive' not found"
**Nguyên nhân**: PHP Zip extension chưa được enable

**Giải pháp**:
```bash
# 1. Mở php.ini
notepad C:\xampp\php\php.ini

# 2. Tìm và bỏ comment dòng này:
extension=zip

# 3. Restart Apache trong XAMPP
# 4. Kiểm tra:
php -m | findstr zip
```

---

### 2. Lỗi: "SQLSTATE[HY000] [1045] Access denied"
**Nguyên nhân**: Thông tin database trong `.env` không đúng

**Giải pháp**:
```env
# Kiểm tra file .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=doanchuyennganh
DB_USERNAME=root
DB_PASSWORD=           # Để trống nếu không có password
```

---

### 3. Lỗi: "npm ERR! code ENOENT"
**Nguyên nhân**: Chưa cài đặt Node.js dependencies

**Giải pháp**:
```bash
npm install
```

---

### 4. Lỗi: "Vite manifest not found"
**Nguyên nhân**: Chưa build frontend

**Giải pháp**:
```bash
npm run build
```

---

### 5. Lỗi: "No application encryption key has been specified"
**Nguyên nhân**: Chưa tạo APP_KEY

**Giải pháp**:
```bash
php artisan key:generate
```

---

### 6. Lỗi: "Storage not writable"
**Nguyên nhân**: Folder storage không có quyền ghi

**Giải pháp** (Windows):
```bash
# Click phải vào folder storage -> Properties -> Security
# Thêm quyền "Full Control" cho user hiện tại
```

**Giải pháp** (Linux/Mac):
```bash
chmod -R 775 storage bootstrap/cache
```

---

### 7. Database connection refused
**Nguyên nhân**: MySQL chưa chạy

**Giải pháp**:
- Mở XAMPP Control Panel
- Start MySQL
- Kiểm tra port 3306 có bị chiếm không

---

## 📝 CHECKLIST CÀI ĐẶT

### Môi trường:
- [ ] XAMPP đã cài đặt và chạy (Apache + MySQL)
- [ ] PHP Zip extension đã enable (`php -m | findstr zip` hiển thị "zip")
- [ ] Composer đã cài đặt (`composer --version` chạy được)
- [ ] Node.js + npm đã cài đặt (`node -v` và `npm -v` chạy được)

### Cài đặt dự án:
- [ ] Đã copy/clone dự án vào máy
- [ ] Đã chạy `composer install` (hoặc `composer install --ignore-platform-req=ext-zip`)
- [ ] Đã chạy `npm install`
- [ ] File `.env` đã tồn tại và cấu hình đúng
- [ ] Đã chạy `php artisan key:generate`

### Database:
- [ ] Database `doanchuyennganh` đã tạo trong phpMyAdmin
- [ ] Đã chạy `php artisan migrate` (tạo bảng)
- [ ] Đã chạy `php artisan db:seed` (tạo dữ liệu mẫu)
- [ ] Kiểm tra phpMyAdmin thấy 9 bảng: users, giang_viens, sinhviens, phong_this, lich_this, phanconggiamthis, lich_thi_sinh_vien, admins, sessions, cache, cache_locks

### Build & Run:
- [ ] Đã chạy `npm run build` thành công
- [ ] Đã chạy `php artisan serve`
- [ ] Truy cập http://127.0.0.1:8000 thấy trang đăng nhập
- [ ] Đăng nhập được với admin@example.com / 123456

### Kiểm tra chức năng:
- [ ] Đăng nhập Admin thành công
- [ ] Thấy menu: Trang chủ, Lịch gác thi, Kết quả điểm danh, Quản lí giảng viên, Quản lí sinh viên, Đổi mật khẩu
- [ ] Xem được danh sách giảng viên (50 records)
- [ ] Xem được danh sách sinh viên (100 records)
- [ ] Xem được lịch thi
- [ ] Import Excel lịch thi hoạt động (nếu đã test)

---

## 🎯 CÁC LỆNH THƯỜNG DÙNG

### 🗄️ Database & Migration
```bash
# Chạy migration để tạo bảng
php artisan migrate

# Rollback migration gần nhất
php artisan migrate:rollback

# Reset tất cả migration
php artisan migrate:reset

# Xóa tất cả bảng và chạy lại migration
php artisan migrate:fresh

# Xóa tất cả bảng, chạy lại migration VÀ seed dữ liệu
php artisan migrate:fresh --seed

# Chỉ chạy seeder (không xóa dữ liệu cũ)
php artisan db:seed

# Chạy 1 seeder cụ thể
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=GiangVienSeeder
php artisan db:seed --class=SinhVienSeeder
```

### 🧹 Clear Cache
```bash
# Clear tất cả cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Clear compiled files
php artisan clear-compiled

# Clear application cache
php artisan optimize:clear
```

### 🔍 Debug & Info
```bash
# Xem danh sách routes
php artisan route:list

# Xem danh sách artisan commands
php artisan list

# Kiểm tra thông tin môi trường
php artisan about

# Xem database connections
php artisan db:show
```

### 🏗️ Build Frontend
```bash
# Build production (1 lần)
npm run build

# Dev mode với hot reload (khi đang phát triển)
npm run dev

# Clear node_modules và cài lại
rmdir /s /q node_modules
npm install
```

### 🔧 Maintenance
```bash
# Tạo symbolic link cho storage
php artisan storage:link

# Tạo Application Key mới
php artisan key:generate

# Chạy Laravel server
php artisan serve

# Chạy Laravel server với port tùy chỉnh
php artisan serve --port=8080
```

---

## 📞 HỖ TRỢ

Nếu gặp vấn đề, kiểm tra:
1. Laravel logs: `storage/logs/laravel.log`
2. Apache error log: `C:\xampp\apache\logs\error.log`
3. PHP error log: `C:\xampp\php\logs\php_error_log`

---

## ✅ HOÀN TẤT!

Sau khi làm theo các bước trên, dự án đã sẵn sàng chạy trên máy mới! 🎉

**Truy cập**: http://127.0.0.1:8000

**Đăng nhập** với tài khoản admin/giảng viên/sinh viên để bắt đầu sử dụng.
