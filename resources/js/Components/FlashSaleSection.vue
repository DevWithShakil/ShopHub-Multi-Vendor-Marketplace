<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Link } from "@inertiajs/vue3";
import { BoltIcon, ArrowRightIcon, ClockIcon } from "@heroicons/vue/24/solid";
import FlashSaleCard from "@/Components/FlashSaleCard.vue";

const props = defineProps({
    saleData: Object,
});

// --- Timer Logic ---
const timeLeft = ref({ days: 0, hours: 0, minutes: 0, seconds: 0 });
let timerInterval = null;

const updateTimer = () => {
    if (!props.saleData?.end_time) return;
    const endTime = new Date(props.saleData.end_time).getTime();
    const now = new Date().getTime();
    const difference = endTime - now;

    if (difference > 0) {
        timeLeft.value = {
            days: Math.floor(difference / (1000 * 60 * 60 * 24)),
            hours: Math.floor((difference / (1000 * 60 * 60)) % 24),
            minutes: Math.floor((difference / 1000 / 60) % 60),
            seconds: Math.floor((difference / 1000) % 60),
        };
    } else {
        timeLeft.value = { days: 0, hours: 0, minutes: 0, seconds: 0 };
        clearInterval(timerInterval);
    }
};

onMounted(() => {
    updateTimer();
    timerInterval = setInterval(updateTimer, 1000);
});

onUnmounted(() => {
    if (timerInterval) clearInterval(timerInterval);
});
</script>

<template>
    <section
        v-if="saleData && saleData.products.length > 0"
        class="py-16 relative overflow-hidden bg-[#0B0F19]"
    >
        <div
            class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5 pointer-events-none"
        ></div>
        <div
            class="absolute top-0 left-0 w-[600px] h-[600px] bg-orange-600/10 rounded-full blur-[150px] pointer-events-none"
        ></div>
        <div
            class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-red-600/10 rounded-full blur-[150px] pointer-events-none"
        ></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div
                class="flex flex-col-reverse md:flex-row items-center justify-between gap-8 mb-12 bg-[#151925]/80 border border-white/5 rounded-[2.5rem] p-8 backdrop-blur-xl shadow-2xl relative overflow-hidden"
            >
                <div
                    class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-orange-500 via-red-600 to-transparent"
                ></div>

                <div
                    class="flex flex-col items-center md:items-start gap-4 w-full md:w-auto"
                >
                    <div
                        class="flex items-center gap-2 text-orange-400 font-bold uppercase tracking-[0.2em] text-xs mb-2"
                    >
                        <ClockIcon class="w-4 h-4 animate-pulse" /> Ending In
                    </div>

                    <div class="flex gap-3 md:gap-4 text-center">
                        <div class="timer-box group">
                            <span class="timer-num">{{
                                String(timeLeft.days).padStart(2, "0")
                            }}</span>
                            <span class="timer-label">Days</span>
                            <div class="timer-glow"></div>
                        </div>

                        <span class="timer-sep">:</span>

                        <div class="timer-box group">
                            <span class="timer-num">{{
                                String(timeLeft.hours).padStart(2, "0")
                            }}</span>
                            <span class="timer-label">Hours</span>
                            <div class="timer-glow"></div>
                        </div>

                        <span class="timer-sep">:</span>

                        <div class="timer-box group">
                            <span class="timer-num">{{
                                String(timeLeft.minutes).padStart(2, "0")
                            }}</span>
                            <span class="timer-label">Mins</span>
                            <div class="timer-glow"></div>
                        </div>

                        <span class="timer-sep text-red-500 animate-pulse"
                            >:</span
                        >

                        <div class="timer-box group !border-red-500/50">
                            <span
                                class="timer-num text-red-500 group-hover:text-red-400"
                                >{{
                                    String(timeLeft.seconds).padStart(2, "0")
                                }}</span
                            >
                            <span class="timer-label text-red-400/80"
                                >Secs</span
                            >
                            <div class="timer-glow !bg-red-500/20"></div>
                        </div>
                    </div>
                </div>

                <div
                    class="flex flex-col items-center md:items-end text-center md:text-right w-full md:w-auto"
                >
                    <div class="flex items-center gap-3 mb-2">
                        <span
                            class="w-12 h-1 bg-orange-500 rounded-full hidden md:block"
                        ></span>
                        <h2
                            class="text-4xl md:text-6xl font-black text-white uppercase italic tracking-tighter"
                        >
                            Flash
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-600"
                                >Sale</span
                            >
                        </h2>
                    </div>
                    <p
                        class="text-gray-400 text-sm md:text-base font-medium mb-6 max-w-md"
                    >
                        Grab these exclusive deals before the timer hits zero!
                        Limited stock available.
                    </p>

                    <Link
                        :href="route('flash-sale.show', saleData.id)"
                        class="group flex items-center gap-3 px-8 py-3 bg-white hover:bg-orange-50 text-gray-900 rounded-full font-bold text-sm transition-all shadow-lg hover:shadow-orange-500/30 hover:scale-105 active:scale-95"
                    >
                        View All Products
                        <div
                            class="bg-orange-100 p-1 rounded-full group-hover:bg-orange-200 transition-colors"
                        >
                            <ArrowRightIcon class="w-4 h-4 text-orange-600" />
                        </div>
                    </Link>
                </div>
            </div>

            <div
                class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6"
            >
                <FlashSaleCard
                    v-for="product in saleData.products.slice(0, 10)"
                    :key="product.id"
                    :product="product"
                />
            </div>

            <div class="mt-10 md:hidden flex justify-center">
                <Link
                    :href="route('flash-sale.show', saleData.id)"
                    class="w-full text-center px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-bold backdrop-blur-md active:scale-95 transition-all"
                >
                    Browse All Deals
                </Link>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* Big Premium Timer Box */
.timer-box {
    @apply relative flex flex-col items-center justify-center w-16 h-20 md:w-24 md:h-28 bg-[#0B0F19] border border-white/10 rounded-2xl shadow-xl overflow-hidden transition-all duration-300 hover:-translate-y-1;
}

.timer-num {
    @apply text-3xl md:text-5xl font-black text-white leading-none font-mono z-10;
}

.timer-label {
    @apply text-[10px] md:text-xs font-bold text-gray-500 uppercase mt-1 md:mt-2 tracking-widest z-10;
}

.timer-sep {
    @apply text-3xl md:text-6xl font-black text-white/20 mt-2 md:mt-4;
}

/* Internal Glow Effect for Timer */
.timer-glow {
    @apply absolute inset-0 bg-orange-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500;
}
</style>
