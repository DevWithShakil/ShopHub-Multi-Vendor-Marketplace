<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import {
    SparklesIcon,
    XMarkIcon,
    GiftIcon,
    TicketIcon,
    StarIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    promos: {
        type: Array,
        default: () => [],
    },
});

const showPopup = ref(false);

onMounted(() => {
    // সেশন চেক: আগে দেখা হয়েছে কিনা
    const hasSeen = sessionStorage.getItem("shophub_promo_seen");

    if (!hasSeen && props.promos.length > 0) {
        setTimeout(() => {
            showPopup.value = true;
            // একবার দেখালে আর দেখাবে না
            sessionStorage.setItem("shophub_promo_seen", "true");
        }, 1000);
    }
});

const closePopup = () => {
    showPopup.value = false;
};
</script>

<template>
    <transition name="modal">
        <div
            v-if="showPopup"
            class="fixed inset-0 z-[9999] flex items-center justify-center px-4"
        >
            <div
                class="absolute inset-0 bg-[#000000]/80 backdrop-blur-sm transition-opacity duration-500"
                @click="closePopup"
            ></div>

            <div
                class="relative w-full max-w-md transform transition-all duration-300 scale-100"
            >
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 rounded-3xl blur opacity-70 animate-pulse"
                ></div>

                <div
                    class="relative bg-[#12141c] rounded-2xl border border-white/10 shadow-2xl overflow-hidden"
                >
                    <div
                        class="relative h-32 bg-gradient-to-br from-purple-900 to-[#12141c] overflow-hidden flex items-center justify-center"
                    >
                        <div
                            class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-30 mix-blend-overlay"
                        ></div>
                        <div
                            class="w-20 h-20 bg-gradient-to-tr from-purple-500 to-pink-500 rounded-2xl rotate-12 flex items-center justify-center shadow-2xl shadow-purple-500/50 relative z-10 animate-float"
                        >
                            <GiftIcon
                                class="w-10 h-10 text-white drop-shadow-md"
                            />
                        </div>
                        <SparklesIcon
                            class="absolute top-6 left-12 w-6 h-6 text-yellow-400 animate-ping opacity-75"
                        />
                        <StarIcon
                            class="absolute bottom-8 right-12 w-5 h-5 text-blue-400 animate-bounce opacity-75"
                        />
                    </div>

                    <div class="p-8 text-center">
                        <h3
                            class="text-3xl font-black text-white mb-2 tracking-tight flex items-center justify-center gap-2"
                        >
                            You're Lucky!
                            <SparklesIcon
                                class="w-8 h-8 text-yellow-500 animate-spin-slow"
                            />
                        </h3>
                        <p class="text-gray-400 text-sm mb-8 leading-relaxed">
                            We found
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 font-bold text-lg"
                                >{{ promos.length }}</span
                            >
                            hidden discount codes for you. Don't let them
                            expire!
                        </p>

                        <div class="space-y-3">
                            <Link
                                :href="route('offers.index')"
                                class="w-full py-3.5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white font-bold rounded-xl shadow-lg shadow-purple-900/40 transition-all active:scale-95 flex items-center justify-center gap-2 group"
                            >
                                <TicketIcon
                                    class="w-5 h-5 group-hover:rotate-12 transition-transform"
                                />
                                Collect Vouchers
                            </Link>

                            <button
                                @click="closePopup"
                                class="w-full py-3 text-sm text-gray-500 font-medium hover:text-white transition-colors"
                            >
                                Maybe Later
                            </button>
                        </div>
                    </div>

                    <button
                        @click="closePopup"
                        class="absolute top-4 right-4 p-2 bg-black/20 hover:bg-white/10 rounded-full text-white/50 hover:text-white transition-all backdrop-blur-md"
                    >
                        <XMarkIcon class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>

<style scoped>
/* Animations from Offers.vue */
@keyframes float {
    0%,
    100% {
        transform: translateY(0) rotate(12deg);
    }
    50% {
        transform: translateY(-10px) rotate(12deg);
    }
}
.animate-float {
    animation: float 3s ease-in-out infinite;
}
.animate-spin-slow {
    animation: spin 3s linear infinite;
}
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
/* Transitions */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}
.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
.modal-enter-active .transform,
.modal-leave-active .transform {
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.modal-enter-from .transform,
.modal-leave-to .transform {
    transform: scale(0.9);
}
</style>
