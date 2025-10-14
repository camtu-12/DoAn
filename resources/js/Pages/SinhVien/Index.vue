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
          <input type="text" v-model="student.name" required />

          <label>Email:</label>
          <input type="email" v-model="student.email" required />

          <label>Ngày sinh:</label>
          <input type="date" v-model="student.birthdate" required />

          <label>MSSV:</label>
          <input type="text" v-model="student.student_code" required />

          <label>Lớp:</label>
          <input type="text" v-model="student.class" required />

          <label>Khoa:</label>
          <select v-model="student.major" required>
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

<script>
export default {
  name: 'StudentDashboard',
  data() {
    return {
      currentTab: 'info',
      student: {},
      attendance: [],
      exams: [],
      password: {
        old: '',
        new: '',
        confirm: '',
      },
      passwordMessage: '',
    };
  },
  created() {
    this.fetchStudentInfo();
    this.fetchAttendance();
    this.fetchExamSchedule();
  },
  methods: {
    async fetchStudentInfo() {
      const res = await this.$axios.get('/api/sinhvien/thongtin');
      this.student = res.data;
    },
    async fetchAttendance() {
      const res = await this.$axios.get('/api/sinhvien/diemdanh');
      this.attendance = res.data;
    },
    async fetchExamSchedule() {
      const res = await this.$axios.get('/api/sinhvien/lichthi');
      this.exams = res.data;
    },
    async changePassword() {
      if (this.password.new !== this.password.confirm) {
        this.passwordMessage = '❌ Mật khẩu xác nhận không khớp.';
        return;
      }
      try {
        await this.$axios.post('/api/sinhvien/doimatkhau', this.password);
        this.passwordMessage = '✅ Đổi mật khẩu thành công.';
        this.password = { old: '', new: '', confirm: '' };
      } catch {
        this.passwordMessage = '❌ Đổi mật khẩu thất bại.';
      }
    },
    logout() {
      this.$axios.post('/api/logout').then(() => {
        this.$router.push('/login');
      });
    },
  },
};
</script>

<style scoped>
.Tieude{
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

<script setup>
import { router } from '@inertiajs/vue3'
function logout() {   
  router.post(route('logout'))
}

</script>

