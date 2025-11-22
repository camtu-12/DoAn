# 🎯 DEMO: TOGGLE MODE NHẬP DANH SÁCH

## 📸 GIAO DIỆN MỚI

### **Trạng thái 1: Mode MSSV (Mặc định)**
```
┌─────────────────────────────────────────────────────┐
│  THÊM LỊCH GÁC THI                         [X]      │
├─────────────────────────────────────────────────────┤
│                                                      │
│  Mã môn thi:    [MT9999_______________]             │
│  Môn học:       [Toán cao cấp_________]             │
│  Ngày thi:      [2025-12-20___________]             │
│  Giờ bắt đầu:   [08:00________________]             │
│  Giờ kết thúc:  [10:00________________]             │
│  Số phòng:      [1____________________]             │
│                                                      │
│  Danh sách sinh viên    [📋 Nhập theo MSSV]         │
│  ┌──────────────────────────────────────────┐       │
│  │ VD: 2021CNTT001, 2021CNTT002 hoặc mỗi   │       │
│  │ MSSV 1 dòng                              │       │
│  │                                          │       │
│  │                                          │       │
│  └──────────────────────────────────────────┘       │
│  📊 0 sinh viên                                     │
│                                                      │
│  Danh sách giảng viên   [📋 Nhập theo Mã]          │
│  ┌──────────────────────────────────────────┐       │
│  │ VD: GV001, GV002 hoặc mỗi mã 1 dòng     │       │
│  │                                          │       │
│  └──────────────────────────────────────────┘       │
│  📊 0 giảng viên                                    │
│                                                      │
│  Ghi chú:                                           │
│  ┌──────────────────────────────────────────┐       │
│  │                                          │       │
│  └──────────────────────────────────────────┘       │
│                                                      │
│  [Lưu]  [Hủy]                                       │
│                                                      │
└─────────────────────────────────────────────────────┘
```

---

### **Trạng thái 2: Mode Tên (Sau khi click toggle)**
```
┌─────────────────────────────────────────────────────┐
│  THÊM LỊCH GÁC THI                         [X]      │
├─────────────────────────────────────────────────────┤
│                                                      │
│  [... các trường khác ...]                          │
│                                                      │
│  Danh sách sinh viên    [👤 Nhập theo Tên]         │
│  ┌──────────────────────────────────────────┐       │
│  │ VD: Nguyễn Văn A, Trần Thị B hoặc mỗi   │       │
│  │ tên 1 dòng                               │       │
│  │                                          │       │
│  │                                          │       │
│  └──────────────────────────────────────────┘       │
│  📊 0 sinh viên                                     │
│                                                      │
│  Danh sách giảng viên   [👤 Nhập theo Tên]         │
│  ┌──────────────────────────────────────────┐       │
│  │ VD: Nguyễn Văn X, Trần Thị Y hoặc mỗi   │       │
│  │ tên 1 dòng                               │       │
│  └──────────────────────────────────────────┘       │
│  📊 0 giảng viên                                    │
│                                                      │
│  [... phần còn lại ...]                             │
│                                                      │
└─────────────────────────────────────────────────────┘
```

---

## 📝 VÍ DỤ NHẬP LIỆU

### **Ví dụ 1: Nhập MSSV với dấu phẩy**
```
Input trong textarea:
┌──────────────────────────────────────────┐
│ 2021CNTT001, 2021CNTT002, 2021CNTT003    │
│                                          │
└──────────────────────────────────────────┘

Hiển thị bên dưới:
📊 3 sinh viên
```

---

### **Ví dụ 2: Nhập MSSV xuống dòng**
```
Input trong textarea:
┌──────────────────────────────────────────┐
│ 2021CNTT001                              │
│ 2021CNTT002                              │
│ 2021CNTT003                              │
│ 2021CNTT004                              │
└──────────────────────────────────────────┘

Hiển thị bên dưới:
📊 4 sinh viên
```

---

### **Ví dụ 3: Kết hợp dấu phẩy và xuống dòng**
```
Input trong textarea:
┌──────────────────────────────────────────┐
│ 2021CNTT001, 2021CNTT002                 │
│ 2021CNTT003                              │
│ 2021CNTT004, 2021CNTT005, 2021CNTT006    │
└──────────────────────────────────────────┘

Hiển thị bên dưới:
📊 6 sinh viên
```

---

### **Ví dụ 4: Nhập theo Tên (Mode Toggle)**
```
Bước 1: Click nút [📋 Nhập theo MSSV]
→ Chuyển thành [👤 Nhập theo Tên]

Input trong textarea:
┌──────────────────────────────────────────┐
│ Nguyễn Văn A                             │
│ Trần Thị B                               │
│ Lê Văn C                                 │
└──────────────────────────────────────────┘

Hoặc:
┌──────────────────────────────────────────┐
│ Nguyễn Văn A, Trần Thị B, Lê Văn C      │
└──────────────────────────────────────────┘

Hệ thống tự tìm MSSV:
- Nguyễn Văn A → 2021CNTT001
- Trần Thị B   → 2021CNTT002
- Lê Văn C     → 2021KHMT003

Hiển thị:
📊 3 sinh viên
```

---

## 🎨 SO SÁNH TRƯỚC VÀ SAU

### **TRƯỚC (v1.0):**
```
❌ Chỉ nhập được MSSV
❌ Chỉ hỗ trợ dấu phẩy
❌ Có nhiều hướng dẫn dài dòng
❌ Placeholder dài 2 dòng

Label:
  Danh sách sinh viên (MSSV)
  
Placeholder:
  Nhập danh sách MSSV, phân cách bằng dấu phẩy
  Ví dụ: 2021CNTT001, 2021CNTT002, 2021KHMT003
  
Hướng dẫn dưới textarea:
  💡 Nhập các MSSV cách nhau bằng dấu phẩy (,). 
  Khoảng trắng thừa sẽ tự động được xóa.
```

---

### **SAU (v2.0):**
```
✅ Nhập được cả MSSV và Tên
✅ Hỗ trợ dấu phẩy + xuống dòng
✅ Tối giản, không rối mắt
✅ Placeholder ngắn gọn 1 dòng

Label với nút:
  Danh sách sinh viên    [📋 Nhập theo MSSV]
  
Placeholder:
  VD: 2021CNTT001, 2021CNTT002 hoặc mỗi MSSV 1 dòng
  
Chỉ hiển thị counter:
  📊 3 sinh viên
```

---

## 🔄 LUỒNG SỬ DỤNG

```
1. Mở form "Thêm lịch gác thi"
   ↓
2. Nhập thông tin cơ bản (Mã, Môn, Ngày, Giờ, Phòng)
   ↓
3. [Tùy chọn] Click nút toggle để chuyển mode
   ↓
4. Nhập danh sách sinh viên
   - Dùng dấu phẩy: A, B, C
   - Hoặc xuống dòng: A \n B \n C
   - Hoặc kết hợp
   ↓
5. Xem counter real-time: 📊 X sinh viên
   ↓
6. Nhập danh sách giảng viên (tương tự)
   ↓
7. Click "Lưu"
   ↓
8. Hệ thống:
   - Parse input (phẩy + xuống dòng)
   - Trim khoảng trắng
   - Nếu mode Tên → tìm MSSV/Mã GV
   - Lưu vào database
   ↓
9. Thành công! ✅
```

---

## 💡 TIPS

### **Nhập nhanh bằng Excel:**
1. Copy danh sách từ Excel (1 cột)
2. Paste vào textarea
3. Tự động nhận dạng xuống dòng
4. Hoàn tất!

### **Tìm nhanh theo tên:**
1. Toggle sang mode Tên
2. Nhập tên (không cần chính xác 100%)
3. Hệ thống tìm tên gần đúng
4. Tự động convert sang MSSV

### **Kiểm tra nhanh:**
- Xem số lượng bên dưới
- Mở Console (F12) để xem warning
- Kiểm tra tên không tìm thấy

---

**Version:** 2.0  
**Date:** 2025-11-22  
**Status:** ✅ Production Ready
