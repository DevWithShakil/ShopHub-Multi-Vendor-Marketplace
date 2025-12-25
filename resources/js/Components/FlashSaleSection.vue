<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import {
    BoltIcon,
    FireIcon,
    ArrowRightIcon,
    ShoppingCartIcon,
} from "@heroicons/vue/24/solid";

// Props (Home.vue থেকে প্রোডাক্টগুলো এখানে আসবে)
const props = defineProps({
    products: Array,
});

// ⏳ Countdown Timer Logic
const targetDate = new Date();
targetDate.setHours(targetDate.getHours() + 12); // উদাহরণ: ১২ ঘণ্টার টাইমার

const timeLeft = ref({ hours: 0, minutes: 0, seconds: 0 });
let timerInterval;

const updateTimer = () => {
    const now = new Date();
    const difference = targetDate - now;

    if (difference > 0) {
        timeLeft.value = {
            hours: Math.floor((difference / (1000 * 60 * 60)) % 24),
            minutes: Math.floor((difference / 1000 / 60) % 60),
            seconds: Math.floor((difference / 1000) % 60),
        };
    } else {
        // Reset or hide logic
    }
};

onMounted(() => {
    updateTimer();
    timerInterval = setInterval(updateTimer, 1000);
});

onUnmounted(() => clearInterval(timerInterval));

// 🎲 Random Sold Percentage Generator (ডেমো ডাটার জন্য)
const getSoldPercentage = (id) => {
    // প্রোডাক্ট আইডি দিয়ে একটি র‍্যান্ডম পার্সেন্টেজ জেনারেট করছি (ডেমো হিসেবে)
    return 40 + (id % 50);
};
</script>

<template>
    <section class="py-10 relative overflow-hidden">
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-gradient-to-r from-indigo-900/20 via-purple-900/10 to-indigo-900/20 blur-3xl pointer-events-none"
        ></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div
                class="flex flex-col md:flex-row items-center justify-between gap-6 mb-10 bg-white/5 border border-white/10 rounded-3xl p-6 md:p-8 backdrop-blur-xl shadow-2xl"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/30 animate-pulse"
                    >
                        <BoltIcon class="w-7 h-7 text-white" />
                    </div>
                    <div>
                        <h2
                            class="text-3xl font-black text-white italic uppercase tracking-tighter"
                        >
                            Flash
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
                        <div
                            class="flex flex-col items-center bg-[#0B0F19] border border-white/10 px-3 py-2 rounded-lg min-w-[60px]"
                        >
                            <span
                                class="text-2xl font-black text-white font-mono"
                                >{{
                                    String(timeLeft.hours).padStart(2, "0")
                                }}</span
                            >
                            <span
                                class="text-[10px] text-gray-500 font-bold uppercase"
                                >Hrs</span
                            >
                        </div>
                        <span class="text-2xl font-black text-white/20 mt-1"
                            >:</span
                        >
                        <div
                            class="flex flex-col items-center bg-[#0B0F19] border border-white/10 px-3 py-2 rounded-lg min-w-[60px]"
                        >
                            <span
                                class="text-2xl font-black text-white font-mono"
                                >{{
                                    String(timeLeft.minutes).padStart(2, "0")
                                }}</span
                            >
                            <span
                                class="text-[10px] text-gray-500 font-bold uppercase"
                                >Mins</span
                            >
                        </div>
                        <span class="text-2xl font-black text-white/20 mt-1"
                            >:</span
                        >
                        <div
                            class="flex flex-col items-center bg-[#0B0F19] border border-red-500/30 px-3 py-2 rounded-lg min-w-[60px] shadow-[0_0_15px_rgba(239,68,68,0.2)]"
                        >
                            <span
                                class="text-2xl font-black text-red-500 font-mono"
                                >{{
                                    String(timeLeft.seconds).padStart(2, "0")
                                }}</span
                            >
                            <span
                                class="text-[10px] text-red-400/60 font-bold uppercase"
                                >Secs</span
                            >
                        </div>
                    </div>
                </div>

                <Link
                    href="/products"
                    class="hidden md:flex items-center gap-2 text-sm font-bold text-white bg-white/10 hover:bg-white/20 px-6 py-3 rounded-full transition border border-white/10"
                >
                    View All Deals <ArrowRightIcon class="w-4 h-4" />
                </Link>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div
                    v-for="product in products.slice(0, 4)"
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
                            -25% OFF
                        </span>
                    </div>

                    <div class="px-2 flex-1 flex flex-col">
                        <Link :href="route('product.details', product.slug)">
                            <h3
                                class="font-bold text-white text-lg leading-tight mb-2 group-hover:text-orange-400 transition line-clamp-1"
                            >
                                {{
                                    typeof product.name === "object"
                                        ? product.name.en || product.name
                                        : product.name
                                }}
                            </h3>
                        </Link>

                        <div class="flex items-end gap-2 mb-4">
                            <span class="text-2xl font-black text-white"
                                >৳{{ product.base_price }}</span
                            >
                            <span
                                class="text-sm text-gray-500 line-through font-medium mb-1"
                                >৳{{
                                    parseInt(product.base_price) + 1200
                                }}</span
                            >
                        </div>

                        <div class="mt-auto">
                            <div
                                class="flex justify-between text-[10px] font-bold text-gray-400 mb-1 uppercase tracking-wide"
                            >
                                <span
                                    >Available:
                                    {{
                                        100 - getSoldPercentage(product.id)
                                    }}</span
                                >
                                <span class="text-orange-400"
                                    >Sold:
                                    {{ getSoldPercentage(product.id) }}%</span
                                >
                            </div>
                            <div
                                class="w-full h-2 bg-white/10 rounded-full overflow-hidden"
                            >
                                <div
                                    class="h-full bg-gradient-to-r from-orange-500 to-red-600 rounded-full"
                                    :style="`width: ${getSoldPercentage(
                                        product.id
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
