# 🚀 HƯỚNG DẪN CHẠY DỰ ÁN - HỆ THỐNG QUẢN LÝ LỊCH THI

## 📋 Mục lục
1. [Yêu cầu hệ thống](#yêu-cầu-hệ-thống)
2. [Cài đặt môi trường](#cài-đặt-môi-trường)
3. [Cài đặt dự án](#cài-đặt-dự-án)
4. [Chạy ứng dụng](#chạy-ứng-dụng)
5. [Xử lý lỗi thường gặp](#xử-lý-lỗi-thường-gặp)

---

## 📌 Yêu cầu hệ thống

### Phần mềm cần thiết:
- ✅ **PHP 8.2+** (hiện tại đang dùng PHP 8.3.28)
- ✅ **Composer** (quản lý package PHP)
- ✅ **Node.js & npm** (v16+ khuyến nghị)
- ✅ **MySQL/MariaDB** (database)
- ✅ **Python 3.x** (cho AI nhận diện khuôn mặt)
- ✅ **XAMPP** (hoặc Laragon)

### Công nghệ sử dụng:
- **Backend**: Laravel 12
- **Frontend**: Vue.js 3 + Inertia.js
- **CSS**: TailwindCSS 4
- **AI**: Flask + TensorFlow + OpenCV
- **Database**: MySQL

---

## 🔧 PHẦN 1: CÀI ĐẶT MÔI TRƯỜNG

### Bước 1: Cài đặt XAMPP

1. **Download XAMPP** có PHP 8.2+:
   - Link: https://www.apachefriends.org/download.html
   - Chọn phiên bản Windows với PHP 8.2 hoặc 8.3

2. **Cài đặt XAMPP**:
   - Chạy file setup
   - Chọn thư mục cài đặt: `D:\Xampp` (hoặc tùy chọn)
   - Tick: Apache, MySQL, PHP

### Bước 2: Cấu hình PHP

1. **Mở file php.ini**:
   ```
   D:\Xampp\php\php.ini
   ```

2. **Kiểm tra extension_dir** (dòng ~767):
   ```ini
   extension_dir = "D:\Xampp\php\ext"
   ```

3. **Bật các extensions cần thiết** (bỏ dấu `;` trước):
   ```ini
   extension=curl
   extension=fileinfo
   extension=gd
   extension=mbstring
   extension=mysqli
   extension=openssl
   extension=pdo_mysql
   extension=zip
   extension=intl
   ```

4. **Tăng giới hạn** (tùy chọn):
   ```ini
   memory_limit = 512M
   upload_max_filesize = 64M
   post_max_size = 64M
   max_execution_time = 300
   ```

5. **Lưu file và restart XAMPP**

6. **Kiểm tra PHP**:
   ```bash
   D:\Xampp\php\php.exe -v
   D:\Xampp\php\php.exe -m
   ```

### Bước 3: Cài đặt Composer

1. **Download Composer**:
   - Link: https://getcomposer.org/download/
   - Chọn: `Composer-Setup.exe`

2. **Cài đặt**:
   - Chọn PHP path: `D:\Xampp\php\php.exe`
   - Next → Install

3. **Kiểm tra**:
   ```bash
   composer --version
   ```

### Bước 4: Cài đặt Node.js

1. **Download Node.js LTS**:
   - Link: https://nodejs.org/
   - Chọn phiên bản LTS (v20+)

2. **Cài đặt**:
   - Chạy setup → Next → Install
   - Tick: "Automatically install necessary tools"

3. **Kiểm tra**:
   ```bash
   node -v
   npm -v
   ```

### Bước 5: Cài đặt Python (cho AI)

1. **Download Python**:
   - Link: https://www.python.org/downloads/
   - Chọn phiên bản 3.10+ (khuyến nghị 3.11)

2. **Cài đặt**:
   - **QUAN TRỌNG**: Tick "Add Python to PATH"
   - Chọn "Install Now"

3. **Kiểm tra**:
   ```bash
   python --version
   pip --version
   ```

---

## 💾 PHẦN 2: CÀI ĐẶT DỰ ÁN

### Bước 1: Clone/Copy dự án

```bash
# Giả sử dự án đã có tại:
cd d:\An\DoAn-master\DoAn-master
```

### Bước 2: Cài đặt Dependencies PHP

```bash
# Xóa folder vendor cũ (nếu có)
rmdir /s /q vendor
del composer.lock

# Cài đặt dependencies
composer install
```

**Lưu ý**: Quá trình này sẽ mất 3-5 phút.

### Bước 3: Cấu hình môi trường

1. **Copy file .env**:
   ```bash
   copy .env.example .env
   ```

2. **Chỉnh sửa file `.env`**:
   ```env
   APP_NAME=Laravel
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=doanchuyennganh
   DB_USERNAME=root
   DB_PASSWORD=210506

   SESSION_DRIVER=database
   QUEUE_CONNECTION=database
   ```

3. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

### Bước 4: Tạo Database

1. **Mở XAMPP Control Panel**:
   - Start **Apache**
   - Start **MySQL**

2. **Truy cập phpMyAdmin**:
   - URL: http://localhost/phpmyadmin
   - Hoặc click "Admin" bên MySQL trong XAMPP

3. **Tạo database**:
   - Click tab "SQL"
   - Chạy lệnh:
   ```sql
   CREATE DATABASE doanchuyennganh 
   CHARACTER SET utf8mb4 
   COLLATE utf8mb4_unicode_ci;
   ```

### Bước 5: Chạy Migration

```bash
php artisan migrate:fresh
```

**Lưu ý**: 
- Lệnh `migrate:fresh` sẽ xóa toàn bộ bảng cũ và tạo lại
- Nếu muốn giữ dữ liệu cũ, dùng: `php artisan migrate`
- **Nếu gặp lỗi foreign key**: Đã được fix trong migrations

### Bước 6: Tạo tài khoản test

**Cách 1 - Dùng script có sẵn (Khuyến nghị):**

```bash
php create_users.php
```

**Cách 2 - Dùng Artisan Tinker:**

```bash
php artisan tinker
```

Trong tinker, chạy từng dòng:

```php
\App\Models\User::create(['Mssv' => 'ADMIN001', 'Ho_va_Ten' => 'Quản Trị Viên', 'email' => 'admin@example.com', 'password' => bcrypt('123456'), 'role' => 'Admin']);

\App\Models\User::create(['Mssv' => 'GV001', 'Ho_va_Ten' => 'Nguyễn Văn A', 'email' => 'giangvien@example.com', 'password' => bcrypt('123456'), 'role' => 'GiangVien']);

\App\Models\User::create(['Mssv' => 'SV001', 'Ho_va_Ten' => 'Trần Thị B', 'email' => 'sinhvien@example.com', 'password' => bcrypt('123456'), 'role' => 'SinhVien']);

exit
```

**Kết quả**: Tạo 3 tài khoản test cho Admin, Giảng viên và Sinh viên

### Bước 7: Cài đặt Node.js Dependencies

```bash
npm install
```

**Lưu ý**: Quá trình này sẽ mất 2-3 phút.

### Bước 8: Cài đặt Python Dependencies (cho AI - Tùy chọn)

⚠️ **LƯU Ý QUAN TRỌNG về AI Service:**
- Tính năng AI Face Recognition yêu cầu model đã được train
- Cần có file `ai_model/face_recognition_cnn.keras` và `ai_model/label_classes.npy`
- Python 3.13 có xung đột packages, khuyến nghị Python 3.11 hoặc 3.10
- **Nếu chưa có model, có thể bỏ qua bước này**. Laravel vẫn chạy bình thường.

**Cài đặt cho Python 3.13:**
```bash
pip install -r requirements.txt
```

Hoặc cài thủ công:
```bash
pip install tensorflow==2.20.0 --upgrade
pip install opencv-python --upgrade
pip install flask flask-cors pillow
```

**Lỗi thường gặp:**
- NumPy/OpenCV xung đột → Đã fix với TensorFlow 2.20.0 + OpenCV 4.12+
- Model không tồn tại → Cần train model hoặc bỏ qua AI service

---

## ▶️ PHẦN 3: CHẠY ỨNG DỤNG

### Cách 1: Chạy từng service riêng (Khuyến nghị cho Development)

**Terminal 1 - Laravel Backend:**
```bash
cd d:\An\DoAn-master\DoAn-master
php artisan serve
```
→ Chạy tại: http://127.0.0.1:8000

**Terminal 2 - Vite Frontend:**
```bash
cd d:\An\DoAn-master\DoAn-master
npm run dev
```
→ Hot reload cho Vue.js

**Terminal 3 - Queue Worker:**
```bash
cd d:\An\DoAn-master\DoAn-master
php artisan queue:listen
```
→ Xử lý background jobs

**Terminal 4 - Flask AI Service (Tùy chọn):**
```bash
cd d:\An\DoAn-master\DoAn-master
python app.py
```
→ Chạy tại: http://127.0.0.1:5000
→ **Chỉ chạy khi đã có model AI được train**

### Cách 2: Chạy nhanh với Composer script

```bash
composer run dev
```

Lệnh này sẽ chạy đồng thời:
- Laravel server (port 8000)
- Queue worker
- Vite dev server

**Lưu ý**: Vẫn cần chạy riêng Flask AI nếu dùng chức năng nhận diện khuôn mặt.

### Cách 3: Build Production

```bash
# Build assets
npm run build

# Chạy Laravel với Apache (XAMPP)
# Copy dự án vào: D:\Xampp\htdocs\
# Truy cập: http://localhost/DoAn-master/public
```

---

## 🌐 TRUY CẬP ỨNG DỤNG

### URL:
- **Frontend**: http://127.0.0.1:8000
- **AI Service**: http://127.0.0.1:5000
- **phpMyAdmin**: http://localhost/phpmyadmin

### Tài khoản đăng nhập:

| Role | Email | Password | Quyền |
|------|-------|----------|-------|
| **Admin** | admin@example.com | 123456 | Quản lý toàn bộ hệ thống |
| **Giảng viên** | giangvien@example.com | 123456 | Xem lịch gác, điểm danh |
| **Sinh viên** | sinhvien@example.com | 123456 | Xem lịch thi, điểm danh AI |

---

## 🎯 CHỨC NĂNG CHÍNH

### Admin:
- ✅ Quản lý giảng viên (Thêm, Sửa, Xóa, Import Excel)
- ✅ Quản lý sinh viên (Thêm, Sửa, Xóa, Import Excel)
- ✅ Quản lý lịch thi (Tạo, Chỉnh sửa, Xóa)
- ✅ Quản lý phòng thi
- ✅ Phân công giám thi tự động/thủ công
- ✅ Xuất báo cáo Excel/PDF

### Giảng viên:
- ✅ Xem thông tin cá nhân
- ✅ Xem lịch gác thi được phân công
- ✅ Xem danh sách sinh viên dự thi
- ✅ Xem kết quả điểm danh
- ✅ Đổi mật khẩu

### Sinh viên:
- ✅ Xem thông tin cá nhân
- ✅ Xem lịch thi của mình
- ✅ Điểm danh bằng AI (nhận diện khuôn mặt)
- ✅ Xem lịch sử điểm danh
- ✅ Đổi mật khẩu

---

## 🐛 PHẦN 4: XỬ LÝ LỖI THƯỜNG GẶP

### Lỗi 1: "No application encryption key"

**Nguyên nhân**: Chưa generate APP_KEY

**Giải pháp**:
```bash
php artisan key:generate
```

### Lỗi 2: "Class not found" hoặc autoload issues

**Giải pháp**:
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Lỗi 3: MySQL không start được (Port 3306 bị chiếm)

**Cách 1 - Tắt MySQL khác**:
- Win + R → `services.msc`
- Tìm "MySQL" hoặc "MySQL80" → Stop service

**Cách 2 - Đổi port MySQL**:
1. XAMPP → Config (MySQL) → my.ini
2. Tìm: `port=3306` → Đổi thành `port=3307`
3. Trong `.env`: `DB_PORT=3307`

### Lỗi 4: "Access denied for user 'root'@'localhost'"

**Giải pháp**:
- Kiểm tra `DB_PASSWORD` trong `.env`
- Mật khẩu mặc định XAMPP: để trống hoặc `root`
- Đổi trong `.env`: `DB_PASSWORD=`

### Lỗi 5: Vite không chạy hoặc lỗi CSS

**Giải pháp**:
```bash
# Xóa node_modules và reinstall
rmdir /s /q node_modules
del package-lock.json
npm install

# Hoặc clear cache
npm run build
```

### Lỗi 6: Queue jobs không chạy

**Giải pháp**:
```bash
# Chạy queue worker
php artisan queue:listen

# Hoặc restart queue
php artisan queue:restart
```

### Lỗi 7: Session/Cache issues

**Giải pháp**:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan session:clear
```

### Lỗi 8: Permission denied (Windows)

**Giải pháp**:
```bash
# Chạy CMD as Administrator
php artisan storage:link
```

### Lỗi 9: Python/Flask không chạy

**Kiểm tra**:
```bash
# Kiểm tra Python
python --version

# Kiểm tra thư viện
pip list | findstr flask
pip list | findstr tensorflow

# Reinstall nếu thiếu
pip install flask flask-cors numpy tensorflow opencv-python pillow
```

### Lỗi 10: Không tìm thấy model AI

**Giải pháp**:
- Kiểm tra có file: `ai_model/face_recognition_cnn.keras`
- Kiểm tra có file: `ai_model/label_classes.npy`
- Nếu thiếu: Train lại model hoặc xin từ người quản lý dự án

### Lỗi 11: Migration foreign key constraint fails

**Nguyên nhân**: Thứ tự chạy migrations không đúng hoặc thiếu cột id

**Giải pháp**:
```bash
# Đã được fix: 
# - File phong_this migration đã được đổi tên để chạy trước lich_this
# - Đã thêm cột id() vào các bảng: giang_viens, lich_this
# - Nếu vẫn lỗi, chạy lại:
php artisan migrate:fresh
```

### Lỗi 12: "Field 'Mssv' doesn't have a default value"

**Nguyên nhân**: Model User thiếu các fields trong $fillable

**Giải pháp**: Đã được fix trong `app/Models/User.php`
- Đã thêm: `Mssv`, `Ho_va_Ten`, `role` vào $fillable

### Lỗi 13: PHP Warning về oci8_12c hoặc pdo_firebird

**Nguyên nhân**: Extensions này không cần thiết cho Laravel

**Giải pháp**:
- Có thể bỏ qua warnings này
- Hoặc comment lại trong php.ini:
```ini
;extension=oci8_12c
;extension=oci8_19
;extension=pdo_firebird
```

---

## 📁 CẤU TRÚC THỦ MỤC QUAN TRỌNG

```
DoAn-master/
├── app/
│   ├── Http/Controllers/     # Controllers
│   └── Models/              # Database Models
├── resources/
│   ├── js/                  # Vue.js components
│   └── views/               # Blade templates
├── routes/
│   └── web.php              # Routes definition
├── database/
│   └── migrations/          # Database migrations
├── public/                  # Public assets
├── ai_model/                # AI model files
├── .env                     # Environment config
├── composer.json            # PHP dependencies
├── package.json             # Node dependencies
├── create_users.php         # Script tạo users test (tiện lợi)
└── HUONG_DAN_CHAY_DU_AN.md  # File hướng dẫn này
```

---

## 🔐 BẢO MẬT

### Lưu ý quan trọng:

1. **Đổi mật khẩu mặc định** trước khi deploy production
2. **Không commit file `.env`** lên Git
3. **APP_DEBUG=false** khi production
4. **Sử dụng HTTPS** khi deploy lên server
5. **Backup database** thường xuyên

---

## 📞 HỖ TRỢ

### Nếu gặp vấn đề:

1. **Kiểm tra logs**:
   - Laravel: `storage/logs/laravel.log`
   - Flask: Terminal output
   - MySQL: XAMPP Control Panel → Logs

2. **Clear cache**:
   ```bash
   php artisan optimize:clear
   ```

3. **Restart services**:
   - Restart XAMPP (Apache + MySQL)
   - Restart terminal và chạy lại commands

4. **Kiểm tra requirements**:
   ```bash
   php -v       # PHP 8.2+
   composer -v  # Composer
   node -v      # Node.js 16+
   npm -v       # npm
   python -v    # Python 3.10+
   ```

---

## 📝 GHI CHÚ

### Thông tin phiên bản:
- **PHP**: 8.3.28
- **Laravel**: 12.33.0
- **Vue**: 3.x
- **Inertia**: 2.0.10
- **TailwindCSS**: 4.x
- **Python**: 3.10+
- **Flask**: Latest

### Ngày cập nhật: 20/11/2025

---

## ✅ CHECKLIST HOÀN THÀNH

### Setup lần đầu:
- [x] Đã cài XAMPP với PHP 8.2+
- [x] Đã cấu hình php.ini (extension_dir + extensions)
- [x] Đã cài Composer
- [x] Đã cài Node.js & npm
- [ ] Đã cài Python & pip
- [x] Đã clone/copy dự án
- [x] Đã chạy `composer install`
- [x] Đã copy và config `.env`
- [x] Đã chạy `php artisan key:generate`
- [x] Đã tạo database `doanchuyennganh`
- [x] Đã chạy `php artisan migrate:fresh`
- [x] Đã tạo tài khoản test (3 users)
- [ ] Đã chạy `npm install`
- [ ] Đã cài Python packages cho AI

### Chạy hàng ngày:
- [ ] Start Apache & MySQL trong XAMPP
- [ ] Chạy `php artisan serve`
- [ ] Chạy `npm run dev`
- [ ] Chạy `php artisan queue:listen` (nếu cần)
- [ ] Chạy `python app.py` (nếu dùng AI)

---

**🎉 CHÚC BẠN THÀNH CÔNG! 🎉**

Nếu có vấn đề, tham khảo phần "Xử lý lỗi thường gặp" ở trên.
