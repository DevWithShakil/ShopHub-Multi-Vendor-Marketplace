<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import {
    ShoppingBagIcon,
    EnvelopeIcon,
    LockClosedIcon,
    CheckIcon,
    FingerPrintIcon,
    ArrowRightIcon,
    CheckCircleIcon,
    XCircleIcon,
    XMarkIcon,
    InformationCircleIcon, // New Icon for Status
} from "@heroicons/vue/24/outline";
import { ref, onMounted } from "vue";
import axios from "axios";

const props = defineProps({
    canResetPassword: Boolean,
    status: String, // Flash message from backend (e.g., "Logged out successfully")
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const toastMessage = ref(null);
const toastType = ref("success");
const isSubmitting = ref(false);

const showToast = (message, type = "success") => {
    toastMessage.value = message;
    toastType.value = type;
    setTimeout(() => {
        toastMessage.value = null;
    }, 4000);
};

// ✅ Handle Logout Message or Status Message
onMounted(() => {
    if (props.status) {
        showToast(props.status, "info");
    }

    // Check for query param 'logged_out' if backend sends it
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("logged_out")) {
        showToast("You have been logged out successfully.", "success");
    }
});

const submit = async () => {
    isSubmitting.value = true;
    form.clearErrors();

    try {
        await axios.post(route("login"), form.data());
        showToast("Login successful! Redirecting to Home...", "success");
        setTimeout(() => {
            window.location.href = "/";
        }, 1500);
    } catch (error) {
        isSubmitting.value = false;
        if (error.response && error.response.data.errors) {
            Object.keys(error.response.data.errors).forEach((key) => {
                form.setError(key, error.response.data.errors[key][0]);
            });
            showToast("Invalid credentials. Please try again.", "error");
        } else {
            showToast("Something went wrong. Please try again.", "error");
        }
    }
};
</script>

<template>
    <Head title="Log in" />

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
            :class="{
                'bg-[#1A1F2E] border-green-500/20 border-l-green-500':
                    toastType === 'success',
                'bg-[#1A1F2E] border-red-500/20 border-l-red-500':
                    toastType === 'error',
                'bg-[#1A1F2E] border-blue-500/20 border-l-blue-500':
                    toastType === 'info',
            }"
        >
            <div
                class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 shadow-inner ring-1"
                :class="{
                    'bg-green-500/10 ring-green-500/20':
                        toastType === 'success',
                    'bg-red-500/10 ring-red-500/20': toastType === 'error',
                    'bg-blue-500/10 ring-blue-500/20': toastType === 'info',
                }"
            >
                <CheckCircleIcon
                    v-if="toastType === 'success'"
                    class="w-6 h-6 text-green-400"
                />
                <XCircleIcon
                    v-else-if="toastType === 'error'"
                    class="w-6 h-6 text-red-400"
                />
                <InformationCircleIcon v-else class="w-6 h-6 text-blue-400" />
            </div>

            <div class="flex-1">
                <h4 class="font-bold text-sm tracking-wide text-white">
                    {{
                        toastType === "success"
                            ? "Success!"
                            : toastType === "error"
                            ? "Error!"
                            : "Info"
                    }}
                </h4>
                <p
                    class="text-xs mt-0.5 font-medium"
                    :class="{
                        'text-green-200/70': toastType === 'success',
                        'text-red-200/70': toastType === 'error',
                        'text-blue-200/70': toastType === 'info',
                    }"
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

    <div
        class="min-h-screen flex bg-white selection:bg-indigo-500 selection:text-white"
    >
        <div class="hidden lg:flex w-1/2 bg-gray-900 relative overflow-hidden">
            <div class="absolute inset-0">
                <img
                    src="https://images.unsplash.com/photo-1556742049-0cfed4f7a07d?q=80&w=2070&auto=format&fit=crop"
                    alt="Login Background"
                    class="w-full h-full object-cover opacity-30 transition-transform duration-[10s] hover:scale-110"
                />
                <div
                    class="absolute inset-0 bg-gradient-to-br from-indigo-900/90 via-purple-900/80 to-black/60"
                ></div>
            </div>

            <div
                class="relative z-10 w-full flex flex-col justify-between p-16 text-white"
            >
                <div>
                    <div class="flex items-center gap-3 mb-8">
                        <div
                            class="bg-white/10 p-2 rounded-lg backdrop-blur-md border border-white/20"
                        >
                            <ShoppingBagIcon class="w-6 h-6 text-white" />
                        </div>
                        <span class="text-2xl font-bold tracking-wide uppercase"
                            >ShopHub</span
                        >
                    </div>
                    <h1
                        class="text-5xl font-bold leading-tight mb-6 tracking-tight"
                    >
                        Secure Access to <br />
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-200 to-purple-200"
                            >Your Marketplace.</span
                        >
                    </h1>
                    <p class="text-gray-300 text-lg font-light max-w-md">
                        Manage orders, track sales, and discover premium
                        products with our secure dashboard.
                    </p>
                </div>
                <div
                    class="flex justify-between items-end border-t border-white/10 pt-8"
                >
                    <p class="text-sm text-gray-400">
                        &copy; 2025 ShopHub Inc.
                    </p>
                    <div class="flex gap-2">
                        <div class="w-2 h-2 rounded-full bg-white"></div>
                        <div class="w-2 h-2 rounded-full bg-white/30"></div>
                        <div class="w-2 h-2 rounded-full bg-white/30"></div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="w-full lg:w-1/2 flex items-center justify-center p-6 bg-white"
        >
            <div class="w-full max-w-[420px]">
                <div class="lg:hidden flex items-center gap-2 mb-8">
                    <div class="bg-indigo-600 text-white p-2 rounded-lg">
                        <ShoppingBagIcon class="w-6 h-6" />
                    </div>
                    <span class="text-xl font-bold text-gray-900">ShopHub</span>
                </div>

                <div class="mb-10">
                    <div
                        class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-indigo-100"
                    >
                        <FingerPrintIcon class="w-8 h-8 text-indigo-600" />
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                        Welcome Back
                    </h2>
                    <p class="text-gray-500 mt-2 text-sm">
                        Please enter your details to access your account.
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div class="group">
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                            >Email Address</label
                        >
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                            >
                                <EnvelopeIcon
                                    class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-600 transition-colors"
                                />
                            </div>
                            <input
                                v-model="form.email"
                                type="email"
                                required
                                autofocus
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 outline-none bg-gray-50/50 focus:bg-white"
                                placeholder="name@company.com"
                            />
                        </div>
                        <p
                            v-if="form.errors.email"
                            class="text-red-500 text-xs mt-1 font-medium"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div class="group">
                        <div class="flex items-center justify-between mb-2">
                            <label
                                class="block text-sm font-semibold text-gray-700"
                                >Password</label
                            >
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors"
                                >Forgot password?</Link
                            >
                        </div>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                            >
                                <LockClosedIcon
                                    class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-600 transition-colors"
                                />
                            </div>
                            <input
                                v-model="form.password"
                                type="password"
                                required
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 outline-none bg-gray-50/50 focus:bg-white"
                                placeholder="Enter your password"
                            />
                        </div>
                        <p
                            v-if="form.errors.password"
                            class="text-red-500 text-xs mt-1 font-medium"
                        >
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <div class="flex items-center">
                        <label
                            class="flex items-center cursor-pointer relative"
                        >
                            <input
                                type="checkbox"
                                v-model="form.remember"
                                class="sr-only peer"
                            />
                            <div
                                class="w-5 h-5 border-2 border-gray-300 rounded peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-all"
                            ></div>
                            <CheckIcon
                                class="w-3.5 h-3.5 text-white absolute top-1 left-1 opacity-0 peer-checked:opacity-100 transition-opacity"
                            />
                            <span
                                class="ml-2 text-sm text-gray-600 font-medium select-none"
                                >Remember me for 30 days</span
                            >
                        </label>
                    </div>

                    <button
                        :disabled="isSubmitting"
                        class="w-full py-3.5 px-4 bg-gray-900 hover:bg-black text-white font-bold rounded-xl shadow-lg shadow-gray-200 transform transition-all duration-200 active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed flex justify-center items-center gap-2 group"
                    >
                        <span v-if="!isSubmitting">Sign In</span>
                        <ArrowRightIcon
                            v-if="!isSubmitting"
                            class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                        />
                        <svg
                            v-else
                            class="animate-spin h-5 w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                    </button>
                </form>

                <p class="mt-8 text-center text-sm text-gray-500">
                    Don't have an account?
                    <Link
                        :href="route('register')"
                        class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors"
                        >Create account</Link
                    >
                </p>
            </div>
        </div>
    </div>
</template>
