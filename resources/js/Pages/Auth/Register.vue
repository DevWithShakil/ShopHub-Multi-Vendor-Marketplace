<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import {
    ShoppingBagIcon,
    UserIcon,
    EnvelopeIcon,
    LockClosedIcon,
    UserPlusIcon,
    ArrowRightIcon,
} from "@heroicons/vue/24/outline";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Create Account" />

    <div
        class="min-h-screen flex bg-white selection:bg-indigo-500 selection:text-white"
    >
        <div class="hidden lg:flex w-1/2 bg-gray-900 relative overflow-hidden">
            <div class="absolute inset-0">
                <img
                    src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=2070&auto=format&fit=crop"
                    alt="Register Background"
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
                            :disabled="form.processing"
                            class="w-full py-3.5 px-4 bg-gray-900 hover:bg-black text-white font-bold rounded-xl shadow-lg shadow-gray-200 transform transition-all duration-200 active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed flex justify-center items-center gap-2 group"
                        >
                            <span v-if="!form.processing">Create Account</span>
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
                    </div>
                </form>

                <p class="mt-8 text-center text-sm text-gray-500">
                    Already have an account?
                    <Link
                        :href="route('login')"
                        class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors"
                    >
                        Log in
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>
