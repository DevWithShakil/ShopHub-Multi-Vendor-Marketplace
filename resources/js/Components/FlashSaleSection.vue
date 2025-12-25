<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { BoltIcon, FireIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    saleData: Object,
});

// --- Timer Logic (As before) ---
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

// --- Helper Functions ---
const getLocalizedName = (name) => {
    try {
        const parsed =
            typeof name === "string" && name.startsWith("{")
                ? JSON.parse(name)
                : name;
        return typeof parsed === "object"
            ? parsed["en"] || Object.values(parsed)[0]
            : name;
    } catch (e) {
        return name;
    }
};

const getDiscountPercentage = (base, discount) => {
    if (!base || !discount) return 0;
    return Math.round(((base - discount) / base) * 100);
};

// ✅ Dynamic Sold Percentage Logic
const getSoldPercentage = (sold, limit) => {
    if (!limit || limit === 0) return 0;
    const percent = (sold / limit) * 100;
    return Math.min(Math.round(percent), 100); // 100% এর বেশি যেন না হয়
};

// ✅ Items Left Logic
const getItemsLeft = (sold, limit) => {
    return Math.max(limit - sold, 0); // নেগেটিভ যেন না হয়
};
</script>

<template>
    <section
        v-if="saleData && saleData.products.length > 0"
        class="py-10 relative overflow-hidden"
    >
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-gradient-to-r from-indigo-900/20 via-purple-900/10 to-indigo-900/20 blur-3xl pointer-events-none"
        ></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div
                class="flex flex-col md:flex-row items-center justify-between gap-6 mb-10 bg-white/5 border border-white/10 rounded-3xl p-6 md:p-8 backdrop-blur-xl shadow-2xl"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg animate-pulse"
                    >
                        <BoltIcon class="w-7 h-7 text-white" />
                    </div>
                    <div>
                        <h2
                            class="text-3xl font-black text-white italic uppercase tracking-tighter"
                        >
                            {{ saleData.title }}
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-500"
                                >Sale</span
                            >
                        </h2>
                        <p
                            class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1"
                        >
                            Limited Time Offer
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <p
                        class="text-sm font-bold text-gray-400 uppercase tracking-wider hidden md:block"
                    >
                        Ending In:
                    </p>
                    <div class="flex gap-2">
                        <div v-if="timeLeft.days > 0" class="timer-box">
                            <span class="timer-text">{{
                                String(timeLeft.days).padStart(2, "0")
                            }}</span
                            ><span class="timer-label">Days</span>
                        </div>
                        <span v-if="timeLeft.days > 0" class="timer-sep"
                            >:</span
                        >
                        <div class="timer-box">
                            <span class="timer-text">{{
                                String(timeLeft.hours).padStart(2, "0")
                            }}</span
                            ><span class="timer-label">Hrs</span>
                        </div>
                        <span class="timer-sep">:</span>
                        <div class="timer-box">
                            <span class="timer-text">{{
                                String(timeLeft.minutes).padStart(2, "0")
                            }}</span
                            ><span class="timer-label">Mins</span>
                        </div>
                        <span class="timer-sep">:</span>
                        <div class="timer-box border-red-500/30">
                            <span class="timer-text text-red-500">{{
                                String(timeLeft.seconds).padStart(2, "0")
                            }}</span
                            ><span class="timer-label text-red-400/60"
                                >Secs</span
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div
                    v-for="product in saleData.products.slice(0, 4)"
                    :key="product.id"
                    class="group relative bg-[#151925] border border-white/5 rounded-3xl p-4 hover:border-orange-500/30 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-orange-500/10 flex flex-col"
                >
                    <div class="absolute top-0 left-6 z-20">
                        <div
                            class="bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded-b-lg shadow-lg flex items-center gap-1"
                        >
                            <FireIcon class="w-3 h-3 animate-bounce" /> HOT
                        </div>
                    </div>

                    <div
                        class="relative h-60 rounded-2xl bg-white overflow-hidden mb-4 p-6 group-hover:shadow-inner transition-all"
                    >
                        <img
                            :src="
                                product.thumb_image
                                    ? `/storage/${product.thumb_image}`
                                    : 'https://placehold.co/400x400'
                            "
                            class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700 ease-out mix-blend-multiply"
                        />

                        <span
                            class="absolute top-3 right-3 bg-black/80 backdrop-blur text-white text-xs font-bold px-3 py-1 rounded-full border border-white/10"
                        >
                            -{{
                                getDiscountPercentage(
                                    product.base_price,
                                    product.pivot?.discount_price
                                )
                            }}% OFF
                        </span>
                    </div>

                    <div class="px-2 flex-1 flex flex-col">
                        <Link :href="route('product.details', product.slug)">
                            <h3
                                class="font-bold text-white text-lg leading-tight mb-2 group-hover:text-orange-400 transition line-clamp-1"
                            >
                                {{ getLocalizedName(product.name) }}
                            </h3>
                        </Link>

                        <div class="flex items-end gap-2 mb-4">
                            <span class="text-2xl font-black text-white"
                                >৳{{
                                    product.pivot?.discount_price ||
                                    product.base_price
                                }}</span
                            >
                            <span
                                class="text-sm text-gray-500 line-through font-medium mb-1"
                                >৳{{ product.base_price }}</span
                            >
                        </div>

                        <div class="mt-auto">
                            <div
                                class="flex justify-between text-[10px] font-bold text-gray-400 mb-1 uppercase tracking-wide"
                            >
                                <span class="text-indigo-400">
                                    {{
                                        getItemsLeft(
                                            product.pivot.sold,
                                            product.pivot.stock_limit
                                        )
                                    }}
                                    items left
                                </span>
                                <span class="text-orange-400">
                                    {{
                                        getSoldPercentage(
                                            product.pivot.sold,
                                            product.pivot.stock_limit
                                        )
                                    }}% Sold
                                </span>
                            </div>
                            <div
                                class="w-full h-2 bg-white/10 rounded-full overflow-hidden"
                            >
                                <div
                                    class="h-full bg-gradient-to-r from-orange-500 to-red-600 rounded-full transition-all duration-1000"
                                    :style="`width: ${getSoldPercentage(
                                        product.pivot.sold,
                                        product.pivot.stock_limit
                                    )}%`"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.timer-box {
    @apply flex flex-col items-center bg-[#0B0F19] border border-white/10 px-3 py-2 rounded-lg min-w-[60px];
}
.timer-text {
    @apply text-2xl font-black text-white font-mono;
}
.timer-label {
    @apply text-[10px] text-gray-500 font-bold uppercase;
}
.timer-sep {
    @apply text-2xl font-black text-white/20 mt-1;
}
</style>
