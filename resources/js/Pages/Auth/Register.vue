<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import {
    ShoppingBagIcon,
    UserIcon,
    EnvelopeIcon,
    LockClosedIcon,
    UserPlusIcon,
    ArrowRightIcon,
    CheckCircleIcon,
    XCircleIcon, // Error Icon
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { ref } from "vue";
import axios from "axios";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

// States
const toastMessage = ref(null);
const toastType = ref("success"); // 'success' or 'error'
const isSubmitting = ref(false);

// Toast Helper
const showToast = (message, type = "success") => {
    toastMessage.value = message;
    toastType.value = type;
    setTimeout(() => {
        toastMessage.value = null;
    }, 4000);
};

const submit = async () => {
    isSubmitting.value = true;
    form.clearErrors(); // আগের এররগুলো মুছে ফেলা

    try {
        await axios.post(route("register"), form.data());

        // ✅ Success Logic
        showToast("Account created successfully! Redirecting...", "success");

        setTimeout(() => {
            window.location.href = "/";
        }, 2000);
    } catch (error) {
        isSubmitting.value = false;

        // ❌ Error Logic
        if (error.response && error.response.data.errors) {
            // ইনপুট ফিল্ডের নিচে এরর সেট করা
            Object.keys(error.response.data.errors).forEach((key) => {
                form.setError(key, error.response.data.errors[key][0]);
            });
            // টোস্ট মেসেজ দেখানো
            showToast("Registration failed. Please check the errors.", "error");
        } else {
            showToast("Something went wrong. Please try again.", "error");
        }
    }
};
</script>

<template>
    <Head title="Create Account" />

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
            class="fixed bottom-6 right-6 z-[9999] max-w-sm w-full border shadow-[0_10px_40px_-10px_rgba(0,0,0,0.5)] rounded-2xl p-4 flex items-center gap-4 backdrop-blur-xl border-l-4 animate-pulse-slow"
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

    <div
        class="min-h-screen flex bg-white selection:bg-indigo-500 selection:text-white"
    >
        <div
            class="hidden lg:flex w-1/2 bg-gray-900 relative overflow-hidden group"
        >
            <div class="absolute inset-0">
                <img
                    src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=2070&auto=format&fit=crop"
                    alt="Register Background"
                    class="w-full h-full object-cover opacity-30 transition-transform duration-[10s] group-hover:scale-110"
                />
                <div
                    class="absolute inset-0 bg-gradient-to-br from-indigo-900/90 via-purple-900/80 to-black/60"
                ></div>
            </div>

            <div
                class="relative z-10 w-full flex flex-col justify-between p-16 text-white"
            >
                <div>
                    <Link
                        href="/"
                        class="flex items-center gap-3 mb-8 w-fit hover:opacity-80 transition-opacity"
                    >
                        <div
                            class="bg-white/10 p-2 rounded-lg backdrop-blur-md border border-white/20"
                        >
                            <ShoppingBagIcon class="w-6 h-6 text-white" />
                        </div>
                        <span class="text-2xl font-bold tracking-wide uppercase"
                            >ShopHub</span
                        >
                    </Link>
                    <h1
                        class="text-5xl font-bold leading-tight mb-6 tracking-tight"
                    >
                        Join the Future of <br />
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-200 to-purple-200"
                            >Online Shopping.</span
                        >
                    </h1>
                    <p class="text-gray-300 text-lg font-light max-w-md">
                        Create an account to unlock exclusive deals, track your
                        orders, and experience seamless checkout.
                    </p>
                </div>

                <div
                    class="flex justify-between items-end border-t border-white/10 pt-8"
                >
                    <p class="text-sm text-gray-400">
                        &copy; 2025 ShopHub Inc.
                    </p>
                    <div class="flex gap-3">
                        <div
                            class="text-xs font-medium px-3 py-1 rounded-full bg-white/10 border border-white/10"
                        >
                            Fast
                        </div>
                        <div
                            class="text-xs font-medium px-3 py-1 rounded-full bg-white/10 border border-white/10"
                        >
                            Secure
                        </div>
                        <div
                            class="text-xs font-medium px-3 py-1 rounded-full bg-white/10 border border-white/10"
                        >
                            Premium
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="w-full lg:w-1/2 flex items-center justify-center p-6 bg-white"
        >
            <div class="w-full max-w-[440px]">
                <div class="lg:hidden flex items-center gap-2 mb-8">
                    <div class="bg-indigo-600 text-white p-2 rounded-lg">
                        <ShoppingBagIcon class="w-6 h-6" />
                    </div>
                    <span class="text-xl font-bold text-gray-900">ShopHub</span>
                </div>

                <div class="mb-8">
                    <div
                        class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-indigo-100"
                    >
                        <UserPlusIcon class="w-8 h-8 text-indigo-600" />
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                        Create Account
                    </h2>
                    <p class="text-gray-500 mt-2 text-sm">
                        Join us today! It takes less than a minute.
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div class="group">
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                            >Full Name</label
                        >
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                            >
                                <UserIcon
                                    class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-600 transition-colors"
                                />
                            </div>
                            <input
                                v-model="form.name"
                                type="text"
                                required
                                autofocus
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 outline-none bg-gray-50/50 focus:bg-white"
                                placeholder="John Doe"
                            />
                        </div>
                        <p
                            v-if="form.errors.name"
                            class="text-red-500 text-xs mt-1 font-medium"
                        >
                            {{ form.errors.name }}
                        </p>
                    </div>

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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="group">
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                                >Password</label
                            >
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
                                    placeholder="••••••••"
                                />
                            </div>
                        </div>

                        <div class="group">
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                                >Confirm</label
                            >
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                                >
                                    <LockClosedIcon
                                        class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-600 transition-colors"
                                    />
                                </div>
                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    required
                                    class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 outline-none bg-gray-50/50 focus:bg-white"
                                    placeholder="••••••••"
                                />
                            </div>
                        </div>
                    </div>
                    <p
                        v-if="form.errors.password"
                        class="text-red-500 text-xs mt-1 font-medium"
                    >
                        {{ form.errors.password }}
                    </p>

                    <div
                        v-if="
                            $page.props.jetstream
                                ?.hasTermsAndPrivacyPolicyFeature
                        "
                        class="mt-4"
                    >
                        <label class="flex items-start">
                            <div class="flex items-center h-5">
                                <input
                                    type="checkbox"
                                    name="terms"
                                    v-model="form.terms"
                                    required
                                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-indigo-300"
                                />
                            </div>
                            <div class="ml-3 text-sm">
                                <label
                                    for="terms"
                                    class="font-medium text-gray-900"
                                >
                                    I agree to the
                                    <a
                                        target="_blank"
                                        :href="route('terms.show')"
                                        class="text-indigo-600 hover:underline"
                                        >Terms of Service</a
                                    >
                                    and
                                    <a
                                        target="_blank"
                                        :href="route('policy.show')"
                                        class="text-indigo-600 hover:underline"
                                        >Privacy Policy</a
                                    >
                                </label>
                            </div>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button
                            :disabled="isSubmitting"
                            class="w-full py-3.5 px-4 bg-gray-900 hover:bg-black text-white font-bold rounded-xl shadow-lg shadow-gray-200 transform transition-all duration-200 active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed flex justify-center items-center gap-2 group"
                        >
                            <span v-if="!isSubmitting">Create Account</span>
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
                    </div>
                </form>

                <p class="mt-8 text-center text-sm text-gray-500">
                    Already have an account?
                    <Link
                        :href="route('login')"
                        class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors"
                        >Log in</Link
                    >
                </p>
            </div>
        </div>
    </div>
</template>
