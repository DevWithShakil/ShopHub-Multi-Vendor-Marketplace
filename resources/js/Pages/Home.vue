<script setup>
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import HeroSlider from "@/Components/HeroSlider.vue";
import CategoryMenu from "@/Components/CategoryMenu.vue";
import {
    ShoppingCartIcon,
    ArrowRightIcon,
    HeartIcon,
    TagIcon,
    FireIcon,
    ChevronDownIcon,
    CheckCircleIcon, // ✅ New
    XMarkIcon, // ✅ New
} from "@heroicons/vue/24/outline";
import {
    HeartIcon as HeartSolidIcon,
    StarIcon as StarSolidIcon,
} from "@heroicons/vue/24/solid";
import { useCartStore } from "@/Stores/cartStore";
import { useWishlistStore } from "@/Stores/wishlistStore";
import { ref, computed } from "vue";

// Props
const props = defineProps({
    products: Array,
    slides: Array,
    categories: Array,
});

const page = usePage();
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();

// --- 🔔 Toast Logic ---
const toastMessage = ref(null);
const toastType = ref("success"); // success | info

const showToast = (message, type = "success") => {
    toastMessage.value = message;
    toastType.value = type;
    setTimeout(() => {
        toastMessage.value = null;
    }, 3000);
};

// --- Helper Functions ---
const getLocalizedName = (nameField) => {
    try {
        if (typeof nameField === "string" && nameField.startsWith("{")) {
            const parsed = JSON.parse(nameField);
            return parsed[page.props.locale] || parsed["en"] || "Unknown";
        }
        if (typeof nameField === "object" && nameField !== null) {
            return nameField[page.props.locale] || nameField["en"] || "Unknown";
        }
        return nameField || "General";
    } catch (e) {
        return nameField || "General";
    }
};

// Group products by Root Category
const productsByRootCategory = computed(() => {
    const grouped = {};
    if (props.products) {
        props.products.forEach((product) => {
            if (!product.root_category) return;
            const rootName = getLocalizedName(product.root_category.name);
            const rootSlug = product.root_category.slug;
            if (!grouped[rootName]) {
                grouped[rootName] = { slug: rootSlug, products: [] };
            }
            grouped[rootName].products.push(product);
        });
    }
    return grouped;
});

const addToCart = (product) => {
    cartStore.addToCart(product, 1); // Default quantity 1
    showToast("Added to cart successfully!", "success");
};

// ✅ Toggle Wishlist
const toggleWishlist = (product) => {
    if (wishlistStore.isInWishlist(product.id)) {
        wishlistStore.toggle(product.id);
        showToast("Removed from Wishlist.", "info");
    } else {
        wishlistStore.toggle(product.id);
        showToast("Added to Wishlist!", "success");
    }
};

// Scroll Logic
const scrollToSection = (categoryName, categorySlug) => {
    const sectionId =
        "cat-section-" + categoryName.replace(/\s+/g, "-").toLowerCase();
    const element = document.getElementById(sectionId);
    if (element) {
        const offset = 100;
        const rect = element.getBoundingClientRect();
        const scrollTop =
            window.pageYOffset || document.documentElement.scrollTop;
        window.scrollTo({
            top: rect.top + scrollTop - offset,
            behavior: "smooth",
        });
    } else {
        router.visit(route("products.index", { category: categorySlug }));
    }
};
</script>

<template>
    <Head title="Home" />

    <transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-10 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div
            v-if="toastMessage"
            class="fixed bottom-6 right-6 z-[100] max-w-sm w-full border shadow-[0_10px_40px_-10px_rgba(0,0,0,0.5)] rounded-2xl p-4 flex items-center gap-4 backdrop-blur-xl border-l-4 animate-pulse-slow"
            :class="
                toastType === 'success'
                    ? 'bg-[#1A1F2E] border-green-500/20 border-l-green-500'
                    : 'bg-[#1A1F2E] border-blue-500/20 border-l-blue-500'
            "
        >
            <div
                class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 shadow-inner ring-1"
                :class="
                    toastType === 'success'
                        ? 'bg-green-500/10 ring-green-500/20'
                        : 'bg-blue-500/10 ring-blue-500/20'
                "
            >
                <CheckCircleIcon
                    v-if="toastType === 'success'"
                    class="w-6 h-6 text-green-400"
                />
                <HeartIcon v-else class="w-6 h-6 text-blue-400" />
            </div>

            <div class="flex-1">
                <h4 class="font-bold text-sm tracking-wide text-white">
                    {{ toastType === "success" ? "Success!" : "Updated" }}
                </h4>
                <p
                    class="text-xs mt-0.5 font-medium"
                    :class="
                        toastType === 'success'
                            ? 'text-green-200/70'
                            : 'text-blue-200/70'
                    "
                >
                    {{ toastMessage }}
                </p>
            </div>

            <button
                @click="toastMessage = null"
                class="p-2 hover:bg-white/5 rounded-full text-gray-400 hover:text-white transition"
            >
                <XMarkIcon class="w-4 h-4" />
            </button>
        </div>
    </transition>

    <MainLayout>
        <HeroSlider :slides="slides" />

        <CategoryMenu :categories="categories" />

        <div class="bg-[#0B0F19] py-16 relative overflow-hidden">
            <div
                class="container mx-auto px-4 lg:px-8 relative z-10 space-y-24"
            >
                <div
                    v-for="(group, categoryName) in productsByRootCategory"
                    :key="categoryName"
                    :id="
                        'cat-section-' +
                        categoryName.replace(/\s+/g, '-').toLowerCase()
                    "
                >
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 border-b border-white/10 pb-6 gap-4"
                    >
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <TagIcon class="w-5 h-5 text-indigo-400" />
                                <span
                                    class="text-xs font-bold text-indigo-400 uppercase tracking-widest"
                                    >{{ categoryName }} Collection</span
                                >
                            </div>
                            <h2
                                class="text-4xl font-black text-white tracking-tight mb-4"
                            >
                                {{ categoryName }}
                            </h2>
                        </div>
                        <Link
                            :href="
                                route('products.index', {
                                    category: group.slug,
                                })
                            "
                            class="text-sm font-bold text-white hover:text-indigo-400 transition flex items-center gap-1 group"
                        >
                            {{ __("See All") }}
                            <ArrowRightIcon
                                class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                            />
                        </Link>
                    </div>

                    <div
                        class="flex gap-6 overflow-x-auto no-scrollbar scroll-smooth pb-4 px-1"
                    >
                        <div
                            v-for="product in group.products.slice(0, 8)"
                            :key="product.id"
                            class="min-w-[280px] w-[280px] group relative bg-[#151925] rounded-3xl p-4 border border-white/5 hover:border-indigo-500/50 hover:bg-[#1A1F2E] transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-indigo-500/10 flex flex-col h-full"
                        >
                            <div
                                class="relative h-64 rounded-2xl bg-white overflow-hidden mb-5 flex-shrink-0 group-hover:shadow-inner transition-shadow"
                            >
                                <Link
                                    :href="
                                        route('product.details', product.slug)
                                    "
                                    class="block w-full h-full p-6"
                                >
                                    <img
                                        v-if="product.thumb_image"
                                        :src="'/storage/' + product.thumb_image"
                                        :alt="getLocalizedName(product.name)"
                                        class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700 ease-out"
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
                                        />
                                        <HeartIcon v-else class="w-5 h-5" />
                                    </button>
                                </div>
                            </div>

                            <div class="px-1 flex-1 flex flex-col">
                                <p
                                    class="text-xs text-indigo-400 font-bold uppercase tracking-wider mb-2"
                                >
                                    {{
                                        getLocalizedName(product.category?.name)
                                    }}
                                </p>
                                <Link
                                    :href="
                                        route('product.details', product.slug)
                                    "
                                >
                                    <h3
                                        class="font-bold text-base text-white leading-snug group-hover:text-indigo-400 transition-colors mb-3 line-clamp-2 min-h-[3rem]"
                                        :title="getLocalizedName(product.name)"
                                    >
                                        {{ getLocalizedName(product.name) }}
                                    </h3>
                                </Link>

                                <Link
                                    :href="
                                        route('product.details', product.slug) +
                                        '#reviews'
                                    "
                                    class="flex items-center gap-1 mb-4 cursor-pointer group/rating w-fit"
                                >
                                    <div
                                        class="flex items-center gap-0.5 group-hover/rating:scale-105 transition-transform duration-300"
                                    >
                                        <StarSolidIcon
                                            v-for="i in 5"
                                            :key="i"
                                            class="w-3.5 h-3.5"
                                            :class="
                                                i <=
                                                Math.round(
                                                    product.reviews_avg_rating ||
                                                        0
                                                )
                                                    ? 'text-yellow-400'
                                                    : 'text-gray-600'
                                            "
                                        />
                                    </div>
                                    <span
                                        class="text-xs font-bold text-gray-400 ml-1"
                                        >({{
                                            product.reviews_avg_rating
                                                ? parseFloat(
                                                      product.reviews_avg_rating
                                                  ).toFixed(1)
                                                : "0.0"
                                        }})</span
                                    >
                                </Link>

                                <div
                                    class="mt-auto flex items-center justify-between border-t border-white/5 pt-4"
                                >
                                    <div class="flex flex-col">
                                        <span
                                            v-if="product.discount_price"
                                            class="text-xs text-gray-500 line-through font-medium"
                                            >৳{{
                                                parseInt(product.base_price) +
                                                500
                                            }}</span
                                        >
                                        <span
                                            class="text-xl font-black text-white tracking-tight"
                                            >৳{{ product.base_price }}</span
                                        >
                                    </div>

                                    <button
                                        @click.prevent="addToCart(product)"
                                        class="w-10 h-10 bg-white text-gray-900 rounded-full flex items-center justify-center shadow-lg shadow-white/10 hover:bg-indigo-600 hover:text-white hover:scale-110 hover:rotate-12 transition-all duration-300"
                                    >
                                        <ShoppingCartIcon class="w-5 h-5" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
