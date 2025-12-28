<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import {
    FunnelIcon,
    AdjustmentsHorizontalIcon,
    ChevronDownIcon,
    XMarkIcon,
    TagIcon,
    Squares2X2Icon,
    FireIcon,
    BanknotesIcon,
    ArrowTrendingUpIcon,
    ArrowTrendingDownIcon,
    ClockIcon,
    CheckIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/24/outline";

import AppLayout from "@/Layouts/MainLayout.vue";
import ProductCard from "@/Components/ProductCard.vue";

// Props
const props = defineProps({
    products: Object,
    categories: Array,
    brands: Array,
    filters: Object,
});

const page = usePage();
const locale = computed(() => page.props.locale || "en");

// Helper: Name Parser
const getName = (nameField) => {
    if (!nameField) return "Unknown";
    try {
        if (typeof nameField === "object") {
            return nameField[locale.value] || nameField["en"] || "Unknown";
        }
        if (typeof nameField === "string" && nameField.startsWith("{")) {
            const parsed = JSON.parse(nameField);
            return parsed[locale.value] || parsed["en"] || "Unknown";
        }
        return nameField;
    } catch (e) {
        return nameField;
    }
};

// State
const showMobileFilters = ref(false);
const isSortOpen = ref(false);
const sortContainer = ref(null);
const isLoading = ref(false);

// ✅ Price Slider Config
const minGap = 1000;
const sliderMax = 500000; // 5 Lakh Limit

const params = ref({
    search: props.filters.search || "",
    category: props.filters.category || "",
    brand: props.filters.brand || "",
    sort: props.filters.sort || "newest",
    min_price: props.filters.min_price ? parseInt(props.filters.min_price) : 0,
    max_price: props.filters.max_price
        ? parseInt(props.filters.max_price)
        : sliderMax,
});

// ✅ Check Active Filters
const hasActiveFilters = computed(() => {
    return (
        params.value.category !== "" ||
        params.value.brand !== "" ||
        params.value.search !== "" ||
        params.value.min_price > 0 ||
        params.value.max_price < sliderMax
    );
});

// Sort Options
const sortOptions = [
    { label: "Newest Arrivals", value: "newest", icon: FireIcon },
    {
        label: "Price: Low to High",
        value: "price_low",
        icon: ArrowTrendingUpIcon,
    },
    {
        label: "Price: High to Low",
        value: "price_high",
        icon: ArrowTrendingDownIcon,
    },
    { label: "Oldest Items", value: "oldest", icon: ClockIcon },
];

const currentSortLabel = computed(() => {
    return (
        sortOptions.find((opt) => opt.value === params.value.sort)?.label ||
        "Sort By"
    );
});

const selectSort = (value) => {
    params.value.sort = value;
    isSortOpen.value = false;
    updateParams();
};

const closeDropdown = (e) => {
    if (sortContainer.value && !sortContainer.value.contains(e.target)) {
        isSortOpen.value = false;
    }
};

onMounted(() => document.addEventListener("click", closeDropdown));
onUnmounted(() => document.removeEventListener("click", closeDropdown));

// ✅ Slider Logic Corrected
const handleMinChange = () => {
    if (params.value.max_price - params.value.min_price < minGap) {
        params.value.min_price = params.value.max_price - minGap;
    }
    updateParams();
};

const handleMaxChange = () => {
    if (params.value.max_price - params.value.min_price < minGap) {
        params.value.max_price = params.value.min_price + minGap;
    }
    updateParams();
};

// CSS logic to set the left and right position of the colored bar
const sliderStyle = computed(() => {
    const minPercent = (params.value.min_price / sliderMax) * 100;
    const maxPercent = (params.value.max_price / sliderMax) * 100;
    return { left: `${minPercent}%`, right: `${100 - maxPercent}%` };
});

let debounceTimeout;
const updateParams = () => {
    isLoading.value = true;
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        router.get(route("shop.index"), params.value, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            onFinish: () => {
                isLoading.value = false;
                if (window.innerWidth < 1024) showMobileFilters.value = false;
            },
        });
    }, 600);
};

watch(showMobileFilters, (val) => {
    if (typeof document !== "undefined") {
        document.body.style.overflow = val ? "hidden" : "";
    }
});

const clearFilters = () => {
    params.value = {
        search: "",
        category: "",
        brand: "",
        sort: "newest",
        min_price: 0,
        max_price: sliderMax,
    };
    updateParams();
};
</script>

<template>
    <AppLayout>
        <Head title="Premium Shop Collection" />

        <div
            class="bg-[#05070a] min-h-screen pb-24 relative font-sans text-slate-300 selection:bg-orange-500 selection:text-white"
        >
            <div
                class="fixed top-0 left-0 w-full h-[800px] bg-gradient-to-b from-orange-600/10 via-blue-900/5 to-transparent pointer-events-none z-0"
            ></div>
            <div
                class="fixed top-[-20%] right-[-10%] w-[600px] h-[600px] bg-orange-600/5 rounded-full blur-[120px] pointer-events-none z-0"
            ></div>

            <div
                class="sticky top-[60px] lg:top-[70px] z-40 bg-[#05070a]/80 backdrop-blur-xl border-b border-white/5 py-4 transition-all duration-300"
            >
                <div class="container mx-auto px-4">
                    <div
                        class="flex items-center gap-3 overflow-x-auto no-scrollbar pb-1 mask-linear-fade"
                    >
                        <button
                            @click="
                                params.category = '';
                                updateParams();
                            "
                            class="flex-shrink-0 px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 border relative overflow-hidden group"
                            :class="
                                params.category === ''
                                    ? 'bg-gradient-to-r from-orange-600 to-red-600 border-orange-500 text-white shadow-[0_0_20px_rgba(234,88,12,0.4)]'
                                    : 'bg-[#151925]/50 border-white/10 text-gray-400 hover:text-white hover:border-white/30 hover:bg-[#1A1F2E]'
                            "
                        >
                            <span
                                class="relative z-10 flex items-center gap-2 whitespace-nowrap"
                            >
                                <Squares2X2Icon class="w-4 h-4" /> All
                                Collections
                            </span>
                        </button>

                        <button
                            v-for="cat in categories"
                            :key="cat.id"
                            @click="
                                params.category = cat.slug;
                                updateParams();
                            "
                            class="flex-shrink-0 px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-300 border flex items-center gap-2 group whitespace-nowrap relative"
                            :class="
                                params.category === cat.slug
                                    ? 'bg-gradient-to-r from-orange-600 to-red-600 border-orange-500 text-white shadow-[0_0_20px_rgba(234,88,12,0.4)]'
                                    : 'bg-[#151925]/50 border-white/10 text-gray-400 hover:text-white hover:border-white/30 hover:bg-[#1A1F2E]'
                            "
                        >
                            <span>{{ getName(cat.name) }}</span>
                            <span
                                v-if="params.category !== cat.slug"
                                class="text-[10px] bg-white/10 px-1.5 py-0.5 rounded-md border border-white/5 ml-1 transition-colors group-hover:bg-white/20"
                            >
                                {{ cat.products_count }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="container mx-auto px-4 pt-6 lg:pt-10 relative z-10">
                <div
                    class="lg:hidden flex flex-col gap-4 mb-6 sticky top-[135px] z-30 bg-[#05070a]/95 backdrop-blur-md py-3 -mx-4 px-4 border-b border-white/5 shadow-lg"
                >
                    <div class="relative w-full">
                        <MagnifyingGlassIcon
                            class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"
                        />
                        <input
                            v-model="params.search"
                            @input="updateParams"
                            type="text"
                            placeholder="Search products..."
                            class="w-full bg-[#151925] border border-white/10 rounded-xl pl-11 pr-4 py-3 text-sm text-white focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 transition-all placeholder-gray-600 shadow-inner"
                        />
                    </div>
                    <div class="flex gap-3">
                        <button
                            @click="showMobileFilters = true"
                            class="flex-1 bg-[#151925] border border-white/10 text-white py-2.5 rounded-xl flex items-center justify-center gap-2 font-bold shadow-lg active:scale-95 transition-all text-sm hover:bg-[#1e2330]"
                        >
                            <AdjustmentsHorizontalIcon
                                class="w-5 h-5 text-orange-500"
                            />
                            Filters
                            <span
                                v-if="hasActiveFilters"
                                class="w-2 h-2 bg-orange-500 rounded-full animate-pulse ml-1"
                            ></span>
                        </button>

                        <div class="flex-1 relative" ref="sortContainer">
                            <button
                                @click="isSortOpen = !isSortOpen"
                                class="w-full bg-[#151925] border border-white/10 text-white py-2.5 rounded-xl flex items-center justify-between px-4 font-bold shadow-lg active:scale-95 transition-all text-sm hover:bg-[#1e2330]"
                            >
                                <span class="truncate">{{
                                    currentSortLabel
                                }}</span>
                                <ChevronDownIcon
                                    class="w-4 h-4 text-gray-400 transition-transform duration-300"
                                    :class="{ 'rotate-180': isSortOpen }"
                                />
                            </button>
                            <transition
                                enter-active-class="transition ease-out duration-200"
                                enter-from-class="opacity-0 translate-y-2"
                                enter-to-class="opacity-100 translate-y-0"
                                leave-active-class="transition ease-in duration-150"
                                leave-from-class="opacity-100 translate-y-0"
                                leave-to-class="opacity-0 translate-y-2"
                            >
                                <div
                                    v-if="isSortOpen"
                                    class="absolute top-full right-0 mt-2 w-full bg-[#1A1F2E] border border-white/10 rounded-xl shadow-2xl z-[60] overflow-hidden backdrop-blur-3xl ring-1 ring-black/50"
                                >
                                    <button
                                        v-for="opt in sortOptions"
                                        :key="opt.value"
                                        @click="selectSort(opt.value)"
                                        class="w-full text-left px-4 py-3 text-sm text-gray-300 hover:bg-orange-600 hover:text-white flex items-center gap-3 transition-colors border-b border-white/5 last:border-0"
                                    >
                                        <component
                                            :is="opt.icon"
                                            class="w-4 h-4 opacity-70"
                                        />
                                        {{ opt.label }}
                                        <CheckIcon
                                            v-if="params.sort === opt.value"
                                            class="w-4 h-4 ml-auto text-white"
                                        />
                                    </button>
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-8 items-start">
                    <aside
                        class="fixed inset-0 z-[60] lg:static lg:z-auto lg:w-[280px] lg:block transition-all duration-300"
                        :class="
                            showMobileFilters
                                ? 'visible'
                                : 'invisible lg:visible'
                        "
                    >
                        <div
                            class="absolute inset-0 bg-black/60 backdrop-blur-sm lg:hidden transition-opacity duration-300"
                            :class="
                                showMobileFilters ? 'opacity-100' : 'opacity-0'
                            "
                            @click="showMobileFilters = false"
                        ></div>

                        <div
                            class="absolute lg:sticky lg:top-[150px] right-0 lg:left-0 h-full lg:h-auto w-[85%] max-w-[340px] lg:w-full bg-[#0b0e14] lg:bg-transparent p-6 lg:p-0 overflow-y-auto lg:overflow-visible transition-transform duration-300 lg:transform-none shadow-2xl lg:shadow-none border-l lg:border-none border-white/10"
                            :class="
                                showMobileFilters
                                    ? 'translate-x-0'
                                    : 'translate-x-full lg:translate-x-0'
                            "
                        >
                            <div
                                class="flex justify-between items-center lg:hidden mb-8"
                            >
                                <h2
                                    class="text-xl font-black text-white flex items-center gap-2"
                                >
                                    <FunnelIcon
                                        class="w-5 h-5 text-orange-500"
                                    />
                                    Filters
                                </h2>
                                <button
                                    @click="showMobileFilters = false"
                                    class="p-2 bg-white/5 rounded-full text-gray-400 hover:text-white hover:bg-red-500/20 hover:text-red-500 transition-all"
                                >
                                    <XMarkIcon class="w-6 h-6" />
                                </button>
                            </div>

                            <div class="space-y-6">
                                <transition
                                    enter-active-class="transition ease-out duration-300"
                                    enter-from-class="opacity-0 -translate-y-4"
                                    enter-to-class="opacity-100 translate-y-0"
                                    leave-active-class="transition ease-in duration-200"
                                    leave-from-class="opacity-100 translate-y-0"
                                    leave-to-class="opacity-0 -translate-y-4"
                                >
                                    <div v-if="hasActiveFilters">
                                        <button
                                            @click="clearFilters"
                                            class="w-full group relative overflow-hidden rounded-xl bg-[#1A1F2E] border border-red-500/30 p-3 text-center transition-all hover:border-red-500 hover:shadow-[0_0_20px_rgba(239,68,68,0.3)] active:scale-95"
                                        >
                                            <div
                                                class="absolute inset-0 w-0 bg-gradient-to-r from-red-600 to-rose-600 transition-all duration-[250ms] ease-out group-hover:w-full opacity-10"
                                            ></div>

                                            <span
                                                class="relative flex items-center justify-center gap-2 text-sm font-bold text-red-500 group-hover:text-white transition-colors"
                                            >
                                                <XMarkIcon
                                                    class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"
                                                />
                                                Clear All Filters
                                            </span>
                                        </button>
                                        <p
                                            class="text-center text-[10px] text-gray-500 mt-2 group-hover:text-gray-400"
                                        >
                                            Resetting will show all available
                                            products
                                        </p>
                                    </div>
                                </transition>

                                <div
                                    class="hidden lg:block bg-[#12141c]/50 backdrop-blur-md p-1 rounded-2xl border border-white/5 shadow-lg group focus-within:border-orange-500/50 transition-colors"
                                >
                                    <div class="relative">
                                        <MagnifyingGlassIcon
                                            class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 group-focus-within:text-orange-500 transition-colors"
                                        />
                                        <input
                                            v-model="params.search"
                                            @input="updateParams"
                                            type="text"
                                            placeholder="Search products..."
                                            class="w-full bg-transparent border-none rounded-xl pl-10 pr-4 py-3 text-sm text-white focus:ring-0 placeholder-gray-600"
                                        />
                                    </div>
                                </div>

                                <div
                                    class="hidden lg:block bg-[#12141c] p-5 rounded-3xl border border-white/5 shadow-xl"
                                    ref="sortContainer"
                                >
                                    <h3
                                        class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4"
                                    >
                                        Sort Products
                                    </h3>
                                    <button
                                        @click="isSortOpen = !isSortOpen"
                                        class="w-full bg-[#05070a] border border-white/10 text-white py-3 rounded-xl flex items-center justify-between px-4 font-medium hover:border-orange-500/30 transition-all group"
                                    >
                                        <span
                                            class="flex items-center gap-2 text-sm text-gray-300 group-hover:text-white"
                                        >
                                            <component
                                                :is="
                                                    sortOptions.find(
                                                        (o) =>
                                                            o.value ===
                                                            params.sort
                                                    )?.icon
                                                "
                                                class="w-4 h-4 text-orange-500"
                                            />
                                            {{ currentSortLabel }}
                                        </span>
                                        <ChevronDownIcon
                                            class="w-4 h-4 text-gray-500 transition-transform duration-300"
                                            :class="{
                                                'rotate-180': isSortOpen,
                                            }"
                                        />
                                    </button>

                                    <transition
                                        enter-active-class="transition ease-out duration-200"
                                        enter-from-class="opacity-0 -translate-y-2"
                                        enter-to-class="opacity-100 translate-y-0"
                                        leave-active-class="transition ease-in duration-150"
                                        leave-from-class="opacity-100 translate-y-0"
                                        leave-to-class="opacity-0 -translate-y-2"
                                    >
                                        <div
                                            v-if="isSortOpen"
                                            class="absolute left-0 right-0 mx-5 mt-2 bg-[#1A1F2E] border border-white/10 rounded-xl shadow-2xl z-50 overflow-hidden"
                                        >
                                            <button
                                                v-for="opt in sortOptions"
                                                :key="opt.value"
                                                @click="selectSort(opt.value)"
                                                class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-orange-600 hover:text-white flex items-center gap-3 transition-colors border-b border-white/5 last:border-0"
                                            >
                                                <component
                                                    :is="opt.icon"
                                                    class="w-4 h-4"
                                                />
                                                {{ opt.label }}
                                            </button>
                                        </div>
                                    </transition>
                                </div>

                                <div
                                    class="bg-[#12141c] p-6 rounded-3xl border border-white/5 shadow-xl"
                                >
                                    <div
                                        class="flex justify-between items-center mb-6"
                                    >
                                        <h3
                                            class="text-white font-bold flex items-center gap-2"
                                        >
                                            <BanknotesIcon
                                                class="w-5 h-5 text-orange-500"
                                            />
                                            Price Range
                                        </h3>
                                    </div>

                                    <div
                                        class="relative w-full h-12 flex items-center justify-center"
                                    >
                                        <div
                                            class="absolute w-full h-1.5 bg-gray-700 rounded-full overflow-hidden"
                                        >
                                            <div
                                                class="absolute h-full bg-gradient-to-r from-orange-600 to-red-500"
                                                :style="sliderStyle"
                                            ></div>
                                        </div>

                                        <input
                                            type="range"
                                            :min="0"
                                            :max="sliderMax"
                                            v-model.number="params.min_price"
                                            @input="handleMinChange"
                                            class="absolute w-full h-1.5 appearance-none bg-transparent pointer-events-none z-20 range-thumb-custom"
                                        />
                                        <input
                                            type="range"
                                            :min="0"
                                            :max="sliderMax"
                                            v-model.number="params.max_price"
                                            @input="handleMaxChange"
                                            class="absolute w-full h-1.5 appearance-none bg-transparent pointer-events-none z-20 range-thumb-custom"
                                        />
                                    </div>

                                    <div class="flex items-center gap-3 mt-2">
                                        <div
                                            class="bg-[#05070a] px-3 py-2 rounded-xl border border-white/10 w-1/2 flex flex-col items-start group hover:border-orange-500/30 transition-colors"
                                        >
                                            <span
                                                class="text-[10px] text-gray-500 uppercase tracking-wider"
                                                >Min</span
                                            >
                                            <span
                                                class="text-sm font-bold text-white"
                                                >৳{{
                                                    params.min_price.toLocaleString()
                                                }}</span
                                            >
                                        </div>
                                        <div
                                            class="bg-[#05070a] px-3 py-2 rounded-xl border border-white/10 w-1/2 flex flex-col items-end group hover:border-orange-500/30 transition-colors"
                                        >
                                            <span
                                                class="text-[10px] text-gray-500 uppercase tracking-wider"
                                                >Max</span
                                            >
                                            <span
                                                class="text-sm font-bold text-white"
                                                >৳{{
                                                    params.max_price.toLocaleString()
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="bg-[#12141c] p-6 rounded-3xl border border-white/5 shadow-xl"
                                >
                                    <div
                                        class="flex justify-between items-center mb-4"
                                    >
                                        <h3
                                            class="text-white font-bold flex items-center gap-2"
                                        >
                                            <TagIcon
                                                class="w-5 h-5 text-orange-500"
                                            />
                                            Brands
                                        </h3>
                                        <button
                                            v-if="params.brand"
                                            @click="
                                                params.brand = '';
                                                updateParams();
                                            "
                                            class="text-xs text-red-400 hover:text-red-300 flex items-center gap-1 bg-red-500/10 px-2 py-1 rounded-md transition-colors"
                                        >
                                            <XMarkIcon class="w-3 h-3" /> Clear
                                        </button>
                                    </div>
                                    <div
                                        class="space-y-1 max-h-64 overflow-y-auto pr-2 custom-scrollbar"
                                    >
                                        <label
                                            v-for="brand in brands"
                                            :key="brand.id"
                                            class="flex items-center gap-3 cursor-pointer group p-2.5 rounded-xl hover:bg-white/5 transition-all duration-200 border border-transparent hover:border-white/5"
                                        >
                                            <div
                                                class="relative flex items-center justify-center"
                                            >
                                                <input
                                                    type="radio"
                                                    :value="brand.slug"
                                                    v-model="params.brand"
                                                    @change="updateParams"
                                                    class="peer sr-only"
                                                />
                                                <div
                                                    class="w-5 h-5 border-2 border-gray-600 rounded-full peer-checked:border-orange-500 peer-checked:scale-110 transition-all"
                                                ></div>
                                                <div
                                                    class="absolute w-2.5 h-2.5 bg-orange-500 rounded-full opacity-0 peer-checked:opacity-100 transition-all transform scale-0 peer-checked:scale-100"
                                                ></div>
                                            </div>
                                            <span
                                                class="text-sm text-gray-400 group-hover:text-white transition-colors flex-1 font-medium"
                                                >{{ brand.name }}</span
                                            >
                                            <span
                                                class="text-[10px] text-gray-500 bg-[#05070a] px-2 py-0.5 rounded-md border border-white/5"
                                                >{{
                                                    brand.products_count
                                                }}</span
                                            >
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <main class="lg:w-3/4 flex-1 min-h-[500px]">
                        <div
                            v-if="isLoading"
                            class="absolute inset-0 z-50 bg-[#05070a]/50 backdrop-blur-sm rounded-3xl flex items-center justify-center"
                        >
                            <div
                                class="w-12 h-12 border-4 border-orange-500 border-t-transparent rounded-full animate-spin"
                            ></div>
                        </div>

                        <div
                            v-if="products.data.length > 0"
                            class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-2 md:gap-6"
                        >
                            <ProductCard
                                v-for="product in products.data"
                                :key="product.id"
                                :product="product"
                                class="h-full w-full"
                            />
                        </div>

                        <div
                            v-else
                            class="flex flex-col items-center justify-center py-32 bg-[#12141c]/50 backdrop-blur-sm rounded-3xl border border-white/5 text-center px-4 relative overflow-hidden group"
                        >
                            <div
                                class="absolute inset-0 bg-gradient-to-b from-transparent via-orange-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"
                            ></div>
                            <div
                                class="bg-gradient-to-br from-orange-500/10 to-red-500/10 p-8 rounded-full mb-6 border border-orange-500/20 relative z-10 shadow-[0_0_30px_rgba(234,88,12,0.1)]"
                            >
                                <FunnelIcon class="w-16 h-16 text-orange-500" />
                            </div>
                            <h3
                                class="text-2xl font-black text-white mb-2 relative z-10 tracking-tight"
                            >
                                No Matches Found
                            </h3>
                            <p
                                class="text-gray-400 max-w-md mx-auto mb-8 relative z-10 leading-relaxed"
                            >
                                We couldn't find any products matching your
                                current filters. Try adjusting your price range
                                or search terms.
                            </p>
                            <button
                                @click="clearFilters"
                                class="px-8 py-3 bg-white text-black font-bold rounded-xl hover:bg-gray-200 transition relative z-10 shadow-lg hover:scale-105 duration-300 flex items-center gap-2"
                            >
                                <XMarkIcon class="w-5 h-5" /> Clear All Filters
                            </button>
                        </div>

                        <div
                            v-if="products.links.length > 3"
                            class="mt-12 flex justify-center pb-10"
                        >
                            <div
                                class="flex flex-wrap justify-center gap-2 bg-[#151925]/80 backdrop-blur p-2 rounded-2xl border border-white/5 shadow-2xl"
                            >
                                <Link
                                    v-for="(link, k) in products.links"
                                    :key="k"
                                    :href="link.url || '#'"
                                    v-html="link.label"
                                    class="px-4 py-2.5 rounded-xl text-xs md:text-sm font-bold transition-all duration-300"
                                    :class="{
                                        'bg-gradient-to-r from-orange-600 to-red-600 text-white shadow-lg shadow-orange-600/20 scale-105':
                                            link.active,
                                        'text-gray-400 hover:bg-white/10 hover:text-white':
                                            !link.active,
                                        'opacity-30 pointer-events-none':
                                            !link.url,
                                    }"
                                />
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.range-thumb-custom::-webkit-slider-thumb {
    pointer-events: auto;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ffffff;
    border: 4px solid #ea580c;
    box-shadow: 0 0 10px rgba(234, 88, 12, 0.5);
    cursor: pointer;
    -webkit-appearance: none;
    margin-top: 0px;
    transition: transform 0.1s;
}

.range-thumb-custom::-webkit-slider-thumb:hover {
    transform: scale(1.2);
}

.range-thumb-custom::-moz-range-thumb {
    pointer-events: auto;
    width: 20px;
    height: 20px;
    border: none;
    border-radius: 50%;
    background: #ffffff;
    border: 4px solid #ea580c;
    box-shadow: 0 0 10px rgba(234, 88, 12, 0.5);
    cursor: pointer;
    transition: transform 0.1s;
}

.mask-linear-fade {
    mask-image: linear-gradient(to right, black 85%, transparent 100%);
    -webkit-mask-image: linear-gradient(to right, black 85%, transparent 100%);
}

.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.02);
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(234, 88, 12, 0.5);
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
