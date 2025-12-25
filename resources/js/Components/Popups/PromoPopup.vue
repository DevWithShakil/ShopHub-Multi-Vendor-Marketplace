<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Link } from "@inertiajs/vue3";
import { XMarkIcon, GiftIcon, ArrowRightIcon } from "@heroicons/vue/24/solid";

const isVisible = ref(false);
let popupTimer = null;

onMounted(() => {
    // 🔥 Change: localStorage ব্যবহার করা হয়েছে (Long term memory)
    const hasSeenPopup = localStorage.getItem("seenPromoPopup");

    if (!hasSeenPopup) {
        // ৫ সেকেন্ড পর পপআপ আসবে
        popupTimer = setTimeout(() => {
            isVisible.value = true;
        }, 5000);
    }
});

onUnmounted(() => {
    if (popupTimer) clearTimeout(popupTimer);
});

const closePopup = () => {
    isVisible.value = false;
    // 🔥 Change: localStorage এ সেভ হচ্ছে
    localStorage.setItem("seenPromoPopup", "true");
};
</script>

<template>
    <transition
        enter-active-class="transition duration-500 cubic-bezier(0.16, 1, 0.3, 1)"
        enter-from-class="opacity-0 translate-y-10 scale-95"
        enter-to-class="opacity-100 translate-y-0 scale-100"
        leave-active-class="transition duration-300 ease-in"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div
            v-if="isVisible"
            class="fixed inset-0 z-[9999] flex items-center justify-center px-4"
        >
            <div
                @click="closePopup"
                class="absolute inset-0 bg-black/60 backdrop-blur-[2px] transition-opacity"
            ></div>

            <div
                class="relative w-full max-w-[380px] bg-[#1A1F2E] border border-yellow-500/30 rounded-3xl shadow-2xl overflow-hidden group"
            >
                <button
                    @click="closePopup"
                    class="absolute top-3 right-3 p-1.5 bg-white/5 hover:bg-red-500/20 text-gray-400 hover:text-red-500 rounded-full transition-colors z-20 cursor-pointer"
                >
                    <XMarkIcon class="w-4 h-4" />
                </button>

                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-32 bg-yellow-500/20 blur-[50px] pointer-events-none"
                ></div>

                <div class="p-6 pt-8 text-center relative z-10">
                    <div class="mb-4 relative inline-block">
                        <div
                            class="absolute inset-0 bg-yellow-500 blur-xl opacity-20 animate-pulse"
                        ></div>
                        <div
                            class="relative w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/30 rotate-3 group-hover:rotate-6 transition-transform duration-500 animate-float"
                        >
                            <GiftIcon class="w-8 h-8 text-white" />
                        </div>
                        <span class="absolute -top-1 -right-1 flex h-4 w-4">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"
                            ></span>
                            <span
                                class="relative inline-flex rounded-full h-4 w-4 bg-red-500 border-2 border-[#1A1F2E]"
                            ></span>
                        </span>
                    </div>

                    <h3
                        class="text-2xl font-black text-white leading-tight mb-1"
                    >
                        Wait! Before You Go
                    </h3>
                    <p
                        class="text-yellow-400 font-bold text-sm uppercase tracking-widest mb-3"
                    >
                        You have a gift inside!
                    </p>
                    <p class="text-gray-400 text-xs leading-relaxed px-4 mb-6">
                        Claim your
                        <span class="text-white font-bold">Free Shipping</span>
                        &
                        <span class="text-white font-bold"
                            >Extra Discounts</span
                        >
                        on your first order.
                    </p>

                    <Link
                        :href="route('offers.index')"
                        class="w-full bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-300 hover:to-orange-400 text-[#1A1F2E] font-black py-3.5 rounded-xl shadow-lg shadow-orange-500/20 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2"
                    >
                        <span>Reveal My Gift</span>
                        <ArrowRightIcon class="w-4 h-4" />
                    </Link>

                    <button
                        @click="closePopup"
                        class="mt-3 text-[10px] text-gray-500 hover:text-gray-300 transition decoration-gray-600 hover:underline cursor-pointer"
                    >
                        No thanks, I don't want discounts
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>

<style scoped>
/* 🎈 Gentle Float Animation */
@keyframes float {
    0%,
    100% {
        transform: translateY(0) rotate(3deg);
    }
    50% {
        transform: translateY(-6px) rotate(3deg);
    }
}
.animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>
