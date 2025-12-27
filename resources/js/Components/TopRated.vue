<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import {
    StarIcon, // Top Rated এর জন্য স্টার আইকন
    ArrowRightIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from "@heroicons/vue/24/solid";
import ProductCard from "@/Components/ProductCard.vue";

// ✅ Props Receive
const props = defineProps({
    products: {
        type: Array,
        default: () => [],
    },
});

// --- 🔄 Scroll Logic ---
const scrollContainer = ref(null);

const scrollLeft = () => {
    if (scrollContainer.value) {
        scrollContainer.value.scrollBy({ left: -304, behavior: "smooth" });
    }
};

const scrollRight = () => {
    if (scrollContainer.value) {
        scrollContainer.value.scrollBy({ left: 304, behavior: "smooth" });
    }
};
</script>

<template>
    <section
        v-if="products && products.length > 0"
        class="py-16 relative overflow-hidden bg-[#0B0F19]"
    >
        <div
            class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 pointer-events-none"
        ></div>
        <div
            class="absolute top-0 left-0 w-[600px] h-[600px] bg-yellow-600/10 rounded-full blur-[150px] pointer-events-none"
        ></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div
                class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6 border-b border-white/10 pb-6"
            >
                <div class="flex items-center gap-6">
                    <div class="relative group">
                        <div
                            class="absolute inset-0 bg-yellow-500 blur-xl opacity-40 group-hover:opacity-60 transition-opacity duration-500"
                        ></div>
                        <div
                            class="w-16 h-16 relative bg-[#151925] border border-yellow-500/30 rounded-2xl flex items-center justify-center transform group-hover:-rotate-6 transition-transform duration-500"
                        >
                            <StarIcon
                                class="w-8 h-8 text-yellow-400 animate-spin-slow"
                            />
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span
                                class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"
                            ></span>
                            <span
                                class="text-xs font-bold text-yellow-400 uppercase tracking-widest"
                                >Customer Favorites</span
                            >
                        </div>
                        <h2
                            class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter"
                        >
                            Top
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-amber-600"
                                >Rated</span
                            >
                        </h2>
                    </div>
                </div>

                <Link
                    :href="route('products.index', { sort: 'rating' })"
                    class="hidden md:flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-white transition group"
                >
                    View All Top Rated
                    <ArrowRightIcon
                        class="w-4 h-4 text-yellow-500 group-hover:translate-x-1 transition-transform"
                    />
                </Link>
            </div>

            <div class="relative group/carousel">
                <button
                    @click="scrollLeft"
                    class="absolute left-0 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-[#151925]/80 hover:bg-yellow-500 backdrop-blur-md border border-white/10 rounded-full flex items-center justify-center text-white opacity-0 group-hover/carousel:opacity-100 transition-all duration-300 hover:scale-110 shadow-lg -ml-4 lg:-ml-6"
                >
                    <ChevronLeftIcon class="w-6 h-6" />
                </button>

                <button
                    @click="scrollRight"
                    class="absolute right-0 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-[#151925]/80 hover:bg-yellow-500 backdrop-blur-md border border-white/10 rounded-full flex items-center justify-center text-white opacity-0 group-hover/carousel:opacity-100 transition-all duration-300 hover:scale-110 shadow-lg -mr-4 lg:-mr-6"
                >
                    <ChevronRightIcon class="w-6 h-6" />
                </button>

                <div
                    ref="scrollContainer"
                    class="flex gap-6 overflow-x-auto no-scrollbar scroll-smooth pb-8 px-2 snap-x snap-mandatory"
                >
                    <ProductCard
                        v-for="product in products"
                        :key="product.id"
                        :product="product"
                        badge="Top"
                        badgeColor="bg-gradient-to-r from-yellow-500 to-amber-600"
                        class="snap-start border-yellow-500/10 hover:border-yellow-500/40 min-w-[280px]"
                    />
                </div>
            </div>

            <div class="md:hidden flex justify-center mt-4">
                <Link
                    :href="route('products.index', { sort: 'rating' })"
                    class="w-full text-center bg-white/5 hover:bg-white/10 text-white font-bold py-3 rounded-xl border border-white/10"
                >
                    See Top Rated
                </Link>
            </div>
        </div>
    </section>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.animate-spin-slow {
    animation: spin 10s linear infinite;
}
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
