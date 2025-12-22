<script setup>
import { ref, computed } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {
    StarIcon,
    ShieldCheckIcon,
    TruckIcon,
    ShoppingCartIcon,
    HeartIcon,
    ShareIcon,
    HomeIcon,
    CheckBadgeIcon,
    MinusIcon,
    PlusIcon,
} from "@heroicons/vue/24/outline";
import { HeartIcon as HeartSolidIcon } from "@heroicons/vue/24/solid";
import { useCartStore } from "@/Stores/cartStore";
import { useWishlistStore } from "@/Stores/wishlistStore";

const props = defineProps({
    product: Object,
});

const page = usePage();
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();

const quantity = ref(1);
const activeImage = ref(props.product.thumb_image);
const selectedVariant = ref(null);

// ✅ JSON Name Helper
const getLocalizedName = (name) => {
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

// Price Calculation
const currentPrice = computed(() => {
    if (selectedVariant.value) return selectedVariant.value.price;
    return props.product.base_price;
});

const addToCart = () => {
    cartStore.addToCart(props.product, quantity.value, selectedVariant.value);
};

const toggleWishlist = () => {
    wishlistStore.toggle(props.product.id);
};
</script>

<template>
    <Head :title="getLocalizedName(product.name)" />

    <MainLayout>
        <div
            class="min-h-screen bg-[#0B0F19] py-12 relative overflow-hidden font-sans text-gray-200"
        >
            <div
                class="absolute top-0 left-0 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[150px] pointer-events-none"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[150px] pointer-events-none"
            ></div>

            <div
                class="container mx-auto px-4 lg:px-8 relative z-10 pb-24 lg:pb-0"
            >
                <div class="flex items-center gap-2 text-sm text-gray-400 mb-8">
                    <Link href="/" class="hover:text-white transition"
                        ><HomeIcon class="w-4 h-4"
                    /></Link>
                    <span>/</span>
                    <span class="text-indigo-400 font-bold">{{
                        getLocalizedName(product.category?.name)
                    }}</span>
                    <span>/</span>
                    <span class="text-gray-500 truncate max-w-[200px]">{{
                        getLocalizedName(product.name)
                    }}</span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <div class="space-y-6">
                        <div
                            class="relative bg-white rounded-3xl p-8 h-[450px] flex items-center justify-center overflow-hidden group shadow-2xl shadow-indigo-900/20 border border-white/5"
                        >
                            <img
                                :src="'/storage/' + activeImage"
                                class="h-full object-contain group-hover:scale-110 transition duration-700 ease-out mix-blend-multiply"
                                :alt="getLocalizedName(product.name)"
                            />

                            <span
                                v-if="product.discount_price"
                                class="absolute top-6 left-6 bg-gradient-to-r from-pink-500 to-rose-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg z-10 uppercase tracking-widest"
                            >
                                Sale
                            </span>

                            <button
                                class="absolute top-6 right-6 p-3 bg-gray-100 hover:bg-gray-200 rounded-full transition text-gray-600"
                            >
                                <ShareIcon class="w-5 h-5" />
                            </button>
                        </div>

                        <div
                            class="flex gap-4 overflow-x-auto pb-2 no-scrollbar"
                        >
                            <button
                                @click="activeImage = product.thumb_image"
                                class="w-20 h-20 rounded-xl p-2 bg-white transition-all duration-300 flex-shrink-0 border-2"
                                :class="
                                    activeImage === product.thumb_image
                                        ? 'border-indigo-500'
                                        : 'border-transparent opacity-70 hover:opacity-100'
                                "
                            >
                                <img
                                    :src="'/storage/' + product.thumb_image"
                                    class="w-full h-full object-contain mix-blend-multiply"
                                />
                            </button>

                            <button
                                v-for="(img, index) in product.gallery_images"
                                :key="index"
                                @click="activeImage = img"
                                class="w-20 h-20 rounded-xl p-2 bg-white transition-all duration-300 flex-shrink-0 border-2"
                                :class="
                                    activeImage === img
                                        ? 'border-indigo-500'
                                        : 'border-transparent opacity-70 hover:opacity-100'
                                "
                            >
                                <img
                                    :src="'/storage/' + img"
                                    class="w-full h-full object-contain mix-blend-multiply"
                                />
                            </button>
                        </div>
                    </div>

                    <div>
                        <h1
                            class="text-3xl lg:text-4xl font-black text-white leading-tight mb-4"
                        >
                            {{ getLocalizedName(product.name) }}
                        </h1>

                        <div class="flex items-center gap-6 mb-8 text-sm">
                            <div
                                class="flex items-center gap-1 bg-yellow-500/10 px-3 py-1 rounded-lg border border-yellow-500/20"
                            >
                                <StarIcon
                                    class="w-4 h-4 text-yellow-400 fill-yellow-400"
                                />
                                <span class="font-bold text-yellow-400"
                                    >4.8</span
                                >
                                <span class="text-gray-400 ml-1"
                                    >(120 Reviews)</span
                                >
                            </div>
                            <div
                                class="flex items-center gap-1 text-green-400 bg-green-500/10 px-3 py-1 rounded-lg border border-green-500/20"
                            >
                                <CheckBadgeIcon class="w-4 h-4" />
                                <span class="font-bold">In Stock</span>
                            </div>
                        </div>

                        <div class="mb-8">
                            <div class="flex items-end gap-3">
                                <span
                                    class="text-4xl font-black text-white tracking-tight"
                                    >৳{{ currentPrice }}</span
                                >
                                <span
                                    v-if="product.discount_price"
                                    class="text-xl text-gray-500 line-through mb-1"
                                >
                                    ৳{{ parseInt(product.base_price) + 500 }}
                                </span>
                            </div>
                            <p class="text-sm text-indigo-400 mt-2 font-medium">
                                Inclusive of all taxes
                            </p>
                        </div>

                        <div v-if="product.has_variants" class="mb-8">
                            <h3
                                class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3"
                            >
                                Select Option
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                <button
                                    v-for="variant in product.variants"
                                    :key="variant.id"
                                    @click="selectedVariant = variant"
                                    class="px-4 py-2.5 rounded-lg text-sm font-bold transition-all border"
                                    :class="
                                        selectedVariant?.id === variant.id
                                            ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-500/30'
                                            : 'bg-white/5 border-white/10 text-gray-300 hover:border-white/30'
                                    "
                                >
                                    <span
                                        v-for="(val, key) in variant.attributes"
                                        :key="key"
                                        >{{ val }}
                                    </span>
                                    <span class="opacity-60 text-xs ml-1"
                                        >৳{{ variant.price }}</span
                                    >
                                </button>
                            </div>
                        </div>

                        <div class="hidden lg:flex flex-row gap-4 mb-8">
                            <div
                                class="flex items-center bg-white/5 border border-white/10 rounded-xl p-1 w-fit"
                            >
                                <button
                                    @click="quantity > 1 ? quantity-- : null"
                                    class="w-10 h-10 flex items-center justify-center text-gray-400 hover:bg-white/10 hover:text-white rounded-lg transition"
                                >
                                    <MinusIcon class="w-4 h-4" />
                                </button>
                                <span
                                    class="w-12 text-center font-bold text-white"
                                    >{{ quantity }}</span
                                >
                                <button
                                    @click="quantity++"
                                    class="w-10 h-10 flex items-center justify-center text-gray-400 hover:bg-white/10 hover:text-white rounded-lg transition"
                                >
                                    <PlusIcon class="w-4 h-4" />
                                </button>
                            </div>

                            <button
                                @click="addToCart"
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-indigo-500/30 flex items-center justify-center gap-2"
                            >
                                <ShoppingCartIcon class="w-5 h-5" />
                                {{ __("Add to Cart") }}
                            </button>

                            <button
                                @click="toggleWishlist"
                                class="p-3.5 bg-white/5 border border-white/10 hover:bg-white/10 rounded-xl transition text-gray-400 hover:text-rose-500"
                            >
                                <HeartSolidIcon
                                    v-if="
                                        wishlistStore.isInWishlist(product.id)
                                    "
                                    class="w-6 h-6 text-rose-500"
                                />
                                <HeartIcon v-else class="w-6 h-6" />
                            </button>
                        </div>

                        <div
                            class="grid grid-cols-2 gap-4 text-sm text-gray-400 border-t border-white/10 pt-6"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="bg-indigo-500/10 p-2 rounded-lg text-indigo-400"
                                >
                                    <TruckIcon class="w-5 h-5" />
                                </div>
                                <span>Free Delivery over ৳5000</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="bg-green-500/10 p-2 rounded-lg text-green-400"
                                >
                                    <ShieldCheckIcon class="w-5 h-5" />
                                </div>
                                <span>1 Year Official Warranty</span>
                            </div>
                        </div>

                        <div
                            class="mt-8 bg-white/5 border border-white/10 p-4 rounded-xl flex items-center justify-between group hover:border-indigo-500/30 transition"
                        >
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg"
                                >
                                    {{ product.vendor?.shop_name.charAt(0) }}
                                </div>
                                <div>
                                    <p
                                        class="text-xs text-gray-500 uppercase font-bold tracking-wider"
                                    >
                                        Sold by
                                    </p>
                                    <p
                                        class="font-bold text-white group-hover:text-indigo-400 transition"
                                    >
                                        {{ product.vendor?.shop_name }}
                                    </p>
                                </div>
                            </div>
                            <button
                                class="text-sm font-bold text-indigo-400 hover:text-white transition"
                            >
                                Visit Store &rarr;
                            </button>
                        </div>

                        <div class="mt-10">
                            <h3
                                class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2"
                            >
                                Description
                            </h3>
                            <div
                                class="prose prose-invert text-gray-400 text-sm leading-relaxed"
                                v-html="product.description"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="fixed bottom-0 left-0 w-full bg-[#0B0F19]/90 backdrop-blur-xl border-t border-white/10 p-4 lg:hidden z-50 flex gap-3 animate-fade-in-up"
            >
                <div class="flex flex-col justify-center">
                    <span class="text-xs text-gray-400">Total Price</span>
                    <span class="text-lg font-bold text-white"
                        >৳{{ currentPrice * quantity }}</span
                    >
                </div>
                <button
                    @click="addToCart"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg flex items-center justify-center gap-2"
                >
                    {{ __("Add to Cart") }}
                </button>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
/* Hide scrollbar for thumbnails */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(100%);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
