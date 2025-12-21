<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import UserDashboardLayout from "@/Layouts/UserDashboardLayout.vue";
import { UserCircleIcon, KeyIcon } from "@heroicons/vue/24/outline";

const user = usePage().props.auth.user;

const formInfo = useForm({
    name: user.name,
    email: user.email,
});

const formPassword = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const updateInfo = () => {
    formInfo.post(route("profile.update.info"), {
        preserveScroll: true,
        onSuccess: () => {
            // Toast notification logic here if needed
        },
    });
};

const updatePassword = () => {
    formPassword.post(route("profile.update.password"), {
        preserveScroll: true,
        onSuccess: () => formPassword.reset(),
    });
};
</script>

<template>
    <Head title="Profile Settings" />

    <UserDashboardLayout>
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-8">
                Account Settings
            </h1>

            <div class="space-y-6">
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100"
                >
                    <div
                        class="flex items-center gap-4 mb-6 border-b border-gray-50 pb-4"
                    >
                        <div
                            class="p-3 bg-indigo-50 text-indigo-600 rounded-xl"
                        >
                            <UserCircleIcon class="w-6 h-6" />
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">
                                Personal Details
                            </h2>
                            <p class="text-sm text-gray-500">
                                Update your name and email address.
                            </p>
                        </div>
                    </div>

                    <form
                        @submit.prevent="updateInfo"
                        class="grid grid-cols-1 md:grid-cols-2 gap-6"
                    >
                        <div class="col-span-1 md:col-span-2">
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                                >Full Name</label
                            >
                            <input
                                v-model="formInfo.name"
                                type="text"
                                class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-gray-50 focus:bg-white"
                                placeholder="John Doe"
                            />
                            <p
                                v-if="formInfo.errors.name"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ formInfo.errors.name }}
                            </p>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                                >Email Address</label
                            >
                            <input
                                v-model="formInfo.email"
                                type="email"
                                class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-gray-50 focus:bg-white"
                                placeholder="john@example.com"
                            />
                            <p
                                v-if="formInfo.errors.email"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ formInfo.errors.email }}
                            </p>
                        </div>

                        <div class="col-span-1 md:col-span-2 flex justify-end">
                            <button
                                :disabled="formInfo.processing"
                                type="submit"
                                class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed"
                            >
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <div
                    class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100"
                >
                    <div
                        class="flex items-center gap-4 mb-6 border-b border-gray-50 pb-4"
                    >
                        <div class="p-3 bg-gray-50 text-gray-600 rounded-xl">
                            <KeyIcon class="w-6 h-6" />
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">
                                Security
                            </h2>
                            <p class="text-sm text-gray-500">
                                Ensure your account is using a long, random
                                password.
                            </p>
                        </div>
                    </div>

                    <form @submit.prevent="updatePassword" class="space-y-5">
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                                >Current Password</label
                            >
                            <input
                                v-model="formPassword.current_password"
                                type="password"
                                class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 focus:bg-white transition-all"
                            />
                            <p
                                v-if="formPassword.errors.current_password"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ formPassword.errors.current_password }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                    >New Password</label
                                >
                                <input
                                    v-model="formPassword.password"
                                    type="password"
                                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 focus:bg-white transition-all"
                                />
                                <p
                                    v-if="formPassword.errors.password"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ formPassword.errors.password }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                    >Confirm New Password</label
                                >
                                <input
                                    v-model="formPassword.password_confirmation"
                                    type="password"
                                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 focus:bg-white transition-all"
                                />
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button
                                :disabled="formPassword.processing"
                                type="submit"
                                class="px-6 py-3 bg-gray-900 text-white font-semibold rounded-xl hover:bg-black shadow-lg shadow-gray-200 transition-all active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed"
                            >
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </UserDashboardLayout>
</template>
