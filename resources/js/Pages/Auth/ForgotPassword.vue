<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import {
    ShoppingBagIcon,
    EnvelopeIcon,
    KeyIcon,
    ArrowLeftIcon,
    PaperAirplaneIcon,
} from "@heroicons/vue/24/outline";

defineProps({
    status: String,
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head title="Forgot Password" />

    <div
        class="min-h-screen flex bg-white selection:bg-indigo-500 selection:text-white"
    >
        <div class="hidden lg:flex w-1/2 bg-gray-900 relative overflow-hidden">
            <div class="absolute inset-0">
                <img
                    src="https://images.unsplash.com/photo-1555421689-491a97ff2040?q=80&w=2070&auto=format&fit=crop"
                    alt="Security Background"
                    class="w-full h-full object-cover opacity-30"
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
                        Account <br />
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-200 to-purple-200"
                            >Recovery.</span
                        >
                    </h1>
                    <p class="text-gray-300 text-lg font-light max-w-md">
                        Don't worry, it happens to the best of us. We'll help
                        you get back into your account in no time.
                    </p>
                </div>

                <div
                    class="flex justify-between items-end border-t border-white/10 pt-8"
                >
                    <p class="text-sm text-gray-400">
                        &copy; 2025 ShopHub Inc.
                    </p>
                    <div class="flex gap-2 text-sm font-medium text-indigo-200">
                        <span>Secure</span>
                        <span>•</span>
                        <span>Encrypted</span>
                        <span>•</span>
                        <span>Fast</span>
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

                <div class="mb-8">
                    <div
                        class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-indigo-100"
                    >
                        <KeyIcon class="w-8 h-8 text-indigo-600" />
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                        Forgot Password?
                    </h2>
                    <p class="text-gray-500 mt-2 text-sm">
                        No worries! Enter your email and we'll send you reset
                        instructions.
                    </p>
                </div>

                <div
                    v-if="status"
                    class="mb-6 font-medium text-sm text-green-600 bg-green-50 px-4 py-3 rounded-xl border border-green-100 flex items-center gap-2 animate-fade-in-up"
                >
                    <PaperAirplaneIcon class="w-5 h-5" />
                    <span>{{ status }}</span>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
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

                    <button
                        :disabled="form.processing"
                        class="w-full py-3.5 px-4 bg-gray-900 hover:bg-black text-white font-bold rounded-xl shadow-lg shadow-gray-200 transform transition-all duration-200 active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed flex justify-center items-center gap-2 group"
                    >
                        <span v-if="!form.processing">Send Reset Link</span>
                        <PaperAirplaneIcon
                            v-if="!form.processing"
                            class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"
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

                <p class="mt-8 text-center text-sm">
                    <Link
                        :href="route('login')"
                        class="inline-flex items-center gap-2 font-bold text-gray-500 hover:text-indigo-600 transition-colors group"
                    >
                        <ArrowLeftIcon
                            class="w-4 h-4 group-hover:-translate-x-1 transition-transform"
                        />
                        Back to Log In
                    </Link>
                </p>
            </div>
        </div>
    </div>
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
