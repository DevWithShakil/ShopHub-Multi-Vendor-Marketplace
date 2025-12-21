<script setup>
import { Head, Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {
    CheckBadgeIcon,
    ShoppingBagIcon,
    ArrowRightIcon,
    TruckIcon,
    MapPinIcon,
} from "@heroicons/vue/24/solid";
import { ClipboardDocumentCheckIcon } from "@heroicons/vue/24/outline";
import { ref } from "vue";

const props = defineProps({
    invoice_no: String,
});

const copied = ref(false);

const copyOrderNo = () => {
    navigator.clipboard.writeText(props.invoice_no);
    copied.value = true;
    setTimeout(() => (copied.value = false), 2000);
};
</script>

<template>
    <Head title="Order Confirmed" />

    <MainLayout>
        <div
            class="min-h-screen bg-[#F8F9FA] flex items-center justify-center py-12 px-4 relative overflow-hidden"
        >
            <div class="absolute inset-0 pointer-events-none">
                <div
                    class="absolute top-10 left-10 w-4 h-4 bg-red-400 rounded-full animate-ping"
                ></div>
                <div
                    class="absolute top-20 right-20 w-3 h-3 bg-blue-400 rounded-full animate-bounce"
                ></div>
                <div
                    class="absolute bottom-10 left-1/4 w-6 h-6 bg-yellow-400 rounded-full opacity-50 animate-pulse"
                ></div>
            </div>

            <div
                class="max-w-2xl w-full bg-white rounded-3xl shadow-xl shadow-indigo-50 border border-white relative z-10 overflow-hidden animate-fade-in-up"
            >
                <div
                    class="bg-gradient-to-r from-green-500 to-emerald-600 p-1"
                ></div>

                <div class="p-8 md:p-12 text-center">
                    <div class="mb-6 relative inline-block">
                        <div
                            class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mx-auto animate-scale-up"
                        >
                            <CheckBadgeIcon
                                class="w-16 h-16 text-green-500 drop-shadow-sm"
                            />
                        </div>
                        <div
                            class="absolute -bottom-2 -right-2 bg-white p-2 rounded-full shadow-md"
                        >
                            <ShoppingBagIcon class="w-6 h-6 text-indigo-600" />
                        </div>
                    </div>

                    <h1
                        class="text-4xl font-black text-gray-900 mb-2 tracking-tight"
                    >
                        Thank You!
                    </h1>
                    <p
                        class="text-lg text-gray-500 mb-8 max-w-md mx-auto leading-relaxed"
                    >
                        Your order has been placed successfully. We are
                        preparing it for shipment.
                    </p>

                    <div
                        class="bg-gray-50 border border-gray-200 rounded-2xl p-6 mb-10 max-w-sm mx-auto relative group"
                    >
                        <p
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1"
                        >
                            Order Number
                        </p>
                        <div class="flex items-center justify-center gap-3">
                            <span
                                class="text-3xl font-mono font-bold text-indigo-600 tracking-wider"
                            >
                                #{{ invoice_no }}
                            </span>
                            <button
                                @click="copyOrderNo"
                                class="text-gray-400 hover:text-gray-600 transition"
                                title="Copy"
                            >
                                <ClipboardDocumentCheckIcon
                                    v-if="!copied"
                                    class="w-5 h-5"
                                />
                                <span
                                    v-else
                                    class="text-xs font-bold text-green-600"
                                    >Copied!</span
                                >
                            </button>
                        </div>

                        <div
                            class="absolute -left-3 top-1/2 w-6 h-6 bg-white rounded-full border-r border-gray-200"
                        ></div>
                        <div
                            class="absolute -right-3 top-1/2 w-6 h-6 bg-white rounded-full border-l border-gray-200"
                        ></div>
                    </div>

                    <div
                        class="flex justify-between items-center max-w-md mx-auto mb-12 text-sm relative"
                    >
                        <div
                            class="absolute top-4 left-0 w-full h-1 bg-gray-100 -z-10"
                        ></div>
                        <div
                            class="absolute top-4 left-0 w-1/3 h-1 bg-green-500 -z-10 transition-all duration-1000"
                        ></div>

                        <div class="flex flex-col items-center gap-2">
                            <div
                                class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-green-200"
                            >
                                <CheckBadgeIcon class="w-5 h-5" />
                            </div>
                            <span class="font-bold text-gray-900">Placed</span>
                        </div>
                        <div
                            class="flex flex-col items-center gap-2 opacity-50"
                        >
                            <div
                                class="w-8 h-8 bg-white border-2 border-gray-300 text-gray-400 rounded-full flex items-center justify-center"
                            >
                                <ShoppingBagIcon class="w-4 h-4" />
                            </div>
                            <span class="font-medium text-gray-500"
                                >Processing</span
                            >
                        </div>
                        <div
                            class="flex flex-col items-center gap-2 opacity-50"
                        >
                            <div
                                class="w-8 h-8 bg-white border-2 border-gray-300 text-gray-400 rounded-full flex items-center justify-center"
                            >
                                <TruckIcon class="w-4 h-4" />
                            </div>
                            <span class="font-medium text-gray-500"
                                >Shipped</span
                            >
                        </div>
                        <div
                            class="flex flex-col items-center gap-2 opacity-50"
                        >
                            <div
                                class="w-8 h-8 bg-white border-2 border-gray-300 text-gray-400 rounded-full flex items-center justify-center"
                            >
                                <MapPinIcon class="w-4 h-4" />
                            </div>
                            <span class="font-medium text-gray-500"
                                >Delivered</span
                            >
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link
                            href="/dashboard/orders"
                            class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-gray-900 hover:bg-black text-white rounded-xl font-bold shadow-lg shadow-gray-300 transition-transform active:scale-95"
                        >
                            Track Order <ArrowRightIcon class="w-5 h-5" />
                        </Link>
                        <Link
                            href="/"
                            class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white border-2 border-gray-100 text-gray-700 hover:border-gray-300 hover:bg-gray-50 rounded-xl font-bold transition-all active:scale-95"
                        >
                            Continue Shopping
                        </Link>
                    </div>
                </div>

                <div
                    class="bg-gray-50 p-4 text-center border-t border-gray-100"
                >
                    <p class="text-sm text-gray-500">
                        Need help?
                        <a
                            href="#"
                            class="text-indigo-600 font-bold hover:underline"
                            >Contact Support</a
                        >
                    </p>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
/* Smooth Entry Animation */
.animate-fade-in-up {
    animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Icon Pop Animation */
.animate-scale-up {
    animation: scaleUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes scaleUp {
    from {
        transform: scale(0.5);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}
</style>
