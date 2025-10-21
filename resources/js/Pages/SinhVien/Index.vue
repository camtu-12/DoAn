
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
          Thông tin sinh viên
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
  <h2 class="tt">THÔNG TIN SINH VIÊN</h2>

  <form class="info-form" @submit.prevent="updateInfo">
    <div class="form-row">
      <label>Họ và tên:</label>
    </div>

    <div class="form-row">
      <label>Email:</label>
    </div>

    <div class="form-row">
      <label>Số điện thoại:</label>
    </div>

    <div class="form-row">
      <label>Khoa:</label>
    </div>

    <button type="submit" class="btn-update">Cập nhật thông tin</button>
  </form>
</section>

      <!-- Kết quả điểm danh -->
      <section v-if="activeTab === 'result'">
        <h2> KẾT QUẢ ĐIỂM DANH </h2>
        <table class="table">
          <thead>
            <tr>
              <th>MSSV</th>
              <th>Họ tên</th>
              <th>Ngày thi</th>
              <th>Trạng thái</th>
            </tr>
          </thead>
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
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import { ref, onMounted } from 'vue'
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
function logout() {
  router.post(route('logout'))}
// dữ liệu lịch gác thi & kết quả điểm danh
const schedules = ref([])
const results = ref([])
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
    const res = await axios.get('/giangvien/lichgac')
    console.log('res.status', res.status, 'data', res.data)
    schedules.value = res.data || []
  } catch (err) {
    console.error('fetchSchedules failed', err.response ? err.response.status : err.message, err.response ? err.response.data : '')
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

.table {
  width: 100%;
  border-collapse: collapse;
  background: white;
}

.table th,
.table td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: left;
}

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


</style>


