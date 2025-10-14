<template>
  <div class="app">
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="brand">
        <div class="logo">SV</div>
        <div class="brand-text">
          <h1 class="title">Cổng thông tin sinh viên</h1>
          <p class="lead">KHOA CÔNG NGHỆ THÔNG TIN</p>
        </div>
      </div>

      <nav>
        <ul>
          <li><a href="#" :class="{ active: currentPage === 'home' }" @click.prevent="showPage('home')">🏠 Trang chủ</a></li>
          <li><a href="#" :class="{ active: currentPage === 'profile' }" @click.prevent="showPage('profile')">👤 Thông tin cá nhân</a></li>
          <li><a href="#" :class="{ active: currentPage === 'results' }" @click.prevent="showPage('results')">📊 Kết quả</a></li>
        </ul>
      </nav>

      <div class="logout-wrap">
        <button class="btn-logout" @click="logout">⎋ Đăng xuất</button>
      </div>
    </aside>

    <!-- MAIN -->
    <main>
      <!-- TRANG CHỦ -->
      <section v-if="currentPage === 'home'">
        <div class="card blue-card">
          <h2>Lịch thi & Phòng thi</h2>
          <table>
            <thead>
              <tr>
                <th>Ngày</th>
                <th>Môn</th>
                <th>Mã môn</th>
                <th>Giờ</th>
                <th>Phòng</th>
                <th>Ghi chú</th>
              </tr>
            </thead>
            <tbody>
              <slot name="exams">
                <tr><td colspan="6" class="muted">(Chưa có dữ liệu lịch thi)</td></tr>
              </slot>
            </tbody>
          </table>
        </div>
      </section>

      <!-- TRANG THÔNG TIN CÁ NHÂN -->
      <section v-if="currentPage === 'profile'">
        <div class="card blue-card">
          <h2>Thông tin cá nhân</h2>

          <!-- 🧑‍🎓 Thông tin sinh viên -->
          <div class="student-info">
            <img class="avatar" src="https://via.placeholder.com/120" alt="Ảnh sinh viên">

            <div class="info-fields">
              <div class="field"><strong>Họ:</strong> Nguyễn</div>
              <div class="field"><strong>Tên:</strong> Văn An</div>
              <div class="field"><strong>Email:</strong> an.nguyen@student.edu.vn</div>
              <div class="field"><strong>Ngày sinh:</strong> 12/05/2004</div>
              <div class="field"><strong>MSSV:</strong> 215480201234</div>
              <div class="field"><strong>Lớp:</strong> CNTT K20A</div>
              <div class="field"><strong>Khoa:</strong> Công nghệ thông tin</div>
            </div>
          </div>
        </div>
      </section>

      <!-- TRANG KẾT QUẢ -->
      <section v-if="currentPage === 'results'">
        <div class="card blue-card">
          <h2>Kết quả điểm danh</h2>
          <table>
            <thead>
              <tr>
                <th>Ngày</th>
                <th>Môn</th>
                <th>Trạng thái</th>
                <th>Ghi chú</th>
              </tr>
            </thead>
            <tbody>
              <slot name="results">
                <tr><td colspan="4" class="muted">(Chưa có dữ liệu kết quả)</td></tr>
              </slot>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>
</template>

<script>
export default {
  name: "StudentPortal",
  data() {
    return { currentPage: "home" };
  },
  methods: {
    showPage(page) { this.currentPage = page; },
    handleLogout() { this.$emit("logout"); },
  },
};
</script>

<style scoped>
:root {
  --primary: hwb(215 0% 0%);
  --logout: #20a4f7;
  --muted: #586071;
  --bg: #f6f8fb;
}

/* === LAYOUT === */
.app {
  display: grid;
  grid-template-columns: 260px 1fr;
  background: linear-gradient(180deg, #eef6ff, var(--bg));
  min-height: 100vh;
  font-family: "Inter", sans-serif;
}

/* === SIDEBAR === */
.sidebar {
  padding: 28px 20px;
  background: #ffffff;
  border-right: 1px solid rgba(15, 23, 42, 0.05);
}

/* Tiêu đề căn giữa */
.brand {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 10px;
  margin-bottom: 24px;
}

.logo {
  width: 60px;
  height: 60px;
  border-radius: 12px;
  background: linear-gradient(135deg, var(--primary), #7cc1ff);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 700;
  font-size: 20px;
}

.title {
  font-size: 18px;
  font-weight: 700;
  color: #045c9c;
  margin: 4px 0 0;
}

.lead {
  color: var(--muted);
  font-size: 13px;
  margin: 2px 0 0;
}

/* === NAVIGATION === */
nav ul {
  list-style: none;
  padding: 0;
}

nav a {
  display: block;
  padding: 10px 12px;
  border-radius: 10px;
  color: #0f172a;
  text-decoration: none;
  font-weight: 600;
  margin-bottom: 6px;
  transition: all 0.15s;
}

nav a:hover {
  background: #e9f5ff;
}

nav a.active {
  background: linear-gradient(90deg, #e6f0ff, #f2fbff);
  color: var(--primary);
}

/* === LOGOUT === */
.logout-wrap {
  margin-top: 20px;
}

.btn-logout {
  width: 100%;
  background: var(--logout);
  color: #08304a;
  border: none;
  padding: 10px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 700;
  transition: 0.2s;
}

.btn-logout:hover {
  background: hsl(199, 84%, 55%);
}

/* === MAIN === */
main {
  padding: 28px;
}

/* === CARD === */
.card {
  padding: 18px;
  border-radius: 12px;
  margin-bottom: 16px;
}

.blue-card {
  background: linear-gradient(180deg, #e6f5ff 0%, #ffffff 85%);
  border: 1px solid rgba(91, 192, 255, 0.2);
  box-shadow: 0 8px 20px rgba(91, 192, 255, 0.12);
}

.blue-card h2 {
  color: #045c9c;
  border-bottom: 1px solid rgba(91, 192, 255, 0.2);
  padding-bottom: 6px;
  margin-bottom: 12px;
}

/* 🧑‍🎓 Thông tin sinh viên */
.student-info {
  display: flex;
  align-items: center;
  gap: 20px;
  background: #f9fcff;
  border: 1px solid rgba(91, 192, 255, 0.15);
  border-radius: 12px;
  padding: 20px;
}

.avatar {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  border: 3px solid #a3d8ff;
  object-fit: cover;
}

.info-fields {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px 40px;
  width: 100%;
}

.field {
  font-size: 15px;
  color: #0b305a;
}

.field strong {
  color: #045c9c;
  width: 90px;
  display: inline-block;
}

/* === TABLE === */
table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 10px 12px;
  border-bottom: 1px solid rgba(11, 22, 40, 0.05);
  text-align: left;
}

th {
  color: var(--muted);
  font-weight: 600;
  font-size: 13px;
}

tr:hover td {
  background: rgba(91, 192, 255, 0.07);
  transition: background 0.2s;
}

/* Responsive */
@media (max-width: 880px) {
  .app { grid-template-columns: 1fr; }
  .student-info { flex-direction: column; align-items: flex-start; }
  .info-fields { grid-template-columns: 1fr; }
  .avatar { margin: 0 auto; }
}


</style>

<script setup>
import { router } from '@inertiajs/vue3'
function logout() {   
  router.post(route('logout'))
}

</script>
