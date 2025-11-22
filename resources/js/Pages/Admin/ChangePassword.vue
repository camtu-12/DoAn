<template>
    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md mx-auto">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h2 class="text-2xl font-bold text-white">
                        🔐 Đổi mật khẩu
                    </h2>
                    <p class="text-blue-100 text-sm mt-1">Cập nhật mật khẩu của bạn</p>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="p-6 space-y-6">
                    <!-- Success Message -->
                    <div v-if="successMessage" class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ successMessage }}
                    </div>

                    <!-- Mật khẩu hiện tại -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Mật khẩu hiện tại
                        </label>
                        <input
                            id="current_password"
                            v-model="form.current_password"
                            type="password"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            :class="{'border-red-500': errors.current_password}"
                            placeholder="Nhập mật khẩu hiện tại"
                        />
                        <p v-if="errors.current_password" class="mt-1 text-sm text-red-600">
                            {{ errors.current_password }}
                        </p>
                    </div>

                    <!-- Mật khẩu mới -->
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Mật khẩu mới
                        </label>
                        <input
                            id="new_password"
                            v-model="form.new_password"
                            type="password"
                            required
                            minlength="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            :class="{'border-red-500': errors.new_password}"
                            placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)"
                        />
                        <p v-if="errors.new_password" class="mt-1 text-sm text-red-600">
                            {{ errors.new_password }}
                        </p>
                    </div>

                    <!-- Xác nhận mật khẩu mới -->
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Xác nhận mật khẩu mới
                        </label>
                        <input
                            id="new_password_confirmation"
                            v-model="form.new_password_confirmation"
                            type="password"
                            required
                            minlength="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Nhập lại mật khẩu mới"
                        />
                    </div>

                    <!-- Buttons -->
                    <div class="flex space-x-3 pt-4">
                        <button
                            type="submit"
                            :disabled="processing"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="!processing">Cập nhật mật khẩu</span>
                            <span v-else>Đang xử lý...</span>
                        </button>
                        <a
                            href="/dashboard"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-lg transition duration-200 text-center"
                        >
                            Quay lại
                        </a>
                    </div>
                </form>

                <!-- Tips -->
                <div class="bg-blue-50 border-t border-blue-100 px-6 py-4">
                    <p class="text-sm text-blue-800 font-medium mb-2">💡 Lưu ý:</p>
                    <ul class="text-xs text-blue-700 space-y-1 list-disc list-inside">
                        <li>Mật khẩu phải có ít nhất 6 ký tự</li>
                        <li>Nên sử dụng kết hợp chữ hoa, chữ thường và số</li>
                        <li>Không chia sẻ mật khẩu với người khác</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';

const form = reactive({
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
});

const errors = ref({});
const successMessage = ref('');
const processing = ref(false);

const submitForm = () => {
    errors.value = {};
    successMessage.value = '';
    processing.value = true;

    router.post('/admin/change-password', form, {
        preserveScroll: true,
        onSuccess: (page) => {
            // Reset form
            form.current_password = '';
            form.new_password = '';
            form.new_password_confirmation = '';
            
            // Show success message
            if (page.props.flash?.success) {
                successMessage.value = page.props.flash.success;
                setTimeout(() => {
                    successMessage.value = '';
                }, 5000);
            }
            processing.value = false;
        },
        onError: (pageErrors) => {
            errors.value = pageErrors;
            processing.value = false;
        },
    });
};
</script>
