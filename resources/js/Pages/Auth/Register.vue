<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    Mssv: '',
    Ho_va_Ten: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'sinh_vien',
    lop: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="Mssv" value="MSSV" />

                <TextInput
                    id="Mssv"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.Mssv"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Ví dụ: 2021CNTT001"
                />

                <InputError class="mt-2" :message="form.errors.Mssv" />
            </div>

            <div class="mt-4">
                <InputLabel for="Ho_va_Ten" value="Họ và Tên" />

                <TextInput
                    id="Ho_va_Ten"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.Ho_va_Ten"
                    required
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.Ho_va_Ten" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="email"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="role" value="Vai trò" />

                <select
                    id="role"
                    v-model="form.role"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    required
                >
                    <option value="sinh_vien">Sinh viên</option>
                    <option value="giang_vien">Giảng viên</option>
                    <option value="admin">Admin</option>
                </select>

                <InputError class="mt-2" :message="form.errors.role" />
            </div>

            <div class="mt-4">
                <InputLabel for="lop" value="Lớp (tùy chọn)" />

                <TextInput
                    id="lop"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.lop"
                    autocomplete="off"
                    placeholder="Ví dụ: CNTT01"
                />

                <InputError class="mt-2" :message="form.errors.lop" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Mật khẩu" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Xác nhận mật khẩu"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Đã có tài khoản?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Đăng ký
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
