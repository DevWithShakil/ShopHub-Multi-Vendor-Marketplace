<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import UserDashboardLayout from "@/Layouts/UserDashboardLayout.vue";
import {
    UserCircleIcon,
    KeyIcon,
    CheckBadgeIcon,
    CheckCircleIcon,
    XCircleIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { ref } from "vue";

const user = usePage().props.auth.user;

// ✅ Toast State (Only for Success)
const toastMessage = ref(null);
const toastType = ref("success");

const showToast = (message) => {
    toastMessage.value = message;
    toastType.value = "success"; // Always success for toast
    setTimeout(() => {
        toastMessage.value = null;
    }, 4000);
};

// Form: Personal Info
const formInfo = useForm({
    name: user.name,
    email: user.email,
});

// Form: Password
const formPassword = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

// ✅ Update Info Logic (Strict Validation)
const updateInfo = () => {
    formInfo.clearErrors();

    let hasError = false;

    // 1. Check if Name is same
    if (formInfo.name === user.name) {
        formInfo.setError("name", "You have entered the same name as before.");
        hasError = true;
    }

    // 2. Check if Email is same
    // if (formInfo.email === user.email) {
    //     formInfo.setError(
    //         "email",
    //         "You have entered the same email as before."
    //     );
    //     hasError = false;
    // }

    if (hasError) {
        return;
    }

    // 3. Submit if valid changes exist
    formInfo.patch(route("profile.update"), {
        preserveScroll: true,
        onSuccess: () => {
            showToast("Profile updated successfully!");
        },
        onError: () => {},
    });
};

// ✅ Update Password Logic (Strict Validation)
const updatePassword = () => {
    formPassword.clearErrors();
    let hasError = false;

    // 1. Check if Current & New Password are SAME
    if (
        formPassword.current_password &&
        formPassword.password &&
        formPassword.current_password === formPassword.password
    ) {
        formPassword.setError(
            "password",
            "New password cannot be the same as current password."
        );
        hasError = true;
    }

    // 2. Check Confirm Password Match
    if (formPassword.password !== formPassword.password_confirmation) {
        formPassword.setError(
            "password_confirmation",
            "Password confirmation does not match."
        );
        hasError = true;
    }

    // 3. Check Minimum Length
    if (formPassword.password && formPassword.password.length < 8) {
        formPassword.setError(
            "password",
            "Password must be at least 8 characters."
        );
        hasError = true;
    }

    if (hasError) return;

    // 4. Submit
    formPassword.put(route("password.update"), {
        preserveScroll: true,
        onSuccess: () => {
            formPassword.reset();
            showToast("Password changed successfully!");
        },
        onError: (errors) => {},
    });
};
</script>

<template>
    <Head title="Profile Settings" />

    <transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-10 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div
            v-if="toastMessage"
            class="fixed bottom-10 right-6 z-[9999] max-w-sm w-full border border-green-500/20 bg-[#1A1F2E] border-l-4 border-l-green-500 shadow-2xl rounded-2xl p-4 flex items-center gap-4 backdrop-blur-xl animate-pulse-slow"
        >
            <div
                class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 shadow-inner ring-1 bg-green-500/10 ring-green-500/20"
            >
                <CheckCircleIcon class="w-6 h-6 text-green-400" />
            </div>

            <div class="flex-1">
                <h4 class="font-bold text-sm tracking-wide text-white">
                    Success!
                </h4>
                <p class="text-xs mt-0.5 font-medium text-green-200/70">
                    {{ toastMessage }}
                </p>
            </div>

            <button
                @click="toastMessage = null"
                class="p-2 hover:bg-white/5 rounded-full text-gray-400 hover:text-white transition"
            >
                <XMarkIcon class="w-4 h-4" />
            </button>
        </div>
    </transition>

    <UserDashboardLayout>
        <div
            class="min-h-screen bg-[#0B0F19] p-4 lg:p-8 font-sans text-gray-200 relative overflow-hidden"
        >
            <div
                class="absolute top-0 left-0 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[120px] pointer-events-none"
            ></div>

            <div class="relative z-10 max-w-4xl mx-auto">
                <div class="mb-10 animate-fade-in-up">
                    <h1
                        class="text-3xl font-black text-white tracking-tight mb-2"
                    >
                        {{ __("Account Settings") }}
                    </h1>
                    <p class="text-gray-400">
                        {{
                            __(
                                "Manage your personal information and security settings."
                            )
                        }}
                    </p>
                </div>

                <div class="space-y-8 animate-fade-in-up">
                    <div
                        class="bg-white/5 backdrop-blur-md p-6 md:p-8 rounded-3xl border border-white/10 shadow-2xl"
                    >
                        <div
                            class="flex items-center gap-4 mb-8 border-b border-white/10 pb-6"
                        >
                            <div
                                class="p-3 bg-gradient-to-br from-indigo-600 to-purple-600 text-white rounded-2xl shadow-lg shadow-indigo-500/30"
                            >
                                <UserCircleIcon class="w-6 h-6" />
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">
                                    {{ __("Personal Details") }}
                                </h2>
                                <p class="text-sm text-gray-400">
                                    {{
                                        __(
                                            "Update your name and email address."
                                        )
                                    }}
                                </p>
                            </div>
                        </div>

                        <form
                            @submit.prevent="updateInfo"
                            class="grid grid-cols-1 md:grid-cols-2 gap-6"
                        >
                            <div class="col-span-1 md:col-span-2">
                                <label
                                    class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wider"
                                    >{{ __("Full Name") }}</label
                                >
                                <input
                                    v-model="formInfo.name"
                                    type="text"
                                    class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none placeholder-gray-600"
                                    placeholder="John Doe"
                                    :class="{
                                        'border-rose-500 focus:ring-rose-500':
                                            formInfo.errors.name,
                                    }"
                                />
                                <p
                                    v-if="formInfo.errors.name"
                                    class="text-rose-500 text-xs mt-2 font-bold animate-pulse flex items-center gap-1"
                                >
                                    <XCircleIcon class="w-4 h-4" />
                                    {{ formInfo.errors.name }}
                                </p>
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <label
                                    class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wider"
                                    >{{ __("Email Address") }}</label
                                >
                                <input
                                    v-model="formInfo.email"
                                    type="email"
                                    class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none placeholder-gray-600"
                                    placeholder="john@example.com"
                                    :class="{
                                        'border-rose-500 focus:ring-rose-500':
                                            formInfo.errors.email,
                                    }"
                                />
                                <p
                                    v-if="formInfo.errors.email"
                                    class="text-rose-500 text-xs mt-2 font-bold animate-pulse flex items-center gap-1"
                                >
                                    <XCircleIcon class="w-4 h-4" />
                                    {{ formInfo.errors.email }}
                                </p>
                            </div>

                            <div
                                class="col-span-1 md:col-span-2 flex justify-end pt-2"
                            >
                                <button
                                    :disabled="formInfo.processing"
                                    type="submit"
                                    class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed flex items-center gap-2"
                                >
                                    <CheckBadgeIcon
                                        v-if="!formInfo.processing"
                                        class="w-5 h-5"
                                    />
                                    {{
                                        formInfo.processing
                                            ? "Saving..."
                                            : "Save Changes"
                                    }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <div
                        class="bg-white/5 backdrop-blur-md p-6 md:p-8 rounded-3xl border border-white/10 shadow-2xl"
                    >
                        <div
                            class="flex items-center gap-4 mb-8 border-b border-white/10 pb-6"
                        >
                            <div
                                class="p-3 bg-gradient-to-br from-rose-500 to-pink-600 text-white rounded-2xl shadow-lg shadow-rose-500/30"
                            >
                                <KeyIcon class="w-6 h-6" />
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">
                                    {{ __("Security") }}
                                </h2>
                                <p class="text-sm text-gray-400">
                                    {{
                                        __(
                                            "Ensure your account is using a long, random password."
                                        )
                                    }}
                                </p>
                            </div>
                        </div>

                        <form
                            @submit.prevent="updatePassword"
                            class="space-y-6"
                        >
                            <div>
                                <label
                                    class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wider"
                                    >{{ __("Current Password") }}</label
                                >
                                <input
                                    v-model="formPassword.current_password"
                                    type="password"
                                    class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition outline-none placeholder-gray-600"
                                    :class="{
                                        'border-rose-500 focus:ring-rose-500':
                                            formPassword.errors
                                                .current_password,
                                    }"
                                />
                                <p
                                    v-if="formPassword.errors.current_password"
                                    class="text-rose-500 text-xs mt-2 font-bold animate-pulse flex items-center gap-1"
                                >
                                    <XCircleIcon class="w-4 h-4" />
                                    {{ formPassword.errors.current_password }}
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wider"
                                        >{{ __("New Password") }}</label
                                    >
                                    <input
                                        v-model="formPassword.password"
                                        type="password"
                                        class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition outline-none placeholder-gray-600"
                                        :class="{
                                            'border-rose-500 focus:ring-rose-500':
                                                formPassword.errors.password,
                                        }"
                                    />
                                    <p
                                        v-if="formPassword.errors.password"
                                        class="text-rose-500 text-xs mt-2 font-bold animate-pulse flex items-center gap-1"
                                    >
                                        <XCircleIcon class="w-4 h-4" />
                                        {{ formPassword.errors.password }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wider"
                                        >{{ __("Confirm New Password") }}</label
                                    >
                                    <input
                                        v-model="
                                            formPassword.password_confirmation
                                        "
                                        type="password"
                                        class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition outline-none placeholder-gray-600"
                                        :class="{
                                            'border-rose-500 focus:ring-rose-500':
                                                formPassword.errors
                                                    .password_confirmation,
                                        }"
                                    />
                                    <p
                                        v-if="
                                            formPassword.errors
                                                .password_confirmation
                                        "
                                        class="text-rose-500 text-xs mt-2 font-bold animate-pulse flex items-center gap-1"
                                    >
                                        <XCircleIcon class="w-4 h-4" />
                                        {{
                                            formPassword.errors
                                                .password_confirmation
                                        }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button
                                    :disabled="formPassword.processing"
                                    type="submit"
                                    class="px-8 py-3 bg-white text-gray-900 font-bold rounded-xl hover:bg-gray-200 shadow-lg transition-all active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed flex items-center gap-2"
                                >
                                    <KeyIcon
                                        v-if="!formPassword.processing"
                                        class="w-5 h-5"
                                    />
                                    {{
                                        formPassword.processing
                                            ? "Updating..."
                                            : "Update Password"
                                    }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </UserDashboardLayout>
</template>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out forwards;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
