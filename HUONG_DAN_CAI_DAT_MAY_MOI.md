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

### Cách 1: Tạo database mới (Nếu chưa có database)

#### Bước 1: Tạo database
1. Mở trình duyệt, truy cập: http://localhost/phpmyadmin
2. Click tab **"SQL"**
3. Chạy lệnh:
```sql
CREATE DATABASE doanchuyennganh CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Bước 2: Chạy Migration
```bash
# Tạo các bảng trong database
php artisan migrate
```

#### Bước 3: Seed dữ liệu mẫu (nếu có)
```bash
php artisan db:seed
```

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
```

---

### Cách 3: Reset database hoàn toàn

```bash
# Xóa tất cả bảng và tạo lại từ đầu
php artisan migrate:fresh

# Xóa tất cả bảng, tạo lại VÀ seed dữ liệu mẫu
php artisan migrate:fresh --seed
```

⚠️ **CẢNH BÁO**: Lệnh này sẽ **XÓA TẤT CẢ DỮ LIỆU** trong database!

---

## 🚀 CHẠY DỰ ÁN

### 1. Build Frontend (một lần đầu tiên)
```bash
npm run build
```

### 2. Khởi động Laravel Development Server
```bash
php artisan serve
```
Dự án sẽ chạy tại: **http://127.0.0.1:8000**

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

Sau khi seed database, bạn có thể đăng nhập với:

### Admin:
- **Email**: admin@example.com
- **Password**: password

### Giảng viên:
- **Email**: (xem trong bảng `giang_viens` hoặc `users`)
- **Password**: password

### Sinh viên:
- **Email**: (xem trong bảng `sinh_viens` hoặc `users`)
- **Password**: password

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

- [ ] XAMPP đã cài đặt và chạy (Apache + MySQL)
- [ ] PHP Zip extension đã enable
- [ ] Composer đã cài đặt
- [ ] Node.js + npm đã cài đặt
- [ ] Đã chạy `composer install`
- [ ] Đã chạy `npm install`
- [ ] File `.env` đã cấu hình đúng
- [ ] Database `doanchuyennganh` đã tạo
- [ ] Đã chạy migration hoặc import SQL
- [ ] Đã chạy `npm run build`
- [ ] Đã chạy `php artisan serve`
- [ ] Truy cập http://127.0.0.1:8000 thành công

---

## 🎯 CÁC LỆNH THƯỜNG DÙNG

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Xem danh sách routes
php artisan route:list

# Chạy migration
php artisan migrate

# Reset database
php artisan migrate:fresh --seed

# Build production
npm run build

# Dev mode với hot reload
npm run dev
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
