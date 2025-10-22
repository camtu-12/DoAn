<template>
  <div class="admin-root">
        <header class="header" >
      <h1 class="ppp">HỆ THỐNG HỖ TRỢ GIÁM THỊ ĐIỂM DANH SINH VIÊN BẰNG HÌNH ẢNH</h1>
       <div class="sidebar-logout">
  <button class="logout" @click="logout">
    <i class="fa-solid fa-right-from-bracket"></i>
  </button>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</div>
    </header>

    <div class="layout">
      <aside class="sidebar">
        <nav>
          <ul>
      <li :class="{active: activePage==='home'}" @click="setActivePage('home')">Trang chủ</li>
      <li :class="{active: activePage==='schedule'}" @click="setActivePage('schedule')">Lịch gác thi & Phòng gác thi</li>
      <li :class="{active: activePage==='attendance'}" @click="setActivePage('attendance')">Kết quả điểm danh</li>
      <li :class="{active: activePage==='lecturers'}" @click="setActivePage('lecturers')">Quản lí giảng viên</li>
      <li :class="{active: activePage==='students'}" @click="setActivePage('students')">Quản lí sinh viên</li>
      <li :class="{active: activePage==='password'}" @click="setActivePage('password')">Đổi mật khẩu</li>
          </ul>
        </nav>
      </aside>
      
      <main class="content">
        <section class="card">
          <h2 class="card-title">{{ pageTitle }}</h2>

          <!-- HOME -->
          <div v-if="activePage==='home'" class="page-body">
            <h3>Chào mừng đến với trang quản trị</h3>
            <p>Chúc bạn một ngày làm việc thật hiệu quả.</p>
          </div>

          <!-- SCHEDULE -->
          <div v-if="activePage==='schedule'" class="page-body">
            <div class="toolbar">
              <div class="search">
                <input v-model="scheduleSearch" placeholder="Tìm kiếm theo mã môn hoặc mã giảng viên" />
              </div>
              <div class="actions">
                <button @click="openScheduleForm()">Thêm</button>
                <label class="file-btn">
                  <input type="file" @change="onScheduleFileAdd" /> Thêm file
                </label>
              </div>
            </div>
          <table class="table">
            <thead>
              <tr>
                <th class="border border-gray-300 px-2 py-1">STT</th>
                <th class="border border-gray-300 px-2 py-1">Thứ</th>
                <th class="border border-gray-300 px-2 py-1">Ngày thi</th>
                <th class="border border-gray-300 px-2 py-1">Giờ bắt đầu</th>
                <th class="border border-gray-300 px-2 py-1">Môn học</th>
                <th class="border border-gray-300 px-2 py-1">Số phòng</th>
                <th class="border border-gray-300 px-2 py-1">Danh sách sinh viên</th>
                <th class="border border-gray-300 px-2 py-1">Danh sách giảng viên</th>
                <th class="border border-gray-300 px-2 py-1">Ghi chú</th>
                <th class="border border-gray-300 px-2 py-1">Ngày tạo</th>
                <th class="border border-gray-300 px-2 py-1">Cập nhật</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(item, index) in schedules"
                :key="item.id"
                class="hover:bg-gray-50"
              >
                <td class="border border-gray-300 px-2 py-1 text-center">{{ index + 1 }}</td>
                <td class="border border-gray-300 px-2 py-1 text-center">{{ item.Thu }}</td>
                <td class="border border-gray-300 px-2 py-1 text-center">{{ formatDate(item.Ngay_Thi) }}</td>
                <td class="border border-gray-300 px-2 py-1 text-center">{{ item.Gio_Bat_Dau }}</td>
                <td class="border border-gray-300 px-2 py-1">{{ item.Mon_Hoc }}</td>
                <td class="border border-gray-300 px-2 py-1 text-center">{{ item.So_Phong }}</td>
                <td class="border border-gray-300 px-2 py-1">{{ item.DSSV }}</td>
                <td class="border border-gray-300 px-2 py-1">{{ item.DSGV }}</td>
                <td class="border border-gray-300 px-2 py-1">{{ item.Ghi_Chu }}</td>
                <td class="border border-gray-300 px-2 py-1 text-center">{{ formatDate(item.created_at) }}</td>
                <td class="border border-gray-300 px-2 py-1 text-center">{{ formatDate(item.updated_at) }}</td>
                <td class="border border-gray-300 px-2 py-1 text-center">
                  <button @click="openScheduleForm(item, index)" class="bg-blue-500 text-white px-2 py-1 rounded mr-1">Sửa</button>
                  <button @click="deleteSchedule(item.id)" class="bg-red-500 text-white px-2 py-1 rounded">Xóa</button>
                </td>
              </tr>

              <tr v-if="schedules.length === 0">
                <td colspan="12" class="text-center text-gray-500 py-2">Không có lịch thi nào</td>
              </tr>
          </tbody>
    </table>

          </div>

          <!-- ATTENDANCE (raw check-in admin) -->
          <div v-if="activePage==='attendance'" class="page-body">
            <div class="toolbar">
              <button @click="openAttendanceForm()" style="margin-right: 20px;">Thêm</button>
              <button @click="exportResults">Xuất file CSV</button>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">MSSV</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Tên</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Môn</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Ngày</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Thời gian</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Trạng thái</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Chỉnh sửa</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(a, i) in attendance" :key="a.id">
                  <td>{{ a.mssv }}</td>
                  <td>{{ a.name }}</td>
                  <td>{{ a.subject }}</td>
                  <td>{{ a.date }}</td>
                  <td>{{ a.time }}</td>
                  <td>{{ a.status }}</td>
                  <td class="actions-cell"><button @click="deleteAttendance(i)">Xóa</button></td>
                </tr>
                <tr v-if="attendance.length===0"><td colspan="7" class="empty">Không có dữ liệu</td></tr>
              </tbody>
            </table>
          </div>

          <!-- LECTURERS -->
          <div v-if="activePage==='lecturers'" class="page-body">
            <div class="toolbar">
              <button @click="openLecturerForm()">Thêm giảng viên</button>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Mã giảng viên</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Họ tên</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Email</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">SĐT</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Chỉnh sửa</th>
                </tr>
              </thead>
                <tbody>
                      <tr v-for="(l, i) in lecturers" :key="l.MaGV">
                        <td>{{ l.MaGV }}</td>
                        <td>{{ l.Ho_va_Ten }}</td>
                        <td>{{ l.Email }}</td>
                        <td>{{ l.Sdt }}</td>
                        <td class="actions-cell">
                          <button @click="openLecturerForm(l, i)">Sửa</button>
                          <button @click="deleteLecturer(l.MaGV)">Xóa</button>
                        </td>
                      </tr>
                      <tr v-if="lecturers.length === 0">
                        <td colspan="5" class="empty">Không có dữ liệu</td>
                      </tr>
              </tbody>
            </table>
          </div>

          <!-- STUDENTS -->
          <div v-if="activePage==='students'" class="page-body">
            <div class="toolbar">
              <button @click="openStudentForm()">Thêm sinh viên</button>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Ảnh</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Họ tên</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Email</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Ngày sinh</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">MSSV</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Lớp</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Khoa</th>
                  <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Chỉnh sửa</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(s, i) in students" :key="s.Mssv">
                  <td class="avatar-cell"><img :src="s.photo || placeholder" /></td>
                  <td>{{ s.Ho_va_ten }}</td>
                  <td>{{ s.Email }}</td>
                  <td>{{ s.Ngay_Sinh}}</td>
                  <td>{{ s.Mssv }}</td>
                  <td>{{ s.Lop }}</td>
                  <td>{{ s.Khoa }}</td>
                  <td class="actions-cell">
                    <button @click="openStudentForm(s, i)">Sửa</button>
                    <button @click="deleteStudent(i)">Xóa</button>
                  </td>
                </tr>
                <tr v-if="students.length===0"><td colspan="8" class="empty">Không có dữ liệu</td></tr>
              </tbody>
            </table>
          </div>

          <!-- PASSWORD -->
          <div v-if="activePage==='password'" class="page-body">
            <div class="form-row">
              <label>Mật khẩu hiện tại</label>
              <input type="password" />
            </div>
            <div class="form-row">
              <label>Mật khẩu mới</label>
              <input type="password" />
            </div>
            <div class="form-row"><button>Đổi mật khẩu</button></div>
          </div>

        </section>
      </main>
    </div>

   

    <!-- FORM / MODAL LỊCH THI -->
<div v-if="showScheduleModal" class="modal">
  <div class="modal-card wide">
    <h3>{{ scheduleEditingIndex === null ? 'Thêm lịch thi' : 'Sửa lịch thi' }}</h3>
    <div class="form-grid">

      <div class="form-row">
        <label>Thứ</label>
        <select v-model="scheduleForm.Thu">
          <option value="Thứ 2">Thứ 2</option>
          <option value="Thứ 3">Thứ 3</option>
          <option value="Thứ 4">Thứ 4</option>
          <option value="Thứ 5">Thứ 5</option>
          <option value="Thứ 6">Thứ 6</option>
          <option value="Thứ 7">Thứ 7</option>
          <option value="Chủ nhật">Chủ nhật</option>
        </select>
      </div>

      <div class="form-row">
        <label>Ngày thi</label>
        <input type="date" v-model="scheduleForm.Ngay_Thi" />
      </div>

      <div class="form-row">
        <label>Giờ bắt đầu</label>
        <input type="time" v-model="scheduleForm.Gio_Bat_Dau" />
      </div>

      <div class="form-row">
        <label>Giờ kết thúc</label>
        <input type="time" v-model="scheduleForm.Gio_Ket_Thuc" />
      </div>

      <div class="form-row">
        <label>Môn học</label>
        <input v-model="scheduleForm.Mon_Hoc" placeholder="Nhập tên môn học" />
      </div>

      <div class="form-row">
        <label>Mã môn</label>
        <input v-model="scheduleForm.S_id" placeholder="Mã môn học" />
      </div>

      <div class="form-row">
        <label>Mã giảng viên</label>
        <input v-model="scheduleForm.lecturerCode" placeholder="Mã giảng viên" />
      </div>

      <div class="form-row">
        <label>Số phòng</label>
        <input v-model="scheduleForm.So_Phong" placeholder="Nhập số phòng" />
      </div>

      <div class="form-row full">
        <label>Danh sách sinh viên</label>
        <textarea v-model="scheduleForm.DSSV" placeholder="Nhập danh sách sinh viên (phân cách bằng dấu phẩy)"></textarea>
      </div>

      <div class="form-row full">
        <label>Danh sách giảng viên</label>
        <textarea v-model="scheduleForm.DSGV" placeholder="Nhập danh sách giảng viên (phân cách bằng dấu phẩy)"></textarea>
      </div>

      <div class="form-row full">
        <label>Ghi chú</label>
        <textarea v-model="scheduleForm.Ghi_Chu" placeholder="Thêm ghi chú (nếu có)"></textarea>
      </div>

    </div>

    <div class="form-row actions">
      <button @click="saveSchedule" class="bg-blue-500 text-white px-3 py-1 rounded mr-2">Lưu</button>
      <button @click="closeScheduleForm" class="bg-gray-400 text-white px-3 py-1 rounded">Hủy</button>
    </div>
  </div>
</div>

    <!-- FORMS / MODALS cua diem danh sinh vien-->
    <div v-if="showAttendanceModal" class="modal">
      <div class="modal-card">
        <h3>Thêm điểm danh</h3>
        <div class="form-row"><label>MSSV</label><input v-model="attendanceForm.mssv" /></div>
        <div class="form-row"><label>Tên</label><input v-model="attendanceForm.name" /></div>
        <div class="form-row"><label>Môn</label><input v-model="attendanceForm.subject" /></div>
        <div class="form-row"><label>Ngày</label><input type="date" v-model="attendanceForm.date" /></div>
        <div class="form-row"><label>Thời gian</label><input type="time" v-model="attendanceForm.time" /></div>
        <div class="form-row"><label>Trạng thái</label>
          <select v-model="attendanceForm.status">
            <option>Có mặt</option>
            <option>Vắng</option>
          </select>
        </div>
        <div class="form-row actions"><button @click="saveAttendance">Lưu</button><button @click="closeAttendanceForm">Hủy</button></div>
      </div>
    </div>

     <!-- FORMS / MODALS cua giang vien -->
          <div v-if="showLecturerModal" class="modal">
          <div class="modal-card">
            <h3>{{ lecturerEditingIndex === null ? 'Thêm giảng viên' : 'Sửa giảng viên' }}</h3>
            <div class="form-row">
              <label>Mã giảng viên</label>
              <input v-model="lecturerForm.MaGV" />
            </div>
            <div class="form-row">
              <label>Họ tên</label>
              <input v-model="lecturerForm.Ho_va_Ten" />
            </div>
            <div class="form-row">
              <label>Email</label>
              <input v-model="lecturerForm.Email" />
            </div>
            <div class="form-row">
              <label>SĐT</label>
              <input v-model="lecturerForm.Sdt" />
            </div>
            <div class="form-row actions">
              <button @click="saveLecturer">Lưu</button>
              <button @click="closeLecturerForm">Hủy</button>
            </div>
          </div>
        </div>

  

  <!-- FORMS / MODALS cua quản lý sinh viên-->
    
          <div v-if= "showStudentModal" class="modal">
          <div class="modal-card">
            <h3>{{ studentEditingIndex === null ? 'Thêm sinh viên' : 'Sửa sinh viên' }}</h3>
        
            <div class="form-row">
              <label>Họ tên</label>
              <input v-model="studentForm.Ho_va_ten" />
            </div>
            <div class="form-row">
              <label>Email</label>
              <input v-model="studentForm.Email" />
            </div>
            <div class="form-row">
              <label>Ngày sinh</label>
              <input v-model="studentForm.Ngay_Sinh" />
            </div>
            <div class="form-row">
              <label>MSSV</label>
              <input v-model="studentForm.Mssv" />
            </div>
            <div class="form-row">
              <label>Lớp</label>
              <input v-model="studentForm.Lop" />
            </div>
            <div class="form-row">
              <label>Khoa</label>
              <input v-model="studentForm.Khoa" />  
            </div>
            <div class="form-row">
              <label>Ảnh</label>
              <input type="file" @change="handleFileUpload" />
              <div v-if="previewImage">
                <img :src="previewImage" alt="Preview" style="max-width: 200px; margin-top: 10px;" />
              </div>
            </div>
            <div class="form-row actions">
              <button @click="saveStudent">Lưu</button>
              <button @click="closeStudentForm">Hủy</button>
            </div>
          </div>
        </div>





  </div>
</template>

<script setup>
import { reactive, ref, computed, watch, onMounted } from 'vue'
import axios from 'axios' 
import { router } from '@inertiajs/vue3'

axios.defaults.withCredentials = true
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

const activePage = ref('home')

function setActivePage(p){
  console.log('nav click ->', p)
  activePage.value = p
}

const pageTitle = computed(() => {
  switch (activePage.value) {
    case 'home': return 'TRANG CHỦ'
    case 'schedule': return 'LỊCH GÁC THI & PHÒNG GÁC THI'
    case 'attendance': return 'KẾT QUẢ ĐIỂM DANH'
    case 'lecturers': return 'QUẢN LÍ GIẢNG VIÊN'
    case 'students': return 'QUẢN LÍ SINH VIÊN'
    default: return ''
  }
})

const placeholder = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80"><rect width="100%" height="100%" fill="%23ddd"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="%23888" font-size="12">Avatar</text></svg>'

// 🧹 Dữ liệu khởi tạo trống (đã xóa toàn bộ mẫu)
const lecturers = ref([])
const students = ref([])
const schedules = ref([])
const attendance = ref([])

function formatDate(dateString) {
  // Nếu rỗng hoặc null thì trả về chuỗi rỗng
  if (!dateString) return ''

  // Tạo đối tượng ngày
  const date = new Date(dateString)

  // Kiểm tra nếu không hợp lệ
  if (isNaN(date)) return dateString

  // Trả về định dạng ngày kiểu Việt Nam (VD: 22/10/2025)
  return date.toLocaleDateString('vi-VN', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  })
}

// fetch functions from server
const fetchLecturers = async () => {
  try {
    const res = await axios.get('/lecturers')
    lecturers.value = res.data || []
  } catch (err) {
    console.error('fetchLecturers failed', err.response?.status, err.response?.data || err.message)
  }
}

const fetchStudents = async () => {
  try {
    const res = await axios.get('/students')
    students.value = res.data || []
  } catch (err) {
    console.error('fetchStudents failed', err.response?.status, err.response?.data || err.message)
  }
}

const fetchSchedules = async () => {
  try {
    console.log('GET /admin/schedules -> sending')
    const res = await axios.get('/schedules')
    console.log('schedules res', res.status, res.data)
    schedules.value = Array.isArray(res.data) ? res.data : []
  } catch (err) {
    console.error('fetchSchedules failed', err.response?.status, err.response?.data || err.message)
    if (err.response && typeof err.response.data === 'string') {
      console.log('Response text preview:', err.response.data.slice(0, 1000))
    }
    // thử sample route để đảm bảo frontend hoạt động
    try {
      const sample = await axios.get('/test/admin-schedules-sample')
      schedules.value = sample.data || []
      console.log('loaded sample schedules')
    } catch (e) {
      console.error('sample endpoint failed', e)
      schedules.value = []
    }
  }
}


async function saveSchedule() {
   try {
    if (scheduleEditingIndex.value === null) {
      // ➕ Thêm mới
      await axios.post('/schedules/add', scheduleForm);
      alert('✅ Thêm lịch thi thành công!');
    } else {
      // ✏️ Cập nhật
      await axios.put(`/schedules/update/${id}`, scheduleForm);
      alert('✅ Cập nhật lịch thi thành công!');
    }

    await fetchSchedules();
    closeScheduleForm();
  } catch (err) {
    console.error('❌ Lỗi khi lưu lịch thi:', err.response?.data || err.message);
    alert('❌ Không thể lưu lịch thi');
  }
}


const fetchAttendance = async () => {
  try {
    const res = await axios.get('/admin/attendance')
    attendance.value = res.data || []
  } catch (err) {
    console.error('fetchAttendance failed', err.response?.status, err.response?.data || err.message)
  }
}

// gọi khi component mount
onMounted(() => {
  fetchLecturers()
  fetchStudents()
  fetchSchedules()
  fetchAttendance()
})

// Tìm kiếm
const scheduleSearch = ref('')
const filteredSchedules = computed(() => {
  if (!scheduleSearch.value) return schedules.value
  const q = scheduleSearch.value.toLowerCase()
  return schedules.value.filter(r => r.subjectCode.toLowerCase().includes(q) || r.lecturerCode.toLowerCase().includes(q))
})

// =============================
// MODALS & FORM - Giảng viên
// =============================
const showLecturerModal = ref(false)
const lecturerForm = reactive({ MaGV: '', Ho_va_Ten: '', Email: '', Sdt: '' })
const lecturerEditingIndex = ref(null)

function openLecturerForm(item=null, idx=null){
  if(item){ 
    Object.assign(lecturerForm, item); 
    lecturerEditingIndex.value = idx }
  else { 
    Object.assign(lecturerForm, {
      MaGV:'',
      Ho_va_Ten:'',
      Email:'',
      Sdt:''});
       lecturerEditingIndex.value = null;
      }
  showLecturerModal.value = true;
}
function closeLecturerForm(){ showLecturerModal.value = false }
async function saveLecturer() {
  try {
  if (lecturerEditingIndex.value === null) {
      // ➕ Thêm mới
      await axios.post('/lecturers/add', lecturerForm);
      alert('✅ Thêm giảng viên thành công!');
    } else {
      // ✏️ Cập nhật
      await axios.put(`/lecturers/update/${lecturerForm.MaGV}`, lecturerForm);
      alert('✅ Cập nhật giảng viên thành công!');
    }

    await fetchLecturers();
    closeLecturerForm();
  } catch (err) {
    console.error('❌ Lỗi khi lưu giảng viên:', err.response?.data || err.message);
    alert('❌ Không thể lưu giảng viên.');
  }
}
async function deleteLecturer(id){
  if (!confirm('Bạn có chắc chắn muốn xóa giảng viên này không?')) return;
  try {
    await axios.delete(`/lecturers/delete/${id}`);
    await fetchLecturers(); // ✅ gọi hàm thật sự
    alert('✅ Xóa giảng viên thành công!');
  } catch (err) {
    console.error('❌ Lỗi khi xóa giảng viên:', err.response?.data || err.message);
  }}

// =============================
// MODALS & FORM - Sinh viên
// =============================
const showStudentModal = ref(false)
const studentForm = reactive({ Ho_va_ten:'', Email:'', Ngay_Sinh:'', Mssv:'', Lop:'', Khoa:'', Photo:'', KQDD:''})
const studentEditingIndex = ref(null)



const previewImage = ref(null)

function handleFileUpload(event) {
  const file = event.target.files[0]
  if (file) {
    studentForm.value.Photo = file
    previewImage.value = URL.createObjectURL(file) // để hiển thị ảnh xem trước
  }
}


function openStudentForm(item=null, idx=null){
  if(item){ Object.assign(studentForm, item); studentEditingIndex.value = idx }
  else { Object.assign(studentForm, { Ho_va_ten:'', Email:'', Ngay_Sinh:'', Mssv:'', Lop:'', Khoa:'', Photo:'',KQDD:''}); studentEditingIndex.value = null }
  showStudentModal.value = true
}
function closeStudentForm(){ showStudentModal.value = false }
function onStudentPhoto(e){
  const f = e.target.files[0]
  if(!f) return
  const reader = new FileReader()
  reader.onload = ev => studentForm.Photo = ev.target.result
  reader.readAsDataURL(f)
}
function saveStudent(){
  if(studentEditingIndex.value===null) students.value.push({...studentForm})
  else students.value[studentEditingIndex.value] = {...studentForm}
  closeStudentForm()
}
function deleteStudent(i){ if(confirm('Xóa sinh viên này?')) students.value.splice(i,1) }

// =============================
// MODALS & FORM - Lịch thi
// =============================
const showScheduleModal = ref(false)
const scheduleForm = reactive({ STT : '', Thu: '',  Ngay_Thi: '',   Gio_Bat_Dau: '',    Mon_Hoc: '',  So_Phong: '',  DSSV: '', DSGV: '',  Ghi_Chu: ''})
const scheduleEditingIndex = ref(null)



function openScheduleForm(item = null, idx = null) {
  if (item) { 
    Object.assign(scheduleForm, item); 
    scheduleEditingIndex.value = idx;
  } else { 
    Object.assign(scheduleForm, { 
      STT : '',
      Thu: '',
      Ngay_Thi: '',
      Gio_Bat_Dau: '', 
      Mon_Hoc: '', 
      So_Phong: '',
      DSSV: '',
      DSGV: '', 
      Ghi_Chu: '' 
    });
    scheduleEditingIndex.value = null;
  }
  showScheduleModal.value = true;
}
function closeScheduleForm(){ showScheduleModal.value = false }

async function deleteSchedule(id) {
  if (!confirm('Bạn có chắc chắn muốn xóa lịch thi này không?')) return;

  try {
    await axios.delete(`/schedules/delete/${id}`);
    await fetchSchedules(); // ✅ gọi hàm thật sự
    alert('✅ Xóa lịch thi thành công!');
  } catch (err) {
    console.error('❌ Lỗi khi xóa lịch thi:', err.response?.data || err.message);
  }
}

    

function onScheduleFileAdd(e){ const f = e.target.files[0]; if(!f) return alert('File đã chọn: ' + f.name) }

// =============================
// ĐIỂM DANH
// =============================
const showAttendanceModal = ref(false)
const attendanceForm = reactive({ mssv:'', name:'', subject:'', date:'', time:'', status:'Có mặt' })
function openAttendanceForm(){ Object.assign(attendanceForm, { mssv:'', name:'', subject:'', date:'', time:'', status:'Có mặt' }); showAttendanceModal.value = true }
function closeAttendanceForm(){ showAttendanceModal.value = false }
function saveAttendance(){ attendance.value.push({ id: Date.now(), ...attendanceForm }); closeAttendanceForm() }
function deleteAttendance(i){ if(confirm('Xóa điểm danh này?')) attendance.value.splice(i,1) }

// =============================
// KẾT QUẢ & XUẤT FILE
// =============================
const attendanceSummary = computed(() => {
  const map = {}
  attendance.value.forEach(a => {
    const key = a.mssv + '||' + a.subject
    if(!map[key]) map[key] = { mssv: a.mssv, name: a.name, subject: a.subject, present:0, total:0 }
    map[key].total++
    if(a.status && a.status.toLowerCase().includes('có')) map[key].present++
  })
  return Object.values(map)
})

function exportResults(){
  const rows = attendanceSummary.value
  if(rows.length===0) return alert('Không có dữ liệu để xuất')
  const header = ['MSSV','Tên','Môn','Số buổi dự','Tổng buổi']
  const csv = [header].concat(rows.map(r=>[r.mssv,r.name,r.subject,r.present,r.total]))
    .map(r=>r.map(cell=>`"${String(cell).replace(/"/g,'""')}"`).join(','))
    .join('\n')
  const blob = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a'); a.href = url; a.download = 'attendance_summary.csv'; a.click(); URL.revokeObjectURL(url)
}

// =============================
// ĐĂNG XUẤT
// =============================
function logout() {
  axios.post('/logout')
    .then(() => window.location.href = '/login')
    .catch(err => {
      console.error('Logout failed', err)
      alert('Đăng xuất thất bại. Kiểm tra console.')
    })
}

// =============================
// LOCALSTORAGE
// =============================
watch(lecturers, (v)=> localStorage.setItem('lecturers', JSON.stringify(v)), {deep:true})
watch(students, (v)=> localStorage.setItem('students', JSON.stringify(v)), {deep:true})
watch(schedules, (v)=> localStorage.setItem('schedules', JSON.stringify(v)), {deep:true})
watch(attendance, (v)=> localStorage.setItem('attendance', JSON.stringify(v)), {deep:true})

// 🧽 Xóa sạch dữ liệu cũ (chạy 1 lần duy nhất)
if (!localStorage.getItem('dataReset2025')) {
  localStorage.clear()
  localStorage.setItem('dataReset2025', 'done')
}

// Khởi tạo dữ liệu từ localStorage (nếu có)
const init = ()=>{
  try{
    const ls = JSON.parse(localStorage.getItem('lecturers')||'null'); if(ls) lecturers.value = ls
    const ss = JSON.parse(localStorage.getItem('students')||'null'); if(ss) students.value = ss
    const sc = JSON.parse(localStorage.getItem('schedules')||'null'); if(sc) schedules.value = sc
    const at = JSON.parse(localStorage.getItem('attendance')||'null'); if(at) attendance.value = at
  }catch(e){ console.warn('load fail', e) }
}
init()
</script>


<style scoped>
.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: linear-gradient(90deg,#95bce1, #0c7de7);
  color: #ffffff;
  padding: 9px 20px;
  font-weight: 400;
  font-size: 24px;
  letter-spacing: 1px;
  box-shadow: 0 2px 6px rgba(154, 189, 237, 0.25);
}

.header h1 {
  margin: 0;
  margin-left: 0px; 
  font-size: 22px;
}
.ppp{
  color:#ffffff;
  font-size: 38px;
  font-weight: 800; /* ✅ in đậm hơn */
   margin-left: 20px;
}
:root{ --blue1:#2f86d1; --blue2:#2ba0f2; }
*{box-sizing:border-box}
.admin-root{font-family: 'Segoe UI', Roboto, Arial; color:#1b1b1b}
.topbar{height:76px;background:linear-gradient(90deg,var(--blue1),var(--blue2));color:#fff;display:flex;align-items:center;justify-content:space-between;padding:0 24px}
.topbar .brand{font-size:22px;font-weight:700;letter-spacing:1px}
.layout{display:flex}
.sidebar{width:300px;background:#e6eaec;padding-top:20px;min-height:calc(100vh - 76px)}
.sidebar nav ul{list-style:none;padding:0;margin:0}
.sidebar nav li{padding:18px 20px;color:#234;cursor:pointer;border-left:6px solid transparent}
.sidebar nav li.active{background:#dcdfe0;border-left-color:#2f86d1;color:#0b4f85}
.content{flex:1;padding:36px 48px;background:#eef4f6;min-height:calc(100vh - 76px)}
.card{background:#fff;border-radius:12px;padding:28px;box-shadow:0 8px 20px rgba(0,0,0,0.06)}
.card-title{text-align:center;color:#1e73be;margin-bottom:18px;font-weight:700;font-size:28px}
.page-body{padding:10px 6px}
.toolbar{display:flex;justify-content:right;align-items:center;margin-bottom:12px}
.search input{padding:8px 12px;border-radius:6px;border:1px solid #ccc;width:360px}
.actions button,.actions .file-btn{background:#2ea44f;color:#fff;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;margin-left:8px}
.file-btn input{display:none}
.table{width:100%;border-collapse:collapse;margin-top:8px}
.table th{background:#f6f8f9;text-align:center;padding:12px;border-bottom:1px solid #eee;color:#2b5f86}
.table td{padding:12px;border-bottom:1px solid #f1f4f5}
.actions-cell button{margin-right:6px;padding:6px 8px;border-radius:6px;border:1px solid #ccc;background:#fff;cursor:pointer}
.empty{text-align:center;padding:18px;color:#777}
.avatar-cell img{width:48px;height:48px;border-radius:6px;object-fit:cover}
.modal{position:fixed;inset:0;background:rgba(10,10,10,0.35);display:flex;align-items:center;justify-content:center}
.modal-card{background:#fff;padding:18px;border-radius:10px;min-width:320px}
.modal-card.wide{min-width:760px}
.form-row{display:flex;flex-direction:column;margin:8px 0}
.form-row label{font-weight:600;color:#2b7ab8;margin-bottom:6px}
.form-row input[type='text'], .form-row input[type='email'], .form-row input[type='password'], .form-row input[type='date'], .form-row input[type='time'], .form-row select{padding:8px;border:1px solid #ddd;border-radius:6px}
.form-row.actions{display:flex;gap:8px;justify-content:flex-end}
.form-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
.form-grid .full{grid-column:1/-1}
button {
  transition: all 0.2s ease;
  font-weight: 600;
}

/* === CÁC LOẠI NÚT CHÍNH === */
.actions button,
.actions .file-btn {
  background: #28a745; /* Xanh lá - thêm */
  color: #fff;
  border: none;
  padding: 8px 12px;
  border-radius: 6px;
  cursor: pointer;
  margin-left: 8px;
}

.actions button:hover,
.actions .file-btn:hover {
  background: #218838;
}

/* === NÚT TRONG BẢNG Chỉnh sửa === */
.actions-cell button {
  margin-right: 6px;
  padding: 6px 10px;
  border-radius: 6px;
  border: none;
  color: #fff;
  cursor: pointer;
  font-weight: 500;
}

/* Sửa */
.actions-cell button:nth-child(1) {
  background-color: rgb(12, 185, 115);
}
.actions-cell button:nth-child(1):hover {
  background-color: rgb(12, 185, 115);
}

/* Xóa */
.actions-cell button:nth-child(2) {
  background-color: rgb(12, 185, 115);
}
.actions-cell button:nth-child(2):hover {
  background-color: rgb(12, 185, 115);
}

/* === NÚT TRONG MODAL === */
.form-row.actions button:first-child {
  background-color: #28a745; /* Lưu */
  color: #fff;
}
.form-row.actions button:first-child:hover {
  background-color: #218838;
}

.form-row.actions button:last-child {
  background-color: #6c757d; /* Hủy */
  color: #fff;
}
.form-row.actions button:last-child:hover {
  background-color: #5a6268;
}

/* === NÚT ĐĂNG XUẤT === */
.logout {
  background: #f5f5f5;
  border: none;
  color: rgb(18, 17, 17);
  font-size: 18px;
  padding: 6px 12px;
  border-radius: 50%;
  cursor: pointer;
  transition: 0.2s;
}
.logout:hover {
  background: #c82333;
  transform: scale(1.1);
}

/* === NÚT XUẤT FILE === */
.toolbar button {
  background-color: #0d6efd;
  color: white;
  border: none;
  border-radius: 6px;
  padding: 8px 14px;
  font-weight: 600;
}
.toolbar button:hover {
  background-color: #0b5ed7;
}
</style>