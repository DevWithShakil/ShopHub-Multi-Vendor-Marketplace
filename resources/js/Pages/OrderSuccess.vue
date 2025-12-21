<script setup>
import { Head, Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { CheckCircleIcon } from "@heroicons/vue/24/solid";

defineProps({
    invoice_no: String,
});
</script>

<template>
    <Head title="Order Confirmed" />

    <MainLayout>
        <div
            class="container mx-auto px-4 py-20 flex flex-col items-center justify-center text-center"
        >
            <div
                class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-6 animate-bounce"
            >
                <CheckCircleIcon class="w-16 h-16 text-green-600" />
            </div>

            <h1 class="text-4xl font-bold text-gray-800 mb-2">Thank You!</h1>
            <p class="text-xl text-gray-600 mb-8">
                Your order has been placed successfully.
            </p>

            <div
                class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 max-w-md w-full mb-8"
            >
                <p class="text-gray-500 text-sm">Order Number</p>
                <p
                    class="text-2xl font-mono font-bold text-indigo-600 tracking-wider"
                >
                    {{ invoice_no }}
                </p>
            </div>

            <div class="flex gap-4">
                <Link
                    href="/"
                    class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition"
                >
                    Continue Shopping
                </Link>
                <Link
                    href="/dashboard"
                    class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition shadow-lg"
                >
                    Track Order
                </Link>
            </div>
        </div>
    </MainLayout>
</template>
