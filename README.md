# HỆ THỐNG QUẢN LÝ LỊCH THI VÀ ĐIỂM DANH GIÁM THI

Hệ thống quản lý lịch thi, phân công giám thị và điểm danh sinh viên dự thi bằng mã QR.

## 🚀 Công Nghệ Sử Dụng

- **Backend**: Laravel 12.40.2 (PHP 8.2.29)
- **Frontend**: Vue 3 + Inertia.js + TailwindCSS 4
- **Database**: MySQL
- **QR Scanner**: html5-qrcode library

## 📋 Chức Năng Chính

### 1. Quản Trị Viên (Admin)
- ✅ Quản lý giảng viên (thêm, sửa, xóa)
- ✅ Quản lý sinh viên (thêm, sửa, xóa)
- ✅ Quản lý phòng thi
- ✅ Tạo và quản lý lịch thi
- ✅ Phân công giám thị cho ca thi
- ✅ Xem thống kê điểm danh
- ✅ Gửi email thông báo lịch gác tự động

### 2. Giảng Viên (Giám Thị)
- ✅ Xem lịch gác thi được phân công
- ✅ Xác nhận/Từ chối lịch gác
- ✅ **Điểm danh sinh viên dự thi (2 phương thức)**:
  - 📱 **Quét mã QR**: Quét QR code của sinh viên bằng camera
  - ⌨️ **Nhập MSSV**: Nhập thủ công mã số sinh viên
- ✅ Xem danh sách sinh viên đã/chưa điểm danh
- ✅ Xem thông tin chi tiết sinh viên
- ✅ Xuất báo cáo điểm danh

### 3. Trang Điểm Danh Sinh Viên
- ✅ Chọn ca thi từ dropdown
- ✅ Hiển thị thống kê: Tổng số/Đã điểm danh/Chưa điểm danh
- ✅ Bộ lọc 3 trạng thái:
  - 📋 Tất cả sinh viên
  - ✅ Đã điểm danh
  - ⚠️ Chưa điểm danh
- ✅ Hiển thị phương thức điểm danh (QR/Manual)
- ✅ Hiển thị thời gian điểm danh
- ✅ Màu sắc phân biệt trạng thái (xanh/cam)

## 🔄 Flow Hoạt Động

### A. Quy Trình Tạo Lịch Thi (Admin)
```
1. Admin tạo ca thi mới
   ├─ Chọn môn thi, phòng thi, thời gian
   ├─ Thêm danh sách sinh viên dự thi (theo MSSV)
   └─ Phân công giảng viên giám thị

2. Hệ thống gửi email thông báo
   ├─ Email cho giảng viên được phân công
   └─ Thông tin: Môn, phòng, thời gian, danh sách sinh viên

3. Giảng viên xác nhận lịch gác
   ├─ Đồng ý: Lịch được kích hoạt
   └─ Từ chối: Admin được thông báo để phân công lại
```

### B. Quy Trình Điểm Danh Sinh Viên (Giảng Viên)

#### Phương thức 1: Quét mã QR 📱
```
1. Giảng viên vào trang "Lịch gác thi"
   └─ Chọn ca thi cần điểm danh

2. Click nút "📝 Điểm danh" trên ca thi
   └─ Modal điểm danh hiện ra với 2 tab

3. Tab "Quét QR" (mặc định)
   ├─ Camera tự động bật
   ├─ Giảng viên quét QR code của sinh viên
   └─ QR format: MSSV_TEN_NGAYSINH
       Ví dụ: 2021CNTT056_LUNGUYETCHI_06.08.1985

4. Hệ thống xử lý QR code
   ├─ Parse QR data → Lấy MSSV
   ├─ Gọi API: GET /giangvien/sinh-vien/{mssv}
   └─ Trả về thông tin đầy đủ sinh viên

5. Hiển thị popup xác nhận
   ├─ Thông tin: MSSV, Họ tên, Lớp, Email, Ngày sinh
   ├─ Nút "Xác nhận điểm danh"
   └─ Nút "Hủy"

6. Xác nhận điểm danh
   ├─ Gọi API: POST /giangvien/diem-danh
   │   Body: {
   │     lich_thi_id: XX,
   │     mssv: "2021CNTT056",
   │     phuong_thuc: "qr_code"
   │   }
   ├─ Backend validate:
   │   ├─ Sinh viên có trong danh sách thi?
   │   ├─ Đã điểm danh chưa?
   │   └─ Lưu vào database
   └─ Cập nhật UI real-time (X/Y tăng lên)

7. Tiếp tục quét sinh viên tiếp theo
```

#### Phương thức 2: Nhập MSSV ⌨️
```
1. Giảng viên chuyển sang tab "Nhập MSSV"
   
2. Nhập mã số sinh viên vào ô input
   └─ Ví dụ: 2021CNTT056

3. Click nút "Tra cứu"
   ├─ Gọi API: GET /giangvien/sinh-vien/{mssv}
   └─ Hiển thị popup xác nhận (giống quét QR)

4. Flow xác nhận tương tự phương thức QR
   └─ Chỉ khác phuong_thuc: "manual"
```

### C. Xem Danh Sách Điểm Danh

#### Từ Nút "👥 Xem DS" (Quick View)
```
1. Click nút "👥 Xem DS" trên bảng lịch gác
   
2. Modal danh sách sinh viên hiện ra
   ├─ Hiển thị tất cả sinh viên trong ca thi
   ├─ Cột: STT, MSSV, Họ tên, Lớp, Trạng thái
   ├─ Badge màu:
   │   ├─ ✅ Xanh: Đã điểm danh
   │   └─ ⏳ Xám: Chưa điểm danh
   └─ Không có phương thức/thời gian
```

#### Từ Trang "Điểm Danh Sinh Viên" (Full View)
```
1. Vào tab "Điểm danh sinh viên" trên menu

2. Chọn ca thi từ dropdown
   ├─ Dropdown chỉ hiển thị ca thi đã xác nhận
   └─ Format: "Tên môn - Phòng - Thời gian"

3. Hiển thị thống kê tổng quan
   ┌──────────────────────────────────┐
   │  📊 Tổng số: 25                  │
   │  ✅ Đã điểm danh: 18 (72%)       │
   │  ⚠️ Chưa điểm danh: 7 (28%)      │
   └──────────────────────────────────┘

4. Bộ lọc 3 tab
   ├─ 📋 Tất cả (25 sinh viên)
   ├─ ✅ Đã điểm danh (18 sinh viên)
   └─ ⚠️ Chưa điểm danh (7 sinh viên)

5. Bảng chi tiết
   ┌─────┬────────────┬──────────────┬────────┬──────────┬────────────┬──────────┐
   │ STT │ MSSV       │ Họ và tên    │ Lớp    │ Trạng thái│ Thời gian  │ P.thức   │
   ├─────┼────────────┼──────────────┼────────┼──────────┼────────────┼──────────┤
   │ 1   │2021CNTT056│Lư Nguyệt Chi │CNTT2021│ ✅ Đã DD │ 09:15:30   │ 📱 QR    │
   │ 2   │2021CNTT072│Cụ. Chu Xuân  │CNTT2021│ ✅ Đã DD │ 09:16:45   │ ⌨️ Thủ công│
   │ 3   │2021CNTT098│Bạch Dân      │CNTT2021│ ⏳ Chưa  │ -          │ -        │
   └─────┴────────────┴──────────────┴────────┴──────────┴────────────┴──────────┘
   
   Màu nền:
   ├─ 🟢 Xanh nhạt: Đã điểm danh
   └─ 🟠 Cam nhạt: Chưa điểm danh
```

## 🔐 Thông Tin Đăng Nhập Mặc Định

### Admin
- Email: `admin@example.com`
- Password: `123456`

### Giảng Viên
- Email: `gv1@hcmus.edu.vn` (hoặc xem `HUONG_DAN_CAI_DAT_MAY_MOI.md`)
- Password: `123456`

## 📱 Tạo Mã QR Cho Sinh Viên

### Định dạng QR Code
```
Format: MSSV_TEN_NGAYSINH
- MSSV: Mã số sinh viên (ví dụ: 2021CNTT056)
- TEN: Họ tên KHÔNG DẤU, VIẾT HOA, KHÔNG KHOẢNG TRẮNG
- NGAYSINH: DD.MM.YYYY

Ví dụ:
- 2021CNTT056_LUNGUYETCHI_06.08.1985
- 2021CNTT072_CHUXUAN_05.12.1977
- DH52200662_NGUYENMINHHIEN_30.09.2004
```

### Script Tự Động Generate QR
```bash
# Tạo QR cho 3 sinh viên đầu tiên
python generate_qr_codes.py

# Output: qr_codes/
# - 2021CNTT056.png
# - 2021CNTT072.png
# - 2021CNTT098.png
```

## 🛠️ Cài Đặt

Xem hướng dẫn chi tiết trong file `HUONG_DAN_CAI_DAT_MAY_MOI.md`

### Yêu cầu
- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL
- Python 3.x (để tạo QR codes)

### Cài đặt nhanh
```bash
# 1. Clone repository
git clone <repo-url>
cd DoAn-master

# 2. Cài dependencies
composer install
npm install

# 3. Setup môi trường
cp .env.example .env
php artisan key:generate

# 4. Tạo database
mysql -u root -e "CREATE DATABASE doanchuyennganh"

# 5. Chạy migration + seeder
php artisan migrate:fresh --seed

# 6. Build frontend
npm run build

# 7. Khởi động server
php artisan serve
```

## 📊 Database Schema

### Bảng chính

#### `sinhviens` - Sinh viên
- `Mssv` (PK): Mã số sinh viên
- `Ho_va_ten`: Họ và tên
- `Email`: Email sinh viên
- `Ngay_Sinh`: Ngày sinh
- `Lop`: Lớp
- `Khoa`: Khoa

#### `giang_viens` - Giảng viên
- `id` (PK): ID giảng viên
- `ten`: Họ và tên
- `email`: Email
- `khoa`: Khoa

#### `lich_this` - Lịch thi
- `id` (PK): ID lịch thi
- `ten_mon`: Tên môn thi
- `phong_thi_id`: ID phòng thi
- `ngay_thi`: Ngày thi
- `gio_bat_dau`: Giờ bắt đầu
- `gio_ket_thuc`: Giờ kết thúc

#### `lich_thi_sinh_vien` - Sinh viên tham gia thi
- `id` (PK): ID
- `lich_thi_id`: ID lịch thi
- `mssv`: Mã số sinh viên
- `da_diem_danh`: Boolean (đã điểm danh hay chưa)
- `thoi_gian_diem_danh`: Thời gian điểm danh
- `phuong_thuc_diem_danh`: 'qr_code' hoặc 'manual'

#### `phan_cong_giam_this` - Phân công giám thị
- `id` (PK): ID
- `lich_thi_id`: ID lịch thi
- `giang_vien_id`: ID giảng viên
- `trang_thai`: 'pending'/'confirmed'/'rejected'

## 🎯 API Endpoints

### Điểm danh
```http
# Lấy thông tin sinh viên theo MSSV
GET /giangvien/sinh-vien/{mssv}
Response: {
  "Mssv": "2021CNTT056",
  "Ho_va_ten": "Lư Nguyệt Chi",
  "Email": "2021cntt056@student.hcmus.edu.vn",
  "Ngay_Sinh": "1985-08-06",
  "Lop": "CNTT2021",
  "Khoa": "CNTT"
}

# Điểm danh sinh viên
POST /giangvien/diem-danh
Body: {
  "lich_thi_id": 1,
  "mssv": "2021CNTT056",
  "phuong_thuc": "qr_code" // hoặc "manual"
}
Response: {
  "success": true,
  "message": "Điểm danh thành công"
}

# Lấy danh sách sinh viên theo lịch thi
GET /giangvien/lich-thi/{id}/sinh-vien
Response: [
  {
    "Mssv": "2021CNTT056",
    "Ho_va_ten": "Lư Nguyệt Chi",
    "Lop": "CNTT2021",
    "da_diem_danh": true,
    "thoi_gian_diem_danh": "2025-11-29 09:15:30",
    "phuong_thuc_diem_danh": "qr_code"
  },
  ...
]
```

## 📝 License

MIT License
