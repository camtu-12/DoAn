<template>
  <div class="min-h-screen bg-gray-100 flex flex-col">
    <!-- Header -->
    <header class="flex justify-between items-center bg-blue-600 text-white px-6 py-4 shadow">
       <!-- Bên trái: Tiêu đề + Khoa -->
        <div class="flex flex-col">
          <h1 class="text-xl font-bold">HỆ THỐNG QUẢN LÝ LUẬN VĂN TỐT NGHIỆP</h1>
          <h5 class="text-sm font-medium mt-1">KHOA CÔNG NGHỆ THÔNG TIN</h5>
        </div>
      <!-- Góc phải: Thông tin admin -->
      <div class="relative">
        <div
        class="flex items-center space-x-3 cursor-pointer"
        @click="toggleMenu"
        >
        <div class="w-10 h-10 bg-white text-blue-600 font-bold rounded-full flex items-center justify-center">A</div>
        <div class="text-right">
          <div class="font-semibold text-sm">{{ user.name }}</div>
          <div class="text-xs opacity-80">Admin</div>
        </div>
      </div>
      <!-- Dropdown menu (Copy sang mấy index kia để có thể logout)-->
      <div
       v-if="showMenu"
       class="absolute right-0 mt-2 bg-white text-black rounded shadow w-40 z-50"
       >
       <button class="w-full text-left px-4 py-2 hover:bg-gray-100">Profile</button>
       <button class="w-full text-left px-4 py-2 hover:bg-gray-100" @click="logout">Log out</button>
      </div>
    </div>

    </header>

    <!-- Nội dung chính: sidebar + main -->
    <div class="flex flex-1">
      <!-- Sidebar -->
      <aside class="w-64 bg-white border-r p-6">
        <nav class="space-y-4">
          <button class="block text-left text-blue-600 hover:underline">Trang chủ</button>
          <button class="block text-left text-blue-600 hover:underline">Quản lý sinh viên</button>
          <button class="block text-left text-blue-600 hover:underline">Quản lý đề tài</button>
          <button class="block text-left text-blue-600 hover:underline">Quản lý giảng viên</button>
        </nav>
      </aside>

      <!-- Main content: Be click vào ra index này-->
      <main class="flex-1 p-8">
        <h1 class="text-2xl font-bold mb-6">TRANG CHỦ</h1>

       <!-- Search bar: Be để nó tìm kiếm được -->
      <input
        type="text"
        placeholder="Tìm kiếm theo tên GVHD hoặc tên đề tài..."
        class="w-full mb-4 p-2 border rounded"
      />

        <!-- Bảng đăng ký đề tài -->
        <table class="w-full bg-white rounded shadow">
          <thead class="bg-gray-100 text-left">
            <tr>
              <th class="p-3">Mã đề tài</th>
              <th class="p-3">Tên đề tài</th>
              <th class="p-3">Giảng viên</th>
              <th class="p-3">Số lượng</th>
              <th class="p-3">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(topic, i) in topics" :key="i" class="border-t">
              <td class="p-3">{{ topic.code }}</td>
              <td class="p-3">{{ topic.title }}</td>
              <td class="p-3">{{ topic.lecturer }}</td>
              <td class="p-3">{{ topic.limit }}</td>
            <td class="p-3">
              <button
              class="px-3 py-1 rounded font-medium text-white"
              :class="{
                'bg-green-600 hover:bg-green-700': topic.status === 'Hoàn thành',
                'bg-yellow-500 hover:bg-yellow-600': topic.status === 'Chờ duyệt',
                'bg-blue-600 hover:bg-blue-700': topic.status === 'Đang thực hiện'
              }"
              >
              {{ topic.status }}
            </button>
          </td>
            </tr>
          </tbody>
        </table>
      </main>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const showMenu = ref(false)

function toggleMenu() {
  showMenu.value = !showMenu.value
}

function logout() {
  router.post(route('logout'))
}

defineProps({
  user: Object,
});
const topics = [
  {
    code: '',
    title: '',
    lecturer: '',
    limit: 0,
    status: ''
  },
  {
    code: '',
    title: '',
    lecturer: '',
    limit: 0,
    status: ''
  },
   {
    code: '',
    title: '',
    lecturer: '',
    limit: 0,
    status: ''
  },
   {
    code: '',
    title: '',
    lecturer: '',
    limit: 0,
    status: ''
  },
   {
    code: '',
    title: '',
    lecturer: '',
    limit: 0,
    status: ''
  },
   {
    code: '',
    title: '',
    lecturer: '',
    limit: 0,
    status: ''
  },
]
</script>