<script setup>
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {
    ShoppingCartIcon,
    StarIcon,
    HeartIcon,
    EyeIcon,
    FunnelIcon,
    ArrowsUpDownIcon,
    ChevronDownIcon,
    HomeIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { HeartIcon as HeartSolidIcon } from "@heroicons/vue/24/solid";
import { useCartStore } from "@/Stores/cartStore";
import { useWishlistStore } from "@/Stores/wishlistStore";
import { ref, reactive, watch } from "vue";
import { debounce } from "lodash";

const props = defineProps({
    products: Object,
    categoryName: String,
    filters: Object,
});

const page = usePage();
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();

// --- 🎛️ Filter & Sort State ---
const showFilter = ref(false);
const showSort = ref(false);

const params = reactive({
    category: props.filters.category || null,
    sort: props.filters.sort || "newest",
    min_price: props.filters.min_price || "",
    max_price: props.filters.max_price || "",
});

// --- 🔄 Update Logic with Debounce ---
const updateParams = debounce(() => {
    router.get(route("products.index"), params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}, 500); // 500ms delay for smoother typing

// Special watcher for sort to trigger immediately without debounce
const changeSort = (value) => {
    params.sort = value;
    showSort.value = false;
    // Direct call for immediate sort
    router.get(route("products.index"), params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const applyPriceFilter = () => {
    showFilter.value = false;
    updateParams(); // Trigger fetch
};

const resetFilters = () => {
    params.min_price = "";
    params.max_price = "";
    params.sort = "newest";
    // Keep category if it exists
    updateParams();
};

const addToCart = (product) => {
    cartStore.addToCart(product);
};
const toggleWishlist = (product) => {
    wishlistStore.toggle(product.id);
};

// ✅ JSON Name Fix Helper
const getLocalizedName = (name) => {
    if (!name) return "";
    try {
        const parsed =
            typeof name === "string" && name.startsWith("{")
                ? JSON.parse(name)
                : name;
        if (typeof parsed === "object" && parsed !== null) {
            const locale = page.props.locale || "en";
            return parsed[locale] || parsed["en"] || Object.values(parsed)[0];
        }
        return name;
    } catch (e) {
        return name;
    }
};
</script>

<template>
    <Head :title="getLocalizedName(categoryName)" />

    <MainLayout>
        <div
            class="min-h-screen bg-[#0B0F19] py-12 relative overflow-hidden font-sans"
            @click="
                showSort = false;
                showFilter = false;
            "
        >
            <div
                class="absolute top-0 left-0 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[150px] pointer-events-none"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[150px] pointer-events-none"
            ></div>

            <div class="container mx-auto px-4 lg:px-8 relative z-10">
                <div class="flex items-center gap-2 text-sm text-gray-400 mb-8">
                    <Link href="/" class="hover:text-white transition"
                        ><HomeIcon class="w-4 h-4"
                    /></Link>
                    <span>/</span>
                    <span class="text-white font-bold">{{
                        getLocalizedName(categoryName)
                    }}</span>
                </div>

                <div
                    class="flex flex-col md:flex-row justify-between items-end mb-10 border-b border-white/10 pb-6 gap-4"
                >
                    <div>
                        <h1
                            class="text-3xl md:text-4xl font-black text-white tracking-tight mb-2"
                        >
                            {{ getLocalizedName(categoryName) }}
                        </h1>
                        <p class="text-gray-400 text-sm">
                            Found {{ products.total }} items
                        </p>
                    </div>

                    <div class="flex gap-3 relative" @click.stop>
                        <div class="relative">
                            <button
                                @click="
                                    showFilter = !showFilter;
                                    showSort = false;
                                "
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white hover:bg-white/10 transition text-sm font-bold"
                                :class="{
                                    'bg-indigo-600 border-indigo-600':
                                        params.min_price || params.max_price,
                                }"
                            >
                                <FunnelIcon class="w-4 h-4" />
                                {{ __("Filter") }}
                            </button>

                            <div
                                v-if="showFilter"
                                class="absolute right-0 mt-3 w-72 bg-[#1a1f2e] border border-white/10 rounded-2xl shadow-2xl p-5 z-50 animate-fade-in-up origin-top-right"
                            >
                                <div
                                    class="flex justify-between items-center mb-4"
                                >
                                    <h3 class="text-white font-bold">
                                        {{ __("Price Range") }}
                                    </h3>
                                    <button
                                        @click="resetFilters"
                                        class="text-xs text-rose-400 hover:text-rose-300"
                                    >
                                        Reset
                                    </button>
                                </div>
                                <div class="flex gap-3 mb-4">
                                    <input
                                        v-model="params.min_price"
                                        type="number"
                                        placeholder="Min"
                                        class="w-full bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-white text-sm focus:border-indigo-500 focus:outline-none"
                                    />
                                    <input
                                        v-model="params.max_price"
                                        type="number"
                                        placeholder="Max"
                                        class="w-full bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-white text-sm focus:border-indigo-500 focus:outline-none"
                                    />
                                </div>
                                <button
                                    @click="applyPriceFilter"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg text-sm font-bold transition"
                                >
                                    {{ __("Apply Filter") }}
                                </button>
                            </div>
                        </div>

                        <div class="relative">
                            <button
                                @click="
                                    showSort = !showSort;
                                    showFilter = false;
                                "
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white hover:bg-white/10 transition text-sm font-bold"
                            >
                                <ArrowsUpDownIcon class="w-4 h-4" />
                                <span class="hidden sm:inline">{{
                                    __("Sort")
                                }}</span>
                                <ChevronDownIcon class="w-3 h-3" />
                            </button>

                            <div
                                v-if="showSort"
                                class="absolute right-0 mt-3 w-48 bg-[#1a1f2e] border border-white/10 rounded-2xl shadow-2xl overflow-hidden z-50 animate-fade-in-up origin-top-right"
                            >
                                <button
                                    @click="changeSort('newest')"
                                    class="w-full text-left px-4 py-3 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition"
                                    :class="{
                                        'text-indigo-400 font-bold':
                                            params.sort === 'newest',
                                    }"
                                >
                                    Newest Arrivals
                                </button>
                                <button
                                    @click="changeSort('price_low')"
                                    class="w-full text-left px-4 py-3 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition"
                                    :class="{
                                        'text-indigo-400 font-bold':
                                            params.sort === 'price_low',
                                    }"
                                >
                                    Price: Low to High
                                </button>
                                <button
                                    @click="changeSort('price_high')"
                                    class="w-full text-left px-4 py-3 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition"
                                    :class="{
                                        'text-indigo-400 font-bold':
                                            params.sort === 'price_high',
                                    }"
                                >
                                    Price: High to Low
                                </button>
                                <button
                                    @click="changeSort('oldest')"
                                    class="w-full text-left px-4 py-3 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition"
                                    :class="{
                                        'text-indigo-400 font-bold':
                                            params.sort === 'oldest',
                                    }"
                                >
                                    Oldest First
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-if="products.data.length > 0"
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="product in products.data"
                        :key="product.id"
                        class="group relative bg-white/5 backdrop-blur-sm rounded-3xl p-4 border border-white/10 hover:border-indigo-500/50 hover:bg-white/10 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-indigo-500/20 flex flex-col h-full"
                    >
                        <div
                            class="relative h-64 rounded-2xl bg-white overflow-hidden mb-4 flex-shrink-0"
                        >
                            <Link
                                :href="route('product.details', product.slug)"
                                class="block w-full h-full"
                            >
                                <img
                                    v-if="product.thumb_image"
                                    :src="'/storage/' + product.thumb_image"
                                    :alt="getLocalizedName(product.name)"
                                    class="w-full h-full object-contain p-6 group-hover:scale-110 transition-transform duration-700 ease-out"
                                />
                                <div
                                    v-else
                                    class="w-full h-full flex items-center justify-center text-gray-300"
                                >
                                    No Image
                                </div>
                            </Link>
                            <span
                                v-if="product.discount_price"
                                class="absolute top-3 left-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg z-10"
                                >Sale</span
                            >

                            <div
                                class="absolute top-3 right-3 flex flex-col gap-2 translate-x-12 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300"
                            >
                                <button
                                    @click.prevent="toggleWishlist(product)"
                                    class="w-9 h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-lg text-gray-500 hover:text-red-500 transition-colors"
                                >
                                    <HeartSolidIcon
                                        v-if="
                                            wishlistStore.isInWishlist(
                                                product.id
                                            )
                                        "
                                        class="w-5 h-5 text-red-500 animate-pulse"
                                    /><HeartIcon v-else class="w-5 h-5" />
                                </button>
                                <Link
                                    :href="
                                        route('product.details', product.slug)
                                    "
                                    class="w-9 h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-lg text-gray-500 hover:text-indigo-600 transition-colors"
                                    ><EyeIcon class="w-5 h-5"
                                /></Link>
                            </div>
                        </div>

                        <div class="px-1 flex-1 flex flex-col">
                            <p
                                class="text-xs text-indigo-400 font-bold uppercase tracking-wider mb-1"
                            >
                                {{
                                    getLocalizedName(product.category?.name) ||
                                    "General"
                                }}
                            </p>
                            <Link
                                :href="route('product.details', product.slug)"
                            >
                                <h3
                                    class="font-bold text-sm md:text-base text-white leading-tight group-hover:text-indigo-400 transition-colors mb-2 line-clamp-2 min-h-[2.5rem]"
                                    :title="getLocalizedName(product.name)"
                                >
                                    {{ getLocalizedName(product.name) }}
                                </h3>
                            </Link>
                            <div class="flex items-center gap-1 mb-4">
                                <div class="flex text-yellow-400">
                                    <StarIcon
                                        v-for="i in 5"
                                        :key="i"
                                        class="w-3.5 h-3.5"
                                        :class="
                                            i <=
                                            Math.round(
                                                product.reviews_avg_rating || 0
                                            )
                                                ? 'fill-current'
                                                : 'text-gray-600 fill-none'
                                        "
                                    />
                                </div>
                                <span
                                    class="text-xs font-bold text-gray-400 ml-1"
                                >
                                    ({{
                                        product.reviews_avg_rating
                                            ? parseFloat(
                                                  product.reviews_avg_rating
                                              ).toFixed(1)
                                            : "0.0"
                                    }})
                                </span>
                                <span class="text-[10px] text-gray-500 ml-1">
                                    | {{ product.reviews_count || 0 }} Reviews
                                </span>
                            </div>
                            <div
                                class="mt-auto flex items-center justify-between border-t border-white/10 pt-4"
                            >
                                <div class="flex flex-col">
                                    <span
                                        v-if="product.discount_price"
                                        class="text-xs text-gray-500 line-through font-medium"
                                        >৳{{
                                            parseInt(product.base_price) + 500
                                        }}</span
                                    >
                                    <span
                                        class="text-lg font-black text-white tracking-tight"
                                        >৳{{ product.base_price }}</span
                                    >
                                </div>
                                <button
                                    @click.prevent="addToCart(product)"
                                    class="w-10 h-10 bg-white text-gray-900 rounded-full flex items-center justify-center shadow-lg shadow-white/10 hover:bg-indigo-500 hover:text-white hover:scale-110 hover:rotate-6 transition-all duration-300"
                                >
                                    <ShoppingCartIcon class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-else
                    class="text-center py-20 bg-white/5 rounded-3xl border border-white/10"
                >
                    <p class="text-gray-400 text-lg">
                        No products match your filters.
                    </p>
                    <button
                        @click="resetFilters"
                        class="mt-4 text-indigo-400 font-bold hover:underline"
                    >
                        Clear Filters
                    </button>
                </div>

                <div
                    v-if="products.links.length > 3"
                    class="mt-16 flex justify-center"
                >
                    <div class="flex flex-wrap gap-2 justify-center">
                        <Link
                            v-for="(link, key) in products.links"
                            :key="key"
                            :href="link.url"
                            v-html="link.label"
                            class="px-4 py-2 rounded-lg text-sm font-bold transition-all border"
                            :class="
                                link.active
                                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-500/30'
                                    : 'bg-white/5 text-gray-400 border-white/10 hover:bg-white/10 hover:text-white'
                            "
                            :preserve-scroll="true"
                        />
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 0.2s ease-out forwards;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>
