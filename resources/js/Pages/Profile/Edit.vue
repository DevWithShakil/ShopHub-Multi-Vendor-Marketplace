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

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

// ✅ Toast State
const toastMessage = ref(null);
const toastType = ref("success"); // 'success' or 'error'

const showToast = (message, type = "success") => {
    toastMessage.value = message;
    toastType.value = type;
    setTimeout(() => {
        toastMessage.value = null;
    }, 4000);
};

// Form: Profile Info
const formInfo = useForm({
    name: user.name,
    email: user.email,
});

// Form: Password Update
const formPassword = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

// ✅ Update Profile Info
const updateInfo = () => {
    formInfo.patch(route("profile.update"), {
        preserveScroll: true,
        onSuccess: () => {
            showToast("Profile information updated successfully!", "success");
        },
        onError: () => {
            showToast(
                "Failed to update profile. Please check inputs.",
                "error"
            );
        },
    });
};

// ✅ Update Password
const updatePassword = () => {
    formPassword.put(route("password.update"), {
        preserveScroll: true,
        onSuccess: () => {
            formPassword.reset();
            showToast("Password updated successfully!", "success");
        },
        onError: () => {
            showToast(
                "Failed to update password. Check current password.",
                "error"
            );
        },
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
            class="fixed bottom-6 right-6 z-[100] max-w-sm w-full border shadow-[0_10px_40px_-10px_rgba(0,0,0,0.5)] rounded-2xl p-4 flex items-center gap-4 backdrop-blur-xl border-l-4 animate-pulse-slow"
            :class="
                toastType === 'success'
                    ? 'bg-[#1A1F2E] border-green-500/20 border-l-green-500'
                    : 'bg-[#1A1F2E] border-red-500/20 border-l-red-500'
            "
        >
            <div
                class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 shadow-inner ring-1"
                :class="
                    toastType === 'success'
                        ? 'bg-green-500/10 ring-green-500/20'
                        : 'bg-red-500/10 ring-red-500/20'
                "
            >
                <CheckCircleIcon
                    v-if="toastType === 'success'"
                    class="w-6 h-6 text-green-400"
                />
                <XCircleIcon v-else class="w-6 h-6 text-red-400" />
            </div>

            <div class="flex-1">
                <h4 class="font-bold text-sm tracking-wide text-white">
                    {{ toastType === "success" ? "Success!" : "Error!" }}
                </h4>
                <p
                    class="text-xs mt-0.5 font-medium"
                    :class="
                        toastType === 'success'
                            ? 'text-green-200/70'
                            : 'text-red-200/70'
                    "
                >
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
                                />
                                <p
                                    v-if="formInfo.errors.name"
                                    class="text-rose-500 text-xs mt-1 font-bold"
                                >
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
                                />
                                <p
                                    v-if="formInfo.errors.email"
                                    class="text-rose-500 text-xs mt-1 font-bold"
                                >
                                    {{ formInfo.errors.email }}
                                </p>

                                <div
                                    v-if="
                                        mustVerifyEmail &&
                                        user.email_verified_at === null
                                    "
                                    class="mt-2 text-sm text-gray-400"
                                >
                                    <p>
                                        {{
                                            __(
                                                "Your email address is unverified."
                                            )
                                        }}
                                    </p>
                                    <Link
                                        :href="route('verification.send')"
                                        method="post"
                                        as="button"
                                        class="underline text-indigo-400 hover:text-indigo-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        {{
                                            __(
                                                "Click here to re-send the verification email."
                                            )
                                        }}
                                    </Link>
                                    <div
                                        v-if="
                                            status === 'verification-link-sent'
                                        "
                                        class="mt-2 font-medium text-sm text-green-400"
                                    >
                                        {{
                                            __(
                                                "A new verification link has been sent to your email address."
                                            )
                                        }}
                                    </div>
                                </div>
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
                                />
                                <p
                                    v-if="formPassword.errors.current_password"
                                    class="text-rose-500 text-xs mt-1 font-bold"
                                >
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
                                    />
                                    <p
                                        v-if="formPassword.errors.password"
                                        class="text-rose-500 text-xs mt-1 font-bold"
                                    >
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
                                    />
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
