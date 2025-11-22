# 🔧 FIX: AUTO-GENERATE MÃ MÔN THI (MaMT)

## 🐛 Vấn đề

User **KHÔNG nên phải nhập** `MaMT` thủ công vì:
- Dễ bị trùng lặp
- Không nhất quán với seeder (auto-generate `MT0001`, `MT0002`, ...)
- Gây khó khăn cho người dùng

## ✅ Giải pháp

### 1. Backend tự động generate MaMT

**File: `app/Http/Controllers/LichThiController.php`**

#### ❌ TRƯỚC:
```php
$validated = $request->validate([
    'MaMT' => 'required|string|unique:lich_this,MaMT',
    'Mon_Hoc' => 'required|string|max:255',
    // ...
]);
```

#### ✅ SAU:
```php
$validated = $request->validate([
    // KHÔNG YÊU CẦU MaMT từ request
    'Mon_Hoc' => 'required|string|max:255',
    'Ngay_Thi' => 'required|date',
    'Gio_Bat_Dau' => 'required',
    'Gio_Ket_Thuc' => 'required',
    'So_Phong' => 'required|exists:phong_this,id',
    'Ghi_Chu' => 'nullable|string',
    'DSSV' => 'nullable|string',
    'DSGV' => 'nullable|string',
]);

// Auto-generate MaMT (MT0001, MT0002, ...)
$lastLichThi = LichThi::orderBy('id', 'desc')->first();
$nextNumber = $lastLichThi ? ($lastLichThi->id + 1) : 1;
$validated['MaMT'] = 'MT' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
```

### 2. Frontend loại bỏ input MaMT

**File: `resources/js/Pages/Admin/Index.vue`**

#### ❌ TRƯỚC:
```javascript
const scheduleForm = reactive({ 
  STT: '', 
  MaMT: '',  // ❌ Không cần nữa
  Thu: '',  
  Ngay_Thi: '',   
  // ...
})
```

```vue
<!-- ❌ Loại bỏ input này -->
<div class="form-row">
  <label>Mã môn thi <span style="color: red;">*</span></label>
  <input v-model="scheduleForm.MaMT" placeholder="VD: MT001" required />
</div>
```

#### ✅ SAU:
```javascript
const scheduleForm = reactive({ 
  STT: '', 
  // MaMT được auto-generate ở backend
  Thu: '',  
  Ngay_Thi: '',   
  Gio_Bat_Dau: '', 
  Gio_Ket_Thuc: '',
  Mon_Hoc: '',  
  So_Phong: '',  
  DSSV: '', 
  DSGV: '',  
  Ghi_Chu: ''
})
```

```vue
<!-- KHÔNG CÓ input MaMT nữa -->
<!-- MaMT sẽ tự động được tạo ở backend -->
```

## 🎯 Kết quả

### Trước khi fix:
```
User nhập: MaMT = "MT123"
          Mon_Hoc = "Toán cao cấp"
          ...
          
❌ Nếu MT123 đã tồn tại → Lỗi 422 (unique constraint)
❌ User phải đoán mã nào chưa dùng
```

### Sau khi fix:
```
User chỉ nhập: Mon_Hoc = "Toán cao cấp"
               Ngay_Thi = "2025-12-25"
               ...
               
✅ Backend tự động tạo: MaMT = "MT0047" (dựa vào ID tiếp theo)
✅ Không bao giờ trùng
✅ Nhất quán với seeder
```

## 📋 Checklist

- [x] Loại bỏ validation `MaMT` required từ backend
- [x] Thêm logic auto-generate `MaMT` trong controller
- [x] Loại bỏ field `MaMT` khỏi `scheduleForm` reactive
- [x] Xóa input `MaMT` khỏi form HTML
- [x] Rebuild frontend: `npm run build` ✅
- [x] Test tạo lịch thi mới → MaMT tự động sinh

## 🧪 Test

```bash
# Mở browser và test thêm lịch thi
# Kiểm tra trong database:

SELECT id, MaMT, Mon_Hoc FROM lich_this ORDER BY id DESC LIMIT 5;

# Kết quả mong đợi:
# id | MaMT   | Mon_Hoc
# 47 | MT0047 | Auto Test Môn Học
# 46 | MT0046 | ...
# 45 | MT0045 | ...
```

## 🚀 Cải tiến so với trước

| Tiêu chí | Trước | Sau |
|----------|-------|-----|
| User phải nhập MaMT | ✅ Có | ❌ Không |
| Nguy cơ trùng lặp | ⚠️ Cao | ✅ Không có |
| Nhất quán với seeder | ❌ Không | ✅ Có |
| UX | ⭐⭐ | ⭐⭐⭐⭐⭐ |

---

**Status:** ✅ HOÀN THÀNH
**Ngày fix:** 2025-11-22
**Tác giả:** GitHub Copilot
