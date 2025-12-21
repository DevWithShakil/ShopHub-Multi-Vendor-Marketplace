<script setup>
import { computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import {
    ShoppingBagIcon,
    EnvelopeOpenIcon,
    CheckCircleIcon,
    PaperAirplaneIcon,
    ArrowLeftOnRectangleIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route("verification.send"));
};

const verificationLinkSent = computed(
    () => props.status === "verification-link-sent"
);
</script>

<template>
    <Head title="Email Verification" />

    <div
        class="min-h-screen flex bg-white selection:bg-indigo-500 selection:text-white"
    >
        <div class="hidden lg:flex w-1/2 bg-gray-900 relative overflow-hidden">
            <div class="absolute inset-0">
                <img
                    src="https://images.unsplash.com/photo-1596526131083-e8c633c948d2?q=80&w=1974&auto=format&fit=crop"
                    alt="Email Verification Background"
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
                        Almost <br />
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-200 to-purple-200"
                            >There!</span
                        >
                    </h1>
                    <p class="text-gray-300 text-lg font-light max-w-md">
                        We just need to verify your email address to secure your
                        account and get you started.
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
                        <EnvelopeOpenIcon class="w-4 h-4" />
                        <span class="text-xs font-bold uppercase tracking-wider"
                            >Check Inbox</span
                        >
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
                        class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-green-100 mx-auto md:mx-0"
                    >
                        <EnvelopeOpenIcon
                            class="w-9 h-9 text-green-600 animate-pulse"
                        />
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                        Check Your Inbox
                    </h2>
                    <div
                        class="mt-3 text-gray-500 text-sm leading-relaxed space-y-2"
                    >
                        <p>
                            Thanks for signing up! Before getting started, could
                            you verify your email address by clicking on the
                            link we just emailed to you?
                        </p>
                        <p>
                            If you didn't receive the email, we will gladly send
                            you another.
                        </p>
                    </div>
                </div>

                <div
                    v-if="verificationLinkSent"
                    class="mb-6 font-medium text-sm text-green-700 bg-green-50 px-4 py-3 rounded-xl border border-green-200 flex items-start gap-3 animate-fade-in-up"
                >
                    <CheckCircleIcon
                        class="w-6 h-6 flex-shrink-0 text-green-600"
                    />
                    <span
                        >A new verification link has been sent to the email
                        address you provided during registration.</span
                    >
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="flex flex-col gap-4">
                        <button
                            :disabled="form.processing"
                            class="w-full py-3.5 px-4 bg-gray-900 hover:bg-black text-white font-bold rounded-xl shadow-lg shadow-gray-200 transform transition-all duration-200 active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed flex justify-center items-center gap-2 group"
                        >
                            <span v-if="!form.processing"
                                >Resend Verification Email</span
                            >
                            <PaperAirplaneIcon
                                v-if="!form.processing"
                                class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-0.5 transition-transform"
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

                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="inline-flex items-center justify-center gap-2 font-bold text-gray-500 hover:text-red-600 transition-colors group w-full py-2"
                        >
                            <ArrowLeftOnRectangleIcon
                                class="w-5 h-5 group-hover:-translate-x-1 transition-transform"
                            />
                            Log Out
                        </Link>
                    </div>
                </form>
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
