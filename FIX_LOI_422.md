# 🐛 FIX LỖI 422 - THÊM LỊCH THI

## ❌ Lỗi gặp phải
```
Failed to load resource: the server responded with a status of 422 (Unprocessable Content)
❌ Lỗi khi lưu lịch thi
```

## 🔍 Nguyên nhân

### 1️⃣ **Thiếu field bắt buộc trong Form**
Backend validation yêu cầu:
- ✅ `MaMT` (Mã môn thi) - **required**, unique
- ✅ `Gio_Ket_Thuc` (Giờ kết thúc) - **required**

Nhưng frontend form **KHÔNG CÓ** 2 field này!

### 2️⃣ **Backend parse sai format input**
- Frontend gửi: `"2021CNTT001,2021CNTT002\n2021CNTT003"` (có xuống dòng)
- Backend chỉ parse: `explode(',', ...)` → **không nhận xuống dòng**

## ✅ Giải pháp đã áp dụng

### 1. Thêm field MaMT và Gio_Ket_Thuc vào Form

**File: `resources/js/Pages/Admin/Index.vue`**

#### Thêm vào `scheduleForm`:
```javascript
const scheduleForm = reactive({ 
  STT: '', 
  MaMT: '',           // ✅ THÊM MỚI
  Thu: '',  
  Ngay_Thi: '',   
  Gio_Bat_Dau: '', 
  Gio_Ket_Thuc: '',   // ✅ THÊM MỚI
  Mon_Hoc: '',  
  So_Phong: '',  
  DSSV: '', 
  DSGV: '',  
  Ghi_Chu: ''
})
```

#### Thêm vào HTML form:
```vue
<!-- MÃ MÔN THI -->
<div class="form-row">
  <label>Mã môn thi <span style="color: red;">*</span></label>
  <input v-model="scheduleForm.MaMT" placeholder="VD: MT001" required />
</div>

<!-- GIỜ KẾT THÚC -->
<div class="form-row">
  <label>Giờ kết thúc <span style="color: red;">*</span></label>
  <input type="time" v-model="scheduleForm.Gio_Ket_Thuc" required />
</div>
```

### 2. Sửa Backend Parse Newline

**File: `app/Http/Controllers/LichThiController.php`**

#### Hàm `addSchedule()` - Parse DSSV:
```php
// ❌ CŨ: Chỉ parse dấu phẩy
$mssvArray = array_filter(
    array_map('trim', explode(',', $validated['DSSV'])),
    fn($mssv) => !empty($mssv)
);

// ✅ MỚI: Parse cả dấu phẩy và xuống dòng
$mssvArray = array_filter(
    array_map('trim', preg_split('/[,\n]/', $validated['DSSV'])),
    fn($mssv) => !empty($mssv)
);
```

#### Hàm `addSchedule()` - Parse DSGV:
```php
// ❌ CŨ: Chỉ parse dấu phẩy
$magvArray = array_filter(
    array_map('trim', explode(',', $validated['DSGV'])),
    fn($magv) => !empty($magv)
);

// ✅ MỚI: Parse cả dấu phẩy và xuống dòng
$magvArray = array_filter(
    array_map('trim', preg_split('/[,\n]/', $validated['DSGV'])),
    fn($magv) => !empty($magv)
);
```

#### Áp dụng tương tự cho `updateSchedule()`

## 📋 Validation Rules

### Required Fields (Bắt buộc):
- ✅ `MaMT` - Mã môn thi (unique)
- ✅ `Mon_Hoc` - Môn học
- ✅ `Ngay_Thi` - Ngày thi (date format)
- ✅ `Gio_Bat_Dau` - Giờ bắt đầu
- ✅ `Gio_Ket_Thuc` - Giờ kết thúc

### Optional Fields (Tùy chọn):
- ⭕ `So_Phong` - Số phòng (phải tồn tại trong `phong_this`)
- ⭕ `Ghi_Chu` - Ghi chú
- ⭕ `DSSV` - Danh sách sinh viên
- ⭕ `DSGV` - Danh sách giảng viên

## 🧪 Test Validation

Chạy script test:
```bash
php test_validation.php
```

Kết quả mong đợi:
```
TEST 1: Missing MaMT → ❌ FAILED (expected)
TEST 2: Missing Gio_Ket_Thuc → ❌ FAILED (expected)
TEST 3: All required fields → ✅ PASSED
```

## 🔄 Rebuild Frontend

Sau khi sửa file `.vue`:
```bash
npm run build
```

## 📊 Kết quả

### Trước khi fix:
```
POST /schedules/add
Status: 422 Unprocessable Content
Error: The ma m t field is required
       The gio ket thuc field is required
```

### Sau khi fix:
```
POST /schedules/add
Status: 201 Created
✅ Thêm lịch thi thành công!
```

## 🎯 Checklist

- [x] Thêm field `MaMT` vào `scheduleForm`
- [x] Thêm field `Gio_Ket_Thuc` vào `scheduleForm`
- [x] Thêm input `MaMT` vào HTML form
- [x] Thêm input `Gio_Ket_Thuc` vào HTML form
- [x] Sửa `addSchedule()` parse xuống dòng cho DSSV
- [x] Sửa `addSchedule()` parse xuống dòng cho DSGV
- [x] Sửa `updateSchedule()` parse xuống dòng cho DSSV
- [x] Sửa `updateSchedule()` parse xuống dòng cho DSGV
- [x] Test validation rules
- [x] Rebuild frontend với `npm run build`

## 🚀 Hoàn tất!

Giờ form có thể:
✅ Nhập đầy đủ field bắt buộc
✅ Parse cả dấu phẩy và xuống dòng
✅ Toggle giữa mode MSSV/Tên và MaGV/Tên
✅ Validation chặt chẽ trước khi lưu
