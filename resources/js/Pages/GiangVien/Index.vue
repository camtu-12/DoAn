
<template>
        <header class="header" >
      <h1 class="ppp">HỆ THỐNG HỖ TRỢ GIÁM THỊ ĐIỂM DANH SINH VIÊN BẰNG HÌNH ẢNH</h1>
       <div class="sidebar-logout">
  <button class="logout" @click="logout">
    <i class="fa-solid fa-right-from-bracket"></i>
  </button>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</div>
    </header>

  <div class="app">

    <!-- SIDEBAR -->
    <aside class="sidebar">
      

      <nav class="menu">
        <button @click="activeTab = 'info'" :class="{ active: activeTab === 'info' }">
          Thông tin giảng viên
        </button>
        <button @click="activeTab = 'schedule'" :class="{ active: activeTab === 'schedule' }">
          Lịch gác thi & Phòng gác thi
        </button>
        <button @click="activeTab = 'attendance'" :class="{ active: activeTab === 'attendance' }">
          Điểm danh sinh viên
        </button>
        <button @click="activeTab = 'result'" :class="{ active: activeTab === 'result' }">
          Kết quả điểm danh
        </button>

         <button @click="activeTab = 'password'" :class="{ active: activeTab === 'password' }">
          Đổi mật khẩu
        </button>
      </nav>

      
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
      <!-- Thông tin cá nhân -->
     <section v-if="activeTab === 'info'" class="centered-section">
  <h2 class="tt">THÔNG TIN GIẢNG VIÊN</h2>

  <form class="info-form" @submit.prevent="updateInfo">
    <div class="form-row">
      <label>Họ và tên:</label>
      <input type="text" v-model="teacher.Ten" required />
    </div>

    <div class="form-row">
      <label>Email:</label>
      <input type="email" v-model="teacher.Email" required />
    </div>

    <div class="form-row">
      <label>Số điện thoại:</label>
      <input type="text" v-model="teacher.Sdt" required />
    </div>

    <div class="form-row">
      <label>Khoa:</label>
      <input type="text" v-model="teacher.Bo_Mon" required />
    </div>

    <button type="submit" class="btn-update">Cập nhật thông tin</button>
  </form>
</section>

      <!-- Lịch gác thi -->
       <section v-if="activeTab === 'schedule'">
        <h2>LỊCH VÀ PHÒNG GÁC THI</h2>

        <!-- Tabs để filter theo status -->
        <div class="schedule-tabs">
          <button 
            @click="scheduleStatusFilter = 'pending'" 
            :class="{ 'tab-active': scheduleStatusFilter === 'pending' }"
            class="tab-button"
          >
            🟡 Chờ xác nhận ({{ pendingSchedules.length }})
          </button>
          <button 
            @click="scheduleStatusFilter = 'confirmed'" 
            :class="{ 'tab-active': scheduleStatusFilter === 'confirmed' }"
            class="tab-button"
          >
            🟢 Đã xác nhận ({{ confirmedSchedules.length }})
          </button>
          <button 
            @click="scheduleStatusFilter = 'rejected'" 
            :class="{ 'tab-active': scheduleStatusFilter === 'rejected' }"
            class="tab-button"
          >
            🔴 Đã từ chối ({{ rejectedSchedules.length }})
          </button>
          <button 
            @click="scheduleStatusFilter = 'all'" 
            :class="{ 'tab-active': scheduleStatusFilter === 'all' }"
            class="tab-button"
          >
            📋 Tất cả ({{ allSchedules.length }})
          </button>
        </div>

        <table class="table">
        <thead>
          <tr>
            <th class="border border-gray-300 px-2 py-1">STT</th>
            <th class="border border-gray-300 px-2 py-1">Mã môn</th>
            <th class="border border-gray-300 px-2 py-1">Môn học</th>
            <th class="border border-gray-300 px-2 py-1">Ngày thi</th>
            <th class="border border-gray-300 px-2 py-1">Thời gian</th>
            <th class="border border-gray-300 px-2 py-1">Phòng thi</th>
            <th class="border border-gray-300 px-2 py-1">Vai trò</th>
            <th class="border border-gray-300 px-2 py-1">Trạng thái</th>
            <th class="border border-gray-300 px-2 py-1">Điểm danh</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(item, index) in filteredSchedules"
            :key="item.id"
            class="hover:bg-gray-50"
          >
            <td class="border border-gray-300 px-2 py-1 text-center">{{ index + 1 }}</td>
            <td class="border border-gray-300 px-2 py-1 text-center">{{ item.ma_mon }}</td>
            <td class="border border-gray-300 px-2 py-1">{{ item.mon_hoc }}</td>
            <td class="border border-gray-300 px-2 py-1 text-center">
              {{ formatDate(item.ngay_thi) }}<br>
              <small style="color: #666;">({{ item.thu }})</small>
            </td>
            <td class="border border-gray-300 px-2 py-1 text-center">
              {{ item.gio_bat_dau }} - {{ item.gio_ket_thuc }}
            </td>
            <td class="border border-gray-300 px-2 py-1 text-center">
              {{ item.so_phong }}<br>
              <small style="color: #666;">{{ item.toa_nha }}</small>
            </td>
            <td class="border border-gray-300 px-2 py-1 text-center">
              <span :class="item.role === 'Trưởng phòng' ? 'badge-leader' : 'badge-supervisor'">
                {{ item.role }}
              </span>
            </td>
            <td class="border border-gray-300 px-2 py-1 text-center">
              <span :class="getStatusClass(item.status)">
                {{ getStatusText(item.status) }}
              </span>
              <div v-if="item.confirmed_at" style="font-size: 11px; color: #666; margin-top: 4px;">
                {{ formatDateTime(item.confirmed_at) }}
              </div>
            </td>
            <td class="border border-gray-300 px-2 py-1 text-center">
              <!-- Nút điểm danh chỉ hiện với status confirmed -->
              <div v-if="item.status === 'confirmed'" class="attendance-cell">
                <div class="attendance-stats">
                  <span class="attendance-count">{{ item.attended_count || 0 }}/{{ item.total_students || 0 }}</span>
                </div>
                <div class="attendance-actions">
                  <button 
                    @click="openAttendanceModal(item)" 
                    class="btn-attendance"
                    title="Điểm danh sinh viên"
                  >
                    📝 Điểm danh
                  </button>
                  <button 
                    @click="viewStudentList(item)" 
                    class="btn-view-list"
                    title="Xem danh sách sinh viên"
                  >
                    👥 Xem DS
                  </button>
                </div>
              </div>
              <!-- Status pending - hiện nút xác nhận -->
              <div v-else-if="item.status === 'pending'" class="action-buttons">
                <button 
                  @click="confirmSchedule(item.id)" 
                  class="btn-confirm"
                  title="Xác nhận lịch gác"
                >
                  ✓ Xác nhận
                </button>
              </div>
              <!-- Status rejected hoặc khác -->
              <div v-else>
                <button 
                  @click="viewScheduleDetail(item)" 
                  class="btn-view"
                >
                  👁 Chi tiết
                </button>
              </div>
            </td>
          </tr>

          <tr v-if="filteredSchedules.length === 0">
            <td colspan="9" class="text-center text-gray-500 py-4">
              {{ getEmptyMessage() }}
            </td>
          </tr>
  </tbody>
</table>
      </section>

      <!-- Điểm danh sinh viên -->
      <section v-if="activeTab === 'attendance'">
        <h2>ĐIỂM DANH SINH VIÊN</h2>
        
        <!-- Dropdown chọn lịch thi -->
        <div class="attendance-selector">
          <label for="exam-select">Chọn lịch gác thi:</label>
          <select 
            id="exam-select" 
            v-model="selectedExamForAttendance" 
            @change="loadAttendanceList"
            class="exam-dropdown"
          >
            <option value="">-- Chọn lịch thi --</option>
            <option 
              v-for="schedule in confirmedSchedules" 
              :key="schedule.exam_id" 
              :value="schedule.exam_id"
            >
              {{ schedule.mon_hoc }} - {{ formatDate(schedule.ngay_thi) }} ({{ schedule.gio_bat_dau }}-{{ schedule.gio_ket_thuc }}) - Phòng {{ schedule.so_phong }}
            </option>
          </select>
        </div>

        <!-- Thông tin lịch thi đã chọn -->
        <div v-if="selectedExamForAttendance && attendanceStats" class="attendance-summary-box">
          <div class="summary-item">
            <span class="label">Môn học:</span>
            <span class="value">{{ currentExamInfo?.mon_hoc }}</span>
          </div>
          <div class="summary-item">
            <span class="label">Ngày thi:</span>
            <span class="value">{{ formatDate(currentExamInfo?.ngay_thi) }} ({{ currentExamInfo?.thu }})</span>
          </div>
          <div class="summary-item">
            <span class="label">Phòng thi:</span>
            <span class="value">{{ currentExamInfo?.so_phong }} - {{ currentExamInfo?.toa_nha }}</span>
          </div>
          <div class="summary-item stats">
            <span class="label">Tình trạng:</span>
            <span class="value">
              <span class="stat-attended">✅ Đã điểm danh: {{ attendanceStats.attended }}</span> / 
              <span class="stat-not-attended">⚠️ Chưa điểm danh: {{ attendanceStats.notAttended }}</span> / 
              <span class="stat-total">📊 Tổng: {{ attendanceStats.total }}</span>
            </span>
          </div>
        </div>

        <!-- Danh sách sinh viên -->
        <div v-if="selectedExamForAttendance" class="attendance-list-container">
          <div class="filter-tabs">
            <button 
              @click="attendanceFilter = 'all'" 
              :class="{ 'active': attendanceFilter === 'all' }"
              class="filter-tab"
            >
              📋 Tất cả ({{ attendanceListForDisplay.length }})
            </button>
            <button 
              @click="attendanceFilter = 'attended'" 
              :class="{ 'active': attendanceFilter === 'attended' }"
              class="filter-tab"
            >
              ✅ Đã điểm danh ({{ attendedStudents.length }})
            </button>
            <button 
              @click="attendanceFilter = 'not-attended'" 
              :class="{ 'active': attendanceFilter === 'not-attended' }"
              class="filter-tab"
            >
              ⚠️ Chưa điểm danh ({{ notAttendedStudents.length }})
            </button>
          </div>

          <table class="attendance-table">
            <thead>
              <tr>
                <th>STT</th>
                <th>MSSV</th>
                <th>Họ và tên</th>
                <th>Lớp</th>
                <th>Trạng thái</th>
                <th>Thời gian điểm danh</th>
                <th>Phương thức</th>
              </tr>
            </thead>
            <tbody>
              <tr 
                v-for="(student, index) in filteredAttendanceList" 
                :key="student.mssv"
                :class="{ 'row-attended': student.da_diem_danh, 'row-not-attended': !student.da_diem_danh }"
              >
                <td>{{ index + 1 }}</td>
                <td><strong>{{ student.mssv }}</strong></td>
                <td>{{ student.ho_va_ten }}</td>
                <td>{{ student.lop || 'N/A' }}</td>
                <td>
                  <span v-if="student.da_diem_danh" class="status-badge attended">✅ Đã điểm danh</span>
                  <span v-else class="status-badge not-attended">⚠️ Chưa điểm danh</span>
                </td>
                <td>
                  <span v-if="student.thoi_gian_diem_danh">
                    {{ formatDateTime(student.thoi_gian_diem_danh) }}
                  </span>
                  <span v-else class="text-muted">-</span>
                </td>
                <td>
                  <span v-if="student.phuong_thuc_diem_danh" class="method-badge">
                    {{ getMethodLabel(student.phuong_thuc_diem_danh) }}
                  </span>
                  <span v-else class="text-muted">-</span>
                </td>
              </tr>
              <tr v-if="filteredAttendanceList.length === 0">
                <td colspan="7" class="text-center text-muted">
                  {{ getAttendanceEmptyMessage() }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Message khi chưa chọn lịch -->
        <div v-else class="empty-state">
          <div class="empty-icon">📋</div>
          <p>Vui lòng chọn lịch gác thi để xem danh sách sinh viên</p>
        </div>
      </section>

      <!-- Kết quả điểm danh -->
      <section v-if="activeTab === 'result'">
        <h2> KẾT QUẢ ĐIỂM DANH </h2>
        <table class="table">
          <thead>
            <tr>
              <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">MSSV</th>
              <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Họ tên</th>
              <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Ngày thi</th>
              <th data-v-d31f6b30 class="border border-gray-300 px-2 py-1">Trạng thái</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="record in results" :key="record.id">
              <td>{{ record.studentId }}</td>
              <td>{{ record.name }}</td>
              <td>{{ record.date }}</td>
              <td>{{ record.status }}</td>
            </tr>
          </tbody>
        </table>
      </section>

           <!-- Đổi mật khẩu -->
    <!-- Đổi mật khẩu -->
<section v-if="activeTab === 'password'" class="centered-section">
  <h2 class="tt">ĐỔI MẬT KHẨU</h2>

  <form class="info-form" @submit.prevent="changePassword">
    <div class="form-row">
      <label>Mật khẩu hiện tại:</label>
      <input type="password" v-model="password.old" required />
    </div>

    <div class="form-row">
      <label>Mật khẩu mới:</label>
      <input type="password" v-model="password.new" required />
    </div>

    <div class="form-row">
      <label>Xác nhận mật khẩu mới:</label>
      <input type="password" v-model="password.confirm" required />
    </div>

    <button type="submit" class="btn-update">Đổi mật khẩu</button>
  </form>

  <p v-if="passwordMessage">{{ passwordMessage }}</p>
</section>

    </main>
  </div>

  <!-- Modal Điểm danh -->
  <div v-if="showAttendanceModal" class="modal-overlay" @click.self="closeAttendanceModal">
    <div class="modal-content attendance-modal">
      <div class="modal-header">
        <h3>📝 Điểm danh sinh viên</h3>
        <button @click="closeAttendanceModal" class="btn-close">✕</button>
      </div>
      
      <div class="modal-body">
        <div class="attendance-info">
          <p><strong>Môn học:</strong> {{ currentSchedule?.mon_hoc }}</p>
          <p><strong>Ngày thi:</strong> {{ formatDate(currentSchedule?.ngay_thi) }}</p>
          <p><strong>Phòng:</strong> {{ currentSchedule?.so_phong }}</p>
        </div>

        <!-- Tabs cho phương thức điểm danh -->
        <div class="attendance-tabs">
          <button 
            @click="switchAttendanceMethod('qr')" 
            :class="{ 'tab-active': attendanceMethod === 'qr' }"
            class="attendance-tab"
          >
            📷 Quét QR Code
          </button>
          <button 
            @click="switchAttendanceMethod('manual')" 
            :class="{ 'tab-active': attendanceMethod === 'manual' }"
            class="attendance-tab"
          >
            ⌨️ Nhập MSSV
          </button>
        </div>

        <!-- QR Scanner -->
        <div v-if="attendanceMethod === 'qr'" class="qr-scanner-container">
          <div id="qr-reader"></div>
          <p class="qr-hint">Đưa mã QR của sinh viên vào khung hình</p>
        </div>

        <!-- Manual Input -->
        <div v-else class="manual-input-container">
          <label>Nhập mã số sinh viên:</label>
          <input 
            v-model="manualMssv" 
            type="text" 
            placeholder="Ví dụ: DH52200662"
            @keyup.enter="submitManualMssv"
            class="input-mssv"
          />
          <button @click="submitManualMssv" class="btn-submit-mssv">
            Tìm kiếm
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Xác nhận thông tin sinh viên -->
  <div v-if="showStudentConfirmModal" class="modal-overlay" @click.self="closeStudentConfirmModal">
    <div class="modal-content student-confirm-modal">
      <div class="modal-header">
        <h3>✓ Xác nhận thông tin sinh viên</h3>
        <button @click="closeStudentConfirmModal" class="btn-close">✕</button>
      </div>
      
      <div class="modal-body">
        <div class="student-info-card">
          <div class="info-row">
            <span class="info-label">MSSV:</span>
            <span class="info-value">{{ foundStudent?.Mssv }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Họ và tên:</span>
            <span class="info-value">{{ foundStudent?.Ho_va_ten }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Lớp:</span>
            <span class="info-value">{{ foundStudent?.Lop || 'N/A' }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Ngày sinh:</span>
            <span class="info-value">{{ formatDate(foundStudent?.Ngay_sinh) || 'N/A' }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Phương thức:</span>
            <span class="info-value">{{ foundStudent?.phuong_thuc === 'qr_code' ? '📷 QR Code' : '⌨️ Nhập thủ công' }}</span>
          </div>
        </div>

        <div class="confirm-buttons">
          <button @click="confirmAttendance" class="btn-confirm-attendance">
            ✓ Xác nhận điểm danh
          </button>
          <button @click="closeStudentConfirmModal" class="btn-cancel">
            ✕ Hủy
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Danh sách sinh viên -->
  <div v-if="showStudentListModal" class="modal-overlay" @click.self="closeStudentListModal">
    <div class="modal-content student-list-modal">
      <div class="modal-header">
        <h3>👥 Danh sách sinh viên</h3>
        <button @click="closeStudentListModal" class="btn-close">✕</button>
      </div>
      
      <div class="modal-body">
        <div class="list-info">
          <p><strong>Môn học:</strong> {{ currentSchedule?.mon_hoc }}</p>
          <p><strong>Phòng:</strong> {{ currentSchedule?.so_phong }}</p>
          <p class="attendance-summary">
            Đã điểm danh: <span class="attended">{{ studentList.filter(s => s.da_diem_danh).length }}</span> / 
            Tổng số: <span class="total">{{ studentList.length }}</span>
          </p>
        </div>

        <table class="student-list-table">
          <thead>
            <tr>
              <th>STT</th>
              <th>MSSV</th>
              <th>Họ và tên</th>
              <th>Lớp</th>
              <th>Trạng thái</th>
              <th>Thời gian</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="(student, index) in studentList" 
              :key="student.mssv"
              :class="{ 'attended-row': student.da_diem_danh, 'not-attended-row': !student.da_diem_danh }"
            >
              <td>{{ index + 1 }}</td>
              <td>{{ student.mssv }}</td>
              <td>{{ student.ho_va_ten }}</td>
              <td>{{ student.lop || 'N/A' }}</td>
              <td>
                <span v-if="student.da_diem_danh" class="badge-attended">✓ Đã điểm danh</span>
                <span v-else class="badge-not-attended">○ Chưa điểm danh</span>
              </td>
              <td>{{ student.thoi_gian_diem_danh ? formatDateTime(student.thoi_gian_diem_danh) : '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import { ref, onMounted, computed, nextTick } from 'vue'
// tab hiện tại
const activeTab = ref('info')
 // mật khẩu
 const password = ref({ old: '', new: '', confirm: '' })
 const passwordMessage = ref('')
 
 const changePassword = async () => {
   if (password.value.new !== password.value.confirm) {
     passwordMessage.value = '❌ Mật khẩu xác nhận không khớp.'
     return
   }
   try {
     const res =  await axios.post('/giangvien/doimatkhau', {
       old_password: password.value.old,
       new_password: password.value.new,
       // optional confirmation if backend expects it
      new_password_confirmation: password.value.confirm
     })
     passwordMessage.value = res.data.message ||'✅ Đổi mật khẩu thành công.'
     password.value = { old: '', new: '', confirm: '' }
   } catch (err) {
     passwordMessage.value = (err.response && err.response.data && err.response.data.message) || '❌ Đổi mật khẩu thất bại.'
     console.error(err)
   }
 }
 
// dữ liệu giảng viên
const teacher = ref({
  Ten: '',
  Email: '',
  Sdt: '',
  Bo_Mon: ''
})
function logout() {
  router.post(route('logout'))}
// dữ liệu lịch gác thi & kết quả điểm danh
const schedules = ref([])
const allSchedules = ref([])
const scheduleStatusFilter = ref('pending') // pending, confirmed, rejected, all
const results = ref([])

// Điểm danh modal states
const showAttendanceModal = ref(false)
const showStudentConfirmModal = ref(false)
const showStudentListModal = ref(false)
const attendanceMethod = ref('qr') // 'qr' or 'manual'
const currentSchedule = ref(null)
const manualMssv = ref('')
const foundStudent = ref(null)
const studentList = ref([])
const isScanning = ref(false)

// Attendance page states
const selectedExamForAttendance = ref('')
const attendanceListForDisplay = ref([])
const attendanceFilter = ref('all') // 'all', 'attended', 'not-attended'
const currentExamInfo = ref(null)

// Computed properties để filter lịch theo status
const pendingSchedules = computed(() => 
  allSchedules.value.filter(s => s.status === 'pending')
)
const confirmedSchedules = computed(() => 
  allSchedules.value.filter(s => s.status === 'confirmed')
)
const rejectedSchedules = computed(() => 
  allSchedules.value.filter(s => s.status === 'rejected')
)

const filteredSchedules = computed(() => {
  if (scheduleStatusFilter.value === 'all') return allSchedules.value
  if (scheduleStatusFilter.value === 'pending') return pendingSchedules.value
  if (scheduleStatusFilter.value === 'confirmed') return confirmedSchedules.value
  if (scheduleStatusFilter.value === 'rejected') return rejectedSchedules.value
  return allSchedules.value
})

// Attendance page computed
const attendedStudents = computed(() => 
  attendanceListForDisplay.value.filter(s => s.da_diem_danh)
)

const notAttendedStudents = computed(() => 
  attendanceListForDisplay.value.filter(s => !s.da_diem_danh)
)

const filteredAttendanceList = computed(() => {
  if (attendanceFilter.value === 'attended') return attendedStudents.value
  if (attendanceFilter.value === 'not-attended') return notAttendedStudents.value
  return attendanceListForDisplay.value
})

const attendanceStats = computed(() => {
  if (!attendanceListForDisplay.value.length) return null
  return {
    attended: attendedStudents.value.length,
    notAttended: notAttendedStudents.value.length,
    total: attendanceListForDisplay.value.length
  }
})

// Helper functions
const getStatusClass = (status) => {
  const classes = {
    'pending': 'status-pending',
    'confirmed': 'status-confirmed',
    'rejected': 'status-rejected'
  }
  return classes[status] || ''
}

const getStatusText = (status) => {
  const texts = {
    'pending': '🟡 Chờ xác nhận',
    'confirmed': '🟢 Đã xác nhận',
    'rejected': '🔴 Đã từ chối'
  }
  return texts[status] || status
}

const getEmptyMessage = () => {
  const messages = {
    'pending': 'Không có lịch gác chờ xác nhận',
    'confirmed': 'Chưa có lịch gác nào được xác nhận',
    'rejected': 'Chưa có lịch gác nào bị từ chối',
    'all': 'Không có lịch gác thi nào'
  }
  return messages[scheduleStatusFilter.value] || 'Không có dữ liệu'
}

const formatDate = (dateStr) => {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  return date.toLocaleDateString('vi-VN')
}

const formatDateTime = (dateStr) => {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  return date.toLocaleString('vi-VN')
}

const viewScheduleDetail = (item) => {
  alert(`Chi tiết lịch gác:\n\nMôn: ${item.mon_hoc}\nNgày: ${formatDate(item.ngay_thi)}\nGiờ: ${item.gio_bat_dau} - ${item.gio_ket_thuc}\nPhòng: ${item.so_phong} (${item.toa_nha})\nVai trò: ${item.role}\nTrạng thái: ${getStatusText(item.status)}`)
}

// Xác nhận lịch gác
const confirmSchedule = async (id) => {
  if (!confirm('Bạn có chắc chắn muốn xác nhận lịch gác thi này không?')) return

  try {
    const res = await axios.post(`/giangvien/phan-cong/${id}/confirm`)
    if (res.data.success) {
      alert('✅ Đã xác nhận lịch gác thi thành công!')
      await fetchSchedules() // Reload danh sách
    }
  } catch (err) {
    console.error('Lỗi khi xác nhận:', err)
    alert('❌ Không thể xác nhận lịch gác: ' + (err.response?.data?.message || err.message))
  }
}

// Từ chối lịch gác
const rejectSchedule = async (id) => {
  const lyDo = prompt('Vui lòng nhập lý do từ chối (không bắt buộc):')
  if (lyDo === null) return // User clicked Cancel

  try {
    const res = await axios.post(`/giangvien/phan-cong/${id}/reject`, {
      ly_do: lyDo
    })
    if (res.data.success) {
      alert('✅ Đã từ chối lịch gác thi')
      await fetchSchedules() // Reload danh sách
    }
  } catch (err) {
    console.error('Lỗi khi từ chối:', err)
    alert('❌ Không thể từ chối lịch gác: ' + (err.response?.data?.message || err.message))
  }
}

// ==================== ĐIỂM DANH FUNCTIONS ====================

// Mở modal điểm danh
const openAttendanceModal = (schedule) => {
  currentSchedule.value = schedule
  showAttendanceModal.value = true
  attendanceMethod.value = 'qr' // Default to QR scan
}

// Đóng modal điểm danh
const closeAttendanceModal = () => {
  showAttendanceModal.value = false
  currentSchedule.value = null
  manualMssv.value = ''
  stopQRScanner()
}

// Chuyển đổi phương thức điểm danh
const switchAttendanceMethod = (method) => {
  attendanceMethod.value = method
  if (method === 'qr') {
    nextTick(() => startQRScanner())
  } else {
    stopQRScanner()
  }
}

// Start QR Scanner
let html5QrcodeScanner = null
const startQRScanner = async () => {
  if (isScanning.value) return
  
  try {
    const { Html5Qrcode } = await import('html5-qrcode')
    const qrReaderElement = document.getElementById('qr-reader')
    
    if (!qrReaderElement) {
      console.error('QR reader element not found')
      return
    }
    
    html5QrcodeScanner = new Html5Qrcode("qr-reader")
    isScanning.value = true
    
    await html5QrcodeScanner.start(
      { facingMode: "environment" }, // Use back camera
      {
        fps: 10,
        qrbox: { width: 250, height: 250 }
      },
      onScanSuccess,
      onScanFailure
    )
  } catch (err) {
    console.error('Error starting QR scanner:', err)
    alert('❌ Không thể khởi động camera. Vui lòng kiểm tra quyền truy cập camera.')
    isScanning.value = false
  }
}

// Stop QR Scanner
const stopQRScanner = async () => {
  if (html5QrcodeScanner && isScanning.value) {
    try {
      await html5QrcodeScanner.stop()
      html5QrcodeScanner.clear()
      html5QrcodeScanner = null
      isScanning.value = false
    } catch (err) {
      console.error('Error stopping scanner:', err)
    }
  }
}

// QR Scan success handler
const onScanSuccess = (decodedText, decodedResult) => {
  console.log('QR Code detected:', decodedText)
  
  // Parse QR: MSSV_TEN_DD.MM.YYYY (ví dụ: DH52200662_NGUYENMINHHIEN_30.09.2004)
  const parts = decodedText.split('_')
  if (parts.length >= 1) {
    const mssv = parts[0].trim()
    stopQRScanner()
    lookupStudent(mssv, 'qr_code')
  } else {
    alert('❌ Mã QR không hợp lệ!')
  }
}

// QR Scan failure handler
const onScanFailure = (error) => {
  // Ignore continuous scan errors
}

// Nhập MSSV thủ công
const submitManualMssv = () => {
  if (!manualMssv.value.trim()) {
    alert('⚠️ Vui lòng nhập MSSV')
    return
  }
  lookupStudent(manualMssv.value.trim(), 'manual')
}

// Tìm kiếm sinh viên theo MSSV
const lookupStudent = async (mssv, method) => {
  try {
    const res = await axios.get(`/giangvien/sinh-vien/${mssv}`)
    if (res.data.success && res.data.student) {
      foundStudent.value = {
        ...res.data.student,
        phuong_thuc: method
      }
      showStudentConfirmModal.value = true
      showAttendanceModal.value = false
    } else {
      alert('❌ Không tìm thấy sinh viên với MSSV: ' + mssv)
    }
  } catch (err) {
    console.error('Lỗi khi tìm sinh viên:', err)
    alert('❌ Không thể tìm sinh viên: ' + (err.response?.data?.message || err.message))
  }
}

// Xác nhận điểm danh sinh viên
const confirmAttendance = async () => {
  if (!foundStudent.value || !currentSchedule.value) return
  
  try {
    const res = await axios.post('/giangvien/diem-danh', {
      lich_thi_id: currentSchedule.value.exam_id,
      mssv: foundStudent.value.Mssv,
      phuong_thuc: foundStudent.value.phuong_thuc
    })
    
    if (res.data.success) {
      alert('✅ Đã điểm danh thành công cho sinh viên: ' + foundStudent.value.Ho_va_ten)
      closeStudentConfirmModal()
      await fetchSchedules() // Refresh để update số lượng đã điểm danh
    }
  } catch (err) {
    console.error('Lỗi khi điểm danh:', err)
    alert('❌ Không thể điểm danh: ' + (err.response?.data?.message || err.message))
  }
}

// Đóng modal xác nhận sinh viên
const closeStudentConfirmModal = () => {
  showStudentConfirmModal.value = false
  foundStudent.value = null
  manualMssv.value = ''
  // Quay lại modal điểm danh
  showAttendanceModal.value = true
  if (attendanceMethod.value === 'qr') {
    nextTick(() => startQRScanner())
  }
}

// Xem danh sách sinh viên
const viewStudentList = async (schedule) => {
  try {
    const res = await axios.get(`/giangvien/lich-thi/${schedule.exam_id}/sinh-vien`)
    if (res.data.success) {
      studentList.value = res.data.students || []
      currentSchedule.value = schedule
      showStudentListModal.value = true
    }
  } catch (err) {
    console.error('Lỗi khi lấy danh sách sinh viên:', err)
    alert('❌ Không thể lấy danh sách sinh viên')
  }
}

// Đóng modal danh sách sinh viên
const closeStudentListModal = () => {
  showStudentListModal.value = false
  studentList.value = []
  currentSchedule.value = null
}

// ==================== ATTENDANCE PAGE FUNCTIONS ====================

// Load danh sách sinh viên khi chọn lịch thi
const loadAttendanceList = async () => {
  if (!selectedExamForAttendance.value) {
    attendanceListForDisplay.value = []
    currentExamInfo.value = null
    return
  }

  try {
    // Lấy thông tin lịch thi
    const schedule = confirmedSchedules.value.find(s => s.exam_id == selectedExamForAttendance.value)
    currentExamInfo.value = schedule

    // Lấy danh sách sinh viên
    const res = await axios.get(`/giangvien/lich-thi/${selectedExamForAttendance.value}/sinh-vien`)
    if (res.data.success) {
      attendanceListForDisplay.value = res.data.students || []
    }
  } catch (err) {
    console.error('Lỗi khi tải danh sách:', err)
    alert('❌ Không thể tải danh sách sinh viên')
    attendanceListForDisplay.value = []
  }
}

// Helper: Lấy label phương thức điểm danh
const getMethodLabel = (method) => {
  const labels = {
    'qr_code': '📷 QR Code',
    'manual': '⌨️ Thủ công',
    'face_recognition': '👤 Nhận diện'
  }
  return labels[method] || method
}

// Helper: Message khi list rỗng
const getAttendanceEmptyMessage = () => {
  if (attendanceFilter.value === 'attended') {
    return 'Chưa có sinh viên nào điểm danh'
  }
  if (attendanceFilter.value === 'not-attended') {
    return 'Tất cả sinh viên đã điểm danh'
  }
  return 'Không có sinh viên trong danh sách'
}

// fetch functions
const fetchTeacher = async () => {
  try {
    // điều chỉnh URL nếu backend của bạn dùng /api/...
    const res = await axios.get('/giangvien/thongtin')
    // nếu backend trả object khác, map lại cho phù hợp
    teacher.value = {
      Ten: res.data.Ten ?? res.data.ten ?? (res.data.name || ''),
      Email: res.data.Email ?? res.data.email ?? '',
      Sdt: res.data.Sdt ?? res.data.sdt ?? '',
      Bo_Mon: res.data.Bo_Mon ?? res.data.bo_mon ?? ''
    }
  } catch (err) {
    console.error('fetchTeacher failed', err)
  }
}

const fetchSchedules = async () => {
   try {
    console.log('GET /giangvien/lichgac -> sending')
    const res = await axios.get('/giangvien/lichgac', {
      params: { status: 'all' } // Lấy tất cả status
    })
    console.log('res.status', res.status, 'data', res.data)
    
    if (res.data.success) {
      allSchedules.value = res.data.data || []
      schedules.value = res.data.data || []
    } else {
      allSchedules.value = res.data || []
      schedules.value = res.data || []
    }
  } catch (err) {
    console.error('fetchSchedules failed', err.response ? err.response.status : err.message, err.response ? err.response.data : '')
    allSchedules.value = []
    schedules.value = []
  }
}

const fetchResults = async () => {
  try {
    const res = await axios.get('/giangvien/ketqua')
    results.value = res.data || []
  } catch (err) {
    console.error('fetchResults failed', err)
  }
}
onMounted(() => {
  fetchSchedules()
})
// cập nhật thông tin
const updateInfo = async() => {
  try {
    await axios.put('/giangvien/capnhat', teacher.value)
    alert('Cập nhật thành công.')
  } catch (err) {
    console.error('updateInfo failed', err)
    alert('Cập nhật thất bại.')
  }
}

// gửi ảnh AWS điểm danh
const captureImage = () => {
  // Lấy hình ảnh từ webcam gửi đến backend (AWS Rekognition)
  console.log('Gửi ảnh điểm danh tới AWS')
}
onMounted(() => {
  fetchTeacher()
  fetchSchedules()
  fetchResults()
})
</script>

<style scoped>
.app {
  display: flex;
  min-height: 100vh;
}

.sidebar {
  width: 260px;
  background: #d5d9db;
  color: rgb(255, 255, 255);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}


.menu {
  display: flex;
  flex-direction: column;
  
}

.menu button {
  background: none;
  color: #022445;
  text-align: left;
  padding: 12px 25px;
  border: none;
  cursor: pointer;
  font-size: 18px;
}

.menu button.active {
  background: rgb(249, 249, 249);
}



.logout {
  background: #f3f3f5;
  border: none;
  color: #0c7de7;
  width: 40px;
  height: 40px;
  margin: 20px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.logout:hover {
  background: #dbe9fa;
  transform: scale(1.05);
}

.logout i {
  font-size: 18px;
}

.main-content {
  flex: 1;
  background: #ecf0f1;
  padding: 30px;
}
* {
  font-family: "Times New Roman", Times, serif;
}



h2 {
  font-size: 20px;
  font-weight: bold;
  margin-bottom: 20px;
}
.tt{
  color: #0c7de7;
  font-size: 28px;
}
.h1, h2, label, li, p {
  color: #0c7de7;
  font-size: 28px;
  text-align: center;
}
.centered-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 80vh;
}





.centered-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  min-height: 80vh;
  margin-top: 15px; /* sát hơn với tiêu đề */
}

.info-form {
  display: flex;
  flex-direction: column;
  align-items: center; /* căn giữa toàn form */
  gap: 18px;
  background: #fff;
  padding: 35px 60px;
  border-radius: 16px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  width: 900px; /* form rộng hơn */
  margin-top: 15px;
}

.form-row {
  display: flex;
  align-items: center;
  gap: 20px;
  width: 100%;
  
}

.form-row label {
  font-weight: 600;
  width: 220px;
  text-align: left;
  color: #0c7de7;
  font-size: 20px;
}

.form-row input {
  flex: 1;
  padding: 10px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 15px;
}

.btn-update {
  background: #0c7de7;
  color: white;
  padding: 10px 22px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.2s;
  margin-top: 20px;
  align-self: center; /* ✅ căn giữa nút */
}

.btn-update:hover {
  background: #095cb3;
}







.btn-update {
  background: #27ae60;
  color: white;
  padding: 8px;
  border: none;
  border-radius: 6px;
  margin-top: 10px;
  cursor: pointer;
}

.table{width:100%;border-collapse:collapse;margin-top:8px}
.table th{background:#f6f8f9;text-align:center;padding:12px;border-bottom:1px solid #eee;color:#2b5f86}
.table td{padding:12px;border-bottom:1px solid #f1f4f5}

.attendance-box {
  display: flex;
  flex-direction: column;
  align-items: center;
}

video {
  width: 480px;
  height: 360px;
  background: black;
  margin-bottom: 15px;
}

.btn-scan {
  background: #39adf0;
  color: white;
  padding: 10px 20px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
}

/* Tabs cho lịch gác thi */
.schedule-tabs {
  display: flex;
  gap: 12px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.tab-button {
  padding: 10px 20px;
  border: 2px solid #ddd;
  background: white;
  color: #666;
  border-radius: 8px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.tab-button:hover {
  border-color: #0c7de7;
  color: #0c7de7;
}

.tab-button.tab-active {
  background: #0c7de7;
  color: white;
  border-color: #0c7de7;
}

/* Status badges */
.status-pending {
  background: #fff3cd;
  color: #856404;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 600;
  display: inline-block;
}

.status-confirmed {
  background: #d4edda;
  color: #155724;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 600;
  display: inline-block;
}

.status-rejected {
  background: #f8d7da;
  color: #721c24;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 600;
  display: inline-block;
}

.badge-leader {
  background: #0c7de7;
  color: white;
  padding: 4px 10px;
  border-radius: 10px;
  font-size: 12px;
  font-weight: 600;
}

.badge-supervisor {
  background: #6c757d;
  color: white;
  padding: 4px 10px;
  border-radius: 10px;
  font-size: 12px;
  font-weight: 600;
}

/* Action buttons */
.action-buttons {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.btn-confirm {
  background: #28a745;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 13px;
  font-weight: 500;
  transition: background 0.2s;
}

.btn-confirm:hover {
  background: #218838;
}

.btn-reject {
  background: #dc3545;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 13px;
  font-weight: 500;
  transition: background 0.2s;
}

.btn-reject:hover {
  background: #c82333;
}

.btn-view {
  background: #17a2b8;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 13px;
  font-weight: 500;
  transition: background 0.2s;
}

.btn-view:hover {
  background: #138496;
}

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

/* ==================== ATTENDANCE STYLES ==================== */

.attendance-cell {
  display: flex;
  flex-direction: column;
  gap: 8px;
  align-items: center;
}

.attendance-stats {
  font-size: 13px;
  color: #666;
  font-weight: 600;
}

.attendance-count {
  background: #e3f2fd;
  padding: 2px 8px;
  border-radius: 12px;
  color: #1976d2;
}

.attendance-actions {
  display: flex;
  gap: 6px;
}

.btn-attendance {
  background: #4caf50;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 12px;
  font-weight: 500;
  transition: all 0.2s;
}

.btn-attendance:hover {
  background: #45a049;
  transform: translateY(-1px);
}

.btn-view-list {
  background: #2196f3;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 12px;
  font-weight: 500;
  transition: all 0.2s;
}

.btn-view-list:hover {
  background: #1976d2;
  transform: translateY(-1px);
}

/* Modal Overlay */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  animation: fadeIn 0.2s;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.modal-content {
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
  max-width: 600px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
  animation: slideUp 0.3s;
}

@keyframes slideUp {
  from {
    transform: translateY(50px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 2px solid #e0e0e0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-radius: 12px 12px 0 0;
}

.modal-header h3 {
  margin: 0;
  font-size: 20px;
  font-weight: 600;
}

.btn-close {
  background: rgba(255, 255, 255, 0.2);
  color: white;
  border: none;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 20px;
  line-height: 1;
  transition: background 0.2s;
}

.btn-close:hover {
  background: rgba(255, 255, 255, 0.3);
}

.modal-body {
  padding: 20px;
}

/* Attendance Modal */
.attendance-info {
  background: #f5f5f5;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 20px;
}

.attendance-info p {
  margin: 5px 0;
  font-size: 14px;
}

.attendance-tabs {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

.attendance-tab {
  flex: 1;
  padding: 12px;
  border: 2px solid #e0e0e0;
  background: white;
  border-radius: 8px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.2s;
}

.attendance-tab:hover {
  border-color: #667eea;
  background: #f8f9ff;
}

.attendance-tab.tab-active {
  border-color: #667eea;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

/* QR Scanner */
.qr-scanner-container {
  text-align: center;
}

#qr-reader {
  width: 100%;
  max-width: 400px;
  margin: 0 auto;
  border: 3px dashed #667eea;
  border-radius: 12px;
  overflow: hidden;
}

.qr-hint {
  margin-top: 15px;
  color: #666;
  font-size: 14px;
  font-style: italic;
}

/* Manual Input */
.manual-input-container {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.manual-input-container label {
  font-weight: 600;
  color: #333;
  font-size: 14px;
}

.input-mssv {
  padding: 12px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 16px;
  transition: border-color 0.2s;
}

.input-mssv:focus {
  outline: none;
  border-color: #667eea;
}

.btn-submit-mssv {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 16px;
  font-weight: 600;
  transition: transform 0.2s;
}

.btn-submit-mssv:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

/* Student Confirm Modal */
.student-info-card {
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 20px;
}

.info-row {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.info-row:last-child {
  border-bottom: none;
}

.info-label {
  font-weight: 600;
  color: #555;
}

.info-value {
  font-weight: 500;
  color: #333;
}

.confirm-buttons {
  display: flex;
  gap: 10px;
}

.btn-confirm-attendance {
  flex: 1;
  background: #4caf50;
  color: white;
  border: none;
  padding: 14px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 16px;
  font-weight: 600;
  transition: all 0.2s;
}

.btn-confirm-attendance:hover {
  background: #45a049;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
}

.btn-cancel {
  flex: 1;
  background: #f44336;
  color: white;
  border: none;
  padding: 14px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 16px;
  font-weight: 600;
  transition: all 0.2s;
}

.btn-cancel:hover {
  background: #da190b;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(244, 67, 54, 0.4);
}

/* Student List Modal */
.student-list-modal {
  max-width: 900px;
}

.list-info {
  background: #f5f5f5;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 20px;
}

.list-info p {
  margin: 5px 0;
  font-size: 14px;
}

.attendance-summary {
  margin-top: 10px;
  font-weight: 600;
  font-size: 15px;
}

.attendance-summary .attended {
  color: #4caf50;
}

.attendance-summary .total {
  color: #2196f3;
}

.student-list-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}

.student-list-table th,
.student-list-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #e0e0e0;
}

.student-list-table th {
  background: #f8f9fa;
  font-weight: 600;
  color: #555;
  position: sticky;
  top: 0;
}

.student-list-table tbody tr:hover {
  background: #f8f9ff;
}

.attended-row {
  background: #e8f5e9;
}

.not-attended-row {
  background: #fafafa;
}

.badge-attended {
  background: #4caf50;
  color: white;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
}

.badge-not-attended {
  background: #9e9e9e;
  color: white;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
}

/* ==================== ATTENDANCE PAGE STYLES ==================== */

.attendance-selector {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 20px;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.attendance-selector label {
  display: block;
  color: white;
  font-weight: 600;
  margin-bottom: 10px;
  font-size: 16px;
}

.exam-dropdown {
  width: 100%;
  padding: 12px 16px;
  font-size: 15px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  background: white;
  cursor: pointer;
  transition: all 0.3s;
  font-weight: 500;
}

.exam-dropdown:focus {
  outline: none;
  border-color: #764ba2;
  box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.2);
}

.exam-dropdown option {
  padding: 10px;
}

.attendance-summary-box {
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 20px;
  border: 2px solid #e0e0e0;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.summary-item:last-child {
  border-bottom: none;
}

.summary-item .label {
  font-weight: 600;
  color: #555;
  min-width: 120px;
}

.summary-item .value {
  color: #333;
  font-weight: 500;
  text-align: right;
}

.summary-item.stats .value {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
  justify-content: flex-end;
}

.stat-attended {
  color: #4caf50;
  font-weight: 600;
}

.stat-not-attended {
  color: #ff9800;
  font-weight: 600;
}

.stat-total {
  color: #2196f3;
  font-weight: 600;
}

.filter-tabs {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.filter-tab {
  flex: 1;
  min-width: 200px;
  padding: 12px 20px;
  border: 2px solid #e0e0e0;
  background: white;
  border-radius: 8px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 600;
  transition: all 0.2s;
  color: #666;
}

.filter-tab:hover {
  border-color: #667eea;
  background: #f8f9ff;
  transform: translateY(-2px);
}

.filter-tab.active {
  border-color: #667eea;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.attendance-list-container {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.attendance-table {
  width: 100%;
  border-collapse: collapse;
}

.attendance-table th,
.attendance-table td {
  padding: 14px 12px;
  text-align: left;
  border-bottom: 1px solid #e0e0e0;
  font-size: 14px;
}

.attendance-table th {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 13px;
  letter-spacing: 0.5px;
  position: sticky;
  top: 0;
  z-index: 10;
}

.attendance-table tbody tr {
  transition: all 0.2s;
}

.attendance-table tbody tr:hover {
  background: #f8f9ff;
  transform: scale(1.01);
}

.row-attended {
  background: #e8f5e9;
}

.row-attended:hover {
  background: #c8e6c9 !important;
}

.row-not-attended {
  background: #fff3e0;
}

.row-not-attended:hover {
  background: #ffe0b2 !important;
}

.status-badge {
  display: inline-block;
  padding: 6px 14px;
  border-radius: 16px;
  font-size: 13px;
  font-weight: 600;
}

.status-badge.attended {
  background: #4caf50;
  color: white;
}

.status-badge.not-attended {
  background: #ff9800;
  color: white;
}

.method-badge {
  background: #2196f3;
  color: white;
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
}

.text-muted {
  color: #999;
  font-style: italic;
}

.text-center {
  text-align: center;
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #999;
}

.empty-icon {
  font-size: 80px;
  margin-bottom: 20px;
  opacity: 0.5;
}

.empty-state p {
  font-size: 18px;
  font-weight: 500;
  color: #666;
}

</style>



