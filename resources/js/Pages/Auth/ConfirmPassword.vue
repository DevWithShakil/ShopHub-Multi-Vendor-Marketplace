<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import {
    ShoppingBagIcon,
    LockClosedIcon,
    ShieldExclamationIcon,
    CheckBadgeIcon,
    ArrowRightIcon,
} from "@heroicons/vue/24/outline";

const form = useForm({
    password: "",
});

const submit = () => {
    form.post(route("password.confirm"), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Secure Area" />

    <div
        class="min-h-screen flex bg-white selection:bg-indigo-500 selection:text-white"
    >
        <div class="hidden lg:flex w-1/2 bg-gray-900 relative overflow-hidden">
            <div class="absolute inset-0">
                <img
                    src="https://images.unsplash.com/photo-1614064641938-3e8529967160?q=80&w=2070&auto=format&fit=crop"
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
                        Security <br />
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-200 to-purple-200"
                            >Checkpoint.</span
                        >
                    </h1>
                    <p class="text-gray-300 text-lg font-light max-w-md">
                        We are just making sure it's really you before allowing
                        access to sensitive account settings.
                    </p>
                </div>

                <div
                    class="flex justify-between items-end border-t border-white/10 pt-8"
                >
                    <p class="text-sm text-gray-400">
                        &copy; 2025 ShopHub Inc.
                    </p>
                    <div
                        class="flex items-center gap-2 text-indigo-200 bg-indigo-500/10 px-3 py-1 rounded-full border border-indigo-400/20"
                    >
                        <LockClosedIcon class="w-4 h-4" />
                        <span class="text-xs font-bold uppercase tracking-wider"
                            >End-to-End Encrypted</span
                        >
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
                        class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-red-100"
                    >
                        <ShieldExclamationIcon class="w-8 h-8 text-red-500" />
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                        Confirm Password
                    </h2>
                    <p class="text-gray-500 mt-2 text-sm leading-relaxed">
                        This is a secure area of the application. Please confirm
                        your password before continuing.
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="group">
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                            >Current Password</label
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
                                autofocus
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

                    <button
                        :disabled="form.processing"
                        class="w-full py-3.5 px-4 bg-gray-900 hover:bg-black text-white font-bold rounded-xl shadow-lg shadow-gray-200 transform transition-all duration-200 active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed flex justify-center items-center gap-2 group"
                    >
                        <span v-if="!form.processing">Confirm Access</span>
                        <ArrowRightIcon
                            v-if="!form.processing"
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
            </div>
        </div>
    </div>
</template>
