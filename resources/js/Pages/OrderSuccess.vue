<script setup>
import { Head, Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {
    CheckBadgeIcon,
    ShoppingBagIcon,
    ArrowRightIcon,
    TruckIcon,
    MapPinIcon,
    HomeIcon,
} from "@heroicons/vue/24/solid";
import { ClipboardDocumentCheckIcon } from "@heroicons/vue/24/outline";
import { ref, onMounted } from "vue"; // ✅ onMounted ইমপোর্ট করা হলো
import { useCartStore } from "@/Stores/cartStore"; // ✅ কার্ট স্টোর ইমপোর্ট

const props = defineProps({
    invoice_no: String,
});

const cartStore = useCartStore(); // ✅ স্টোর ইনিশিলাইজ
const copied = ref(false);

// ✅ পেজ লোড হওয়ার সাথে সাথে কার্ট ক্লিয়ার হয়ে যাবে
onMounted(() => {
    cartStore.clearCart();
});

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
            class="min-h-screen bg-[#0B0F19] flex items-center justify-center py-12 px-4 relative overflow-hidden font-sans text-gray-200"
        >
            <div
                class="absolute top-0 left-0 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[150px] pointer-events-none animate-pulse"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[150px] pointer-events-none animate-pulse"
            ></div>

            <div class="absolute inset-0 pointer-events-none">
                <div
                    class="absolute top-20 left-20 w-3 h-3 bg-indigo-500 rounded-full animate-ping"
                ></div>
                <div
                    class="absolute top-40 right-40 w-2 h-2 bg-purple-500 rounded-full animate-bounce"
                ></div>
                <div
                    class="absolute bottom-20 left-1/3 w-4 h-4 bg-green-500 rounded-full opacity-50 animate-pulse"
                ></div>
            </div>

            <div
                class="max-w-2xl w-full bg-white/5 backdrop-blur-md rounded-3xl shadow-2xl border border-white/10 relative z-10 overflow-hidden animate-fade-in-up"
            >
                <div
                    class="bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 p-1"
                ></div>

                <div class="p-8 md:p-12 text-center">
                    <div class="mb-8 relative inline-block">
                        <div
                            class="w-28 h-28 bg-green-500/20 rounded-full flex items-center justify-center mx-auto animate-scale-up ring-4 ring-green-500/10 shadow-lg shadow-green-900/20"
                        >
                            <CheckBadgeIcon
                                class="w-20 h-20 text-green-400 drop-shadow-lg"
                            />
                        </div>
                        <div
                            class="absolute -bottom-2 -right-2 bg-[#0B0F19] p-3 rounded-full border border-white/10 shadow-lg"
                        >
                            <ShoppingBagIcon class="w-6 h-6 text-indigo-400" />
                        </div>
                    </div>

                    <h1
                        class="text-4xl md:text-5xl font-black text-white mb-4 tracking-tight"
                    >
                        Thank You!
                    </h1>
                    <p
                        class="text-lg text-gray-400 mb-10 max-w-md mx-auto leading-relaxed"
                    >
                        Your order has been placed successfully. We are
                        preparing it for shipment.
                    </p>

                    <div
                        class="bg-white/5 border border-white/10 rounded-2xl p-6 mb-12 max-w-sm mx-auto relative group hover:bg-white/10 transition-colors"
                    >
                        <p
                            class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2"
                        >
                            Order Number
                        </p>
                        <div class="flex items-center justify-center gap-3">
                            <span
                                class="text-3xl font-mono font-bold text-indigo-400 tracking-wider"
                            >
                                #{{ invoice_no }}
                            </span>
                            <button
                                @click="copyOrderNo"
                                class="text-gray-500 hover:text-white transition p-2 rounded-lg hover:bg-white/10 active:scale-95"
                                title="Copy Order ID"
                            >
                                <ClipboardDocumentCheckIcon
                                    v-if="!copied"
                                    class="w-6 h-6"
                                />
                                <span
                                    v-else
                                    class="text-xs font-bold text-green-400"
                                    >Copied!</span
                                >
                            </button>
                        </div>

                        <div
                            class="absolute -left-3 top-1/2 w-6 h-6 bg-[#0B0F19] rounded-full border-r border-white/10"
                        ></div>
                        <div
                            class="absolute -right-3 top-1/2 w-6 h-6 bg-[#0B0F19] rounded-full border-l border-white/10"
                        ></div>
                    </div>

                    <div
                        class="flex justify-between items-center max-w-md mx-auto mb-12 text-sm relative"
                    >
                        <div
                            class="absolute top-4 left-0 w-full h-1 bg-white/10 -z-10 rounded-full"
                        ></div>
                        <div
                            class="absolute top-4 left-0 w-1/3 h-1 bg-gradient-to-r from-green-500 to-emerald-400 -z-10 rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(16,185,129,0.5)]"
                        ></div>

                        <div class="flex flex-col items-center gap-2">
                            <div
                                class="w-9 h-9 bg-green-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-green-500/30 ring-4 ring-[#0B0F19]"
                            >
                                <CheckBadgeIcon class="w-5 h-5" />
                            </div>
                            <span class="font-bold text-white">Placed</span>
                        </div>

                        <div
                            class="flex flex-col items-center gap-2 opacity-50"
                        >
                            <div
                                class="w-9 h-9 bg-[#0B0F19] border-2 border-white/20 text-gray-400 rounded-full flex items-center justify-center ring-4 ring-[#0B0F19]"
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
                                class="w-9 h-9 bg-[#0B0F19] border-2 border-white/20 text-gray-400 rounded-full flex items-center justify-center ring-4 ring-[#0B0F19]"
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
                                class="w-9 h-9 bg-[#0B0F19] border-2 border-white/20 text-gray-400 rounded-full flex items-center justify-center ring-4 ring-[#0B0F19]"
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
                            :href="route('track.order')"
                            :data="{ invoice_no: invoice_no }"
                            method="post"
                            as="button"
                            class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition-transform active:scale-95 group"
                        >
                            Track Order
                            <ArrowRightIcon
                                class="w-5 h-5 group-hover:translate-x-1 transition-transform"
                            />
                        </Link>
                        <Link
                            href="/"
                            class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white/5 border border-white/10 text-gray-300 hover:bg-white/10 hover:text-white rounded-xl font-bold transition-all active:scale-95"
                        >
                            <HomeIcon class="w-5 h-5" /> Continue Shopping
                        </Link>
                    </div>
                </div>

                <div
                    class="bg-white/5 p-4 text-center border-t border-white/10 backdrop-blur-sm"
                >
                    <p class="text-sm text-gray-500">
                        Need help?
                        <a
                            href="#"
                            class="text-indigo-400 font-bold hover:text-indigo-300 hover:underline transition"
                            >Contact Support</a
                        >
                    </p>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

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
