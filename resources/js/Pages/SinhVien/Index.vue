<template>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="profile">
        <div class="avatar">SV</div>
        <h3>Sinh Viên</h3>

        <!-- Menu chức năng -->
        <nav>
          <ul class="menu-list">
            <li :class="{ active: currentTab === 'info' }" @click="currentTab = 'info'">Thông tin cá nhân</li>
            <li :class="{ active: currentTab === 'attendance' }" @click="currentTab = 'attendance'">Kết quả điểm danh</li>
            <li :class="{ active: currentTab === 'exam' }" @click="currentTab = 'exam'">Lịch thi & Phòng thi</li>
            <li :class="{ active: currentTab === 'password' }" @click="currentTab = 'password'">Đổi mật khẩu</li>
          </ul>
        </nav>
      </div>

      <!-- Nút đăng xuất -->
      <div class="sidebar-logout">
        <button class="logout-btn" @click="logout">Đăng xuất</button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <header class="topbar">
        <h2>Xin chào, {{ student.name }}</h2>
      </header>

      <!-- Thông tin cá nhân -->
      <section v-if="currentTab === 'info'" class="section">
        <p class="Tieude">Thông tin cá nhân</p>

        <form @submit.prevent="updateProfile">
          <label>Họ và tên:</label>
          <input type="text" v-model="name" required />

          <label>Email:</label>
          <input type="email" v-model="student.email" required />

          <label>Ngày sinh:</label>
          <input type="date" v-model="student.ngay_sinh" required />

          <label>MSSV:</label>
          <input type="text" v-model="student.mssv" required />

          <label>Lớp:</label>
          <input type="text" v-model="student.lop" required />

          <label>Khoa:</label>
          <select v-model="student.khoa" required>
            <option disabled value="">-- Chọn khoa --</option>
            <option>Công Nghệ Thông Tin</option>
            <option>Quản Trị Kinh Doanh</option>
            <option>Kỹ thuật Công Trình</option>
            <option>Điện - Điện Thử</option>
            <option>Công Nghệ Thực Phẩm</option>
            <option>Design</option>
            <option>Luật Kinh Tế</option>
          </select>

          <img :src="student.photo_url" alt="Ảnh sinh viên" class="avatar-img" />

          <button type="submit" class="update-btn">Cập nhật thông tin</button>
        </form>
        <p v-if="profileMessage">{{ profileMessage }}</p>
      </section>

      <!-- Kết quả điểm danh -->
      <section v-if="currentTab === 'attendance'" class="section">
        <p class="Tieude">Kết quả điểm danh</p>
        <ul>
          <li v-for="record in attendance" :key="record.id">
            {{ record.subject }} - {{ record.date }}:
            <span :class="record.status === 'Đã điểm danh' ? 'checked' : 'not-checked'">
              {{ record.status }}
            </span>
          </li>
        </ul>
      </section>

      <!-- Lịch thi & Phòng thi -->
      <section v-if="currentTab === 'exam'" class="section">
        <p class="Tieude">Lịch thi & Phòng thi</p>
        <ul>
          <li v-for="exam in exams" :key="exam.id">
            <strong>{{ exam.subject }}</strong> - {{ exam.date }} - Phòng: {{ exam.room }}
          </li>
        </ul>
      </section>

      <!-- Đổi mật khẩu -->
      <section v-if="currentTab === 'password'" class="section">
        <p class="Tieude">Đổi mật khẩu</p>
        <form @submit.prevent="changePassword">
          <label>Mật khẩu hiện tại:</label>
          <input type="password" v-model="password.old" required />
          <label>Mật khẩu mới:</label>
          <input type="password" v-model="password.new" required />
          <label>Xác nhận mật khẩu mới:</label>
          <input type="password" v-model="password.confirm" required />
          <button type="submit" class="update-btn">Cập nhật mật khẩu</button>
        </form>
        <p v-if="passwordMessage">{{ passwordMessage }}</p>
      </section>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'
// 🧠 Reactive state (thay cho data())
const currentTab = ref('info')
const student = ref({
  ho: '',
  ten: '',
  email: '',
  ngay_sinh: '',
  mssv: '',
  lop: '',
  khoa: '',
  photo: ''
})
const attendance = ref([])
const exams = ref([])
const password = ref({ old: '', new: '', confirm: '' })
const passwordMessage = ref('')
const profileMessage = ref('')


// Computed property to combine and split name
const name = computed({
  get() {
    return `${student.value.ho} ${student.value.ten}`.trim()
  },
  set(value) {
    // Split the full name when edited
    const parts = value.split(' ')
    student.value.ten = parts.pop() // last word = ten
    student.value.ho = parts.join(' ') // rest = ho
  }
})
// 🧩 Hàm logout
function logout() {
  router.post(route('logout'))
}

// 🧩 Gọi API
async function fetchStudentInfo() {
  const res = await axios.get('/sinhvien/thongtin')
  student.value = res.data
}

async function fetchAttendance() {
  const res = await axios.get('/sinhvien/diemdanh')
  attendance.value = res.data
}

async function fetchExamSchedule() {
  const res = await axios.get('/sinhvien/lichthi')
  exams.value = res.data
}

async function changePassword() {
  if (password.value.new !== password.value.confirm) {
    passwordMessage.value = '❌ Mật khẩu xác nhận không khớp.'
    return
  }
  try {
    await axios.post('/sinhvien/doimatkhau', password.value)
    passwordMessage.value = '✅ Đổi mật khẩu thành công.'
    password.value = { old: '', new: '', confirm: '' }
  } catch {
    passwordMessage.value = '❌ Đổi mật khẩu thất bại.'
  }
}

async function updateProfile() {
  try {
    await axios.post('/sinhvien/update', student.value)
    profileMessage.value = '✅ Cập nhật thông tin thành công.'
  } catch {
    profileMessage.value = '❌ Cập nhật thất bại.'
  }
}

// 🧩 Khi component load
onMounted(() => {
  fetchStudentInfo()
  fetchAttendance()
  fetchExamSchedule()
})
</script>

<style scoped>
/* (Giữ nguyên toàn bộ CSS cũ của bạn) */
.Tieude {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 20px;
}
.dashboard-container {
  display: flex;
  height: 100vh;
  font-family: 'Segoe UI', sans-serif;
}
.sidebar {
  width: 400px;
  background-color: #2c3e50;
  color: white;
  padding: 20px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.profile {
  text-align: center;
}
.avatar {
  background-color: #3498db;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  line-height: 60px;
  font-size: 24px;
  margin: auto;
}
.menu-list {
  list-style: none;
  padding: 0;
  margin-top: 20px;
}
.menu-list li {
  padding: 12px 16px;
  cursor: pointer;
  text-align: left;
}
.menu-list li.active {
  background-color: #34495e;
  border-radius: 4px;
}
.sidebar-logout {
  margin-top: 30px;
  text-align: center;
}
.logout-btn {
  background-color: #e74c3c;
  color: white;
  border: none;
  padding: 10px 20px;
  width: 80%;
  cursor: pointer;
  border-radius: 4px;
}
.logout-btn:hover {
  background-color: #c0392b;
}
.main-content {
  flex: 1;
  padding: 30px;
  background-color: #ecf0f1;
}
.topbar {
  margin-bottom: 20px;
}
.section {
  margin-top: 20px;
}
.avatar-img {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
}
.checked {
  color: green;
  font-weight: bold;
}
.not-checked {
  color: red;
  font-weight: bold;
}
form input {
  display: block;
  margin-bottom: 10px;
  padding: 8px;
  width: 100%;
}
.update-btn {
  padding: 10px 20px;
  background-color: #27ae60;
  color: white;
  border: none;
  cursor: pointer;
}
.update-btn:hover {
  background-color: #1e8449;
}
</style>
