# 🧪 TEST NHẬP DANH SÁCH SINH VIÊN & GIẢNG VIÊN (v2.0)

## ✨ TÍNH NĂNG MỚI:

### **1. Toggle Mode - Nhập theo Mã hoặc Tên**
- ✅ Nút toggle bên phải label
- ✅ 2 mode cho sinh viên: **MSSV** hoặc **Tên**
- ✅ 2 mode cho giảng viên: **Mã GV** hoặc **Tên**
- ✅ Icon động thay đổi theo mode

### **2. Hỗ trợ đa định dạng input**
- ✅ Phân cách bằng dấu phẩy: `A, B, C`
- ✅ Xuống dòng (mỗi item 1 dòng):
  ```
  A
  B
  C
  ```
- ✅ Kết hợp cả 2: `A, B` + xuống dòng

### **3. Giao diện tối giản**
- ✅ Bỏ các hướng dẫn dài dòng
- ✅ Placeholder ngắn gọn, có ví dụ
- ✅ Chỉ hiển thị số lượng đã nhập

---

## 🧪 HƯỚNG DẪN TEST:

### **Test 1: Nhập theo MSSV (Mode mặc định)**

1. Mở form **Thêm lịch gác thi**
2. Kiểm tra nút toggle hiển thị: `📋 Nhập theo MSSV`
3. Nhập sinh viên theo **dấu phẩy**:
   ```
   2021CNTT001, 2021CNTT002, 2021CNTT003
   ```
4. Nhập sinh viên theo **xuống dòng**:
   ```
   2021CNTT001
   2021CNTT002
   2021CNTT003
   ```
5. Kiểm tra hiển thị: `📊 3 sinh viên`

---

### **Test 2: Nhập theo Tên (Toggle sang mode tên)**

1. Click nút toggle → Chuyển sang `👤 Nhập theo Tên`
2. Nhập tên sinh viên (tìm gần đúng):
   ```
   Nguyễn Văn A
   Trần Thị B
   Lê Văn C
   ```
   
   Hoặc dùng dấu phẩy:
   ```
   Nguyễn Văn A, Trần Thị B, Lê Văn C
   ```

3. Hệ thống tự động tìm MSSV tương ứng
4. Nếu không tìm thấy → hiển thị warning trong console

---

### **Test 3: Nhập giảng viên theo Mã GV**

1. Kiểm tra nút toggle: `📋 Nhập theo Mã`
2. Nhập:
   ```
   GV001
   GV002
   GV003
   ```
3. Kiểm tra: `📊 3 giảng viên`

---

### **Test 4: Nhập giảng viên theo Tên**

1. Click toggle → `👤 Nhập theo Tên`
2. Nhập tên giảng viên:
   ```
   Nguyễn Văn X, Trần Thị Y
   ```
3. Hệ thống tự động tìm Mã GV

---

### **Test 5: Kết hợp dấu phẩy và xuống dòng**

```
2021CNTT001, 2021CNTT002
2021CNTT003
2021CNTT004, 2021CNTT005
```

Kết quả: `📊 5 sinh viên`

---

## 📋 CHECKLIST TEST:

- [ ] Toggle mode sinh viên hoạt động
- [ ] Toggle mode giảng viên hoạt động
- [ ] Icon thay đổi khi toggle
- [ ] Placeholder thay đổi theo mode
- [ ] Nhập theo dấu phẩy hoạt động
- [ ] Nhập xuống dòng hoạt động
- [ ] Kết hợp dấu phẩy + xuống dòng hoạt động
- [ ] Mode "Tên" tìm được MSSV/Mã GV
- [ ] Hiển thị đúng số lượng real-time
- [ ] Lưu vào database thành công
- [ ] Trim khoảng trắng tự động

---

## 🎨 GIAO DIỆN:

### **Mode MSSV:**
```
┌─────────────────────────────────────────┐
│ Danh sách sinh viên   [📋 Nhập theo MSSV] │
├─────────────────────────────────────────┤
│ VD: 2021CNTT001, 2021CNTT002 hoặc mỗi   │
│ MSSV 1 dòng                              │
│                                          │
│ 📊 3 sinh viên                           │
└─────────────────────────────────────────┘
```

### **Mode Tên:**
```
┌─────────────────────────────────────────┐
│ Danh sách sinh viên   [👤 Nhập theo Tên] │
├─────────────────────────────────────────┤
│ VD: Nguyễn Văn A, Trần Thị B hoặc mỗi   │
│ tên 1 dòng                               │
│                                          │
│ 📊 3 sinh viên                           │
└─────────────────────────────────────────┘
```

---

## 💡 LƯU Ý:

### **Nhập theo Tên:**
- Tìm kiếm **gần đúng** (case-insensitive)
- Tìm trong danh sách sinh viên/giảng viên đã load
- Nếu không tìm thấy → bỏ qua + warning console

### **Ví dụ tìm gần đúng:**
- Input: `Nguyễn`
- Tìm thấy: `Nguyễn Văn A`, `Nguyễn Thị B`
- Lấy: Người đầu tiên tìm thấy

---

## 🐛 XỬ LÝ LỖI:

### **Warning: "Không tìm thấy sinh viên: XXX"**
- Xuất hiện trong console (F12)
- Sinh viên đó sẽ bị bỏ qua
- Kiểm tra lại tên có đúng không

### **Giải pháp:**
1. Kiểm tra danh sách sinh viên trong tab "Quản lý SV"
2. Sửa tên cho khớp
3. Hoặc chuyển sang mode MSSV

---

## ✅ ĐÃ IMPLEMENT:

### **Frontend:**
- ✅ Toggle button với icon động
- ✅ Parse input hỗ trợ `,` và `\n`
- ✅ Tìm MSSV/Mã GV theo tên
- ✅ Real-time counter
- ✅ Placeholder động theo mode

### **Backend:**
- ✅ Nhận MSSV đã được convert từ frontend
- ✅ Validate và lưu vào database
- ✅ Không cần thay đổi (frontend đã xử lý)

---

**Updated:** 2025-11-22 v2.0  
**Status:** ✅ READY FOR TESTING

---

## 🚀 NEXT FEATURES (Tùy chọn):

- [ ] Autocomplete khi nhập tên
- [ ] Hiển thị danh sách đã chọn với avatar
- [ ] Drag & drop để sắp xếp
- [ ] Import từ Excel
- [ ] Export danh sách đã chọn
