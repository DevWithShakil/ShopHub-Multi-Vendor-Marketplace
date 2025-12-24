<script setup>
import { ref, computed } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {
    StarIcon as StarOutlineIcon, // Empty Star এর জন্য
    ShieldCheckIcon,
    TruckIcon,
    ShoppingCartIcon,
    HeartIcon,
    ShareIcon,
    HomeIcon,
    CheckBadgeIcon,
    MinusIcon,
    PlusIcon,
    ChatBubbleLeftRightIcon,
    ArrowRightIcon,
    CheckCircleIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import {
    HeartIcon as HeartSolidIcon,
    StarIcon as StarSolidIcon, // Filled Star এর জন্য
} from "@heroicons/vue/24/solid";
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
const toastMessage = ref(null);
const toastType = ref("success");

const showToast = (message, type = "success") => {
    toastMessage.value = message;
    toastType.value = type;
    setTimeout(() => {
        toastMessage.value = null;
    }, 3000);
};

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

const currentPrice = computed(() => {
    if (selectedVariant.value) return selectedVariant.value.price;
    return props.product.base_price;
});

const addToCart = () => {
    cartStore.addToCart(props.product, quantity.value, selectedVariant.value);
    showToast(`Added ${quantity.value} item(s) to cart!`, "success");
};

const toggleWishlist = (product) => {
    if (wishlistStore.isInWishlist(product.id)) {
        wishlistStore.toggle(product.id);
        showToast("Removed from Wishlist.", "info");
    } else {
        wishlistStore.toggle(product.id);
        showToast("Added to Wishlist!", "success");
    }
};

const scrollToReviews = () => {
    const element = document.getElementById("reviews");
    if (element) {
        element.scrollIntoView({ behavior: "smooth", block: "start" });
    }
};
</script>

<template>
    <Head :title="getLocalizedName(product.name)" />

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

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-20">
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
                                >Sale</span
                            >
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
                                @click="scrollToReviews"
                                class="flex items-center gap-1 mb-4 cursor-pointer group hover:opacity-80 transition-opacity"
                            >
                                <div class="flex">
                                    <component
                                        :is="
                                            i <=
                                            Math.round(
                                                product.reviews_avg_rating || 0
                                            )
                                                ? StarSolidIcon
                                                : StarSolidIcon
                                        "
                                        v-for="i in 5"
                                        :key="i"
                                        class="w-4 h-4"
                                        :class="
                                            i <=
                                            Math.round(
                                                product.reviews_avg_rating || 0
                                            )
                                                ? 'text-yellow-400'
                                                : 'text-gray-700'
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
                                <span
                                    class="text-[10px] text-gray-500 ml-1 group-hover:text-indigo-400 group-hover:underline"
                                >
                                    | {{ product.reviews_count || 0 }} Reviews
                                </span>
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
                                    >৳{{
                                        parseInt(product.base_price) + 500
                                    }}</span
                                >
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
                                @click="toggleWishlist(product)"
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

                <div id="reviews" class="mt-16 border-t border-white/10 pt-12">
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6"
                    >
                        <div>
                            <h2
                                class="text-3xl font-black text-white tracking-tight mb-2"
                            >
                                Customer Reviews
                            </h2>
                            <div class="flex items-center gap-3">
                                <div class="flex">
                                    <StarSolidIcon
                                        v-for="i in 5"
                                        :key="i"
                                        class="w-5 h-5"
                                        :class="
                                            i <=
                                            Math.round(
                                                product.reviews_avg_rating || 0
                                            )
                                                ? 'text-yellow-400'
                                                : 'text-gray-700'
                                        "
                                    />
                                </div>
                                <span class="text-gray-300 font-bold"
                                    >Based on
                                    {{ product.reviews_count || 0 }}
                                    reviews</span
                                >
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div
                            v-for="review in product.reviews"
                            :key="review.id"
                            class="bg-white/5 border border-white/10 rounded-[2rem] p-6 backdrop-blur-md hover:bg-white/10 transition-all duration-300"
                        >
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-indigo-500/20"
                                    >
                                        {{
                                            review.user.name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}
                                    </div>
                                    <div>
                                        <h4
                                            class="text-white font-bold text-sm"
                                        >
                                            {{ review.user.name }}
                                        </h4>
                                        <div class="flex mt-0.5">
                                            <StarSolidIcon
                                                v-for="i in 5"
                                                :key="i"
                                                class="w-3 h-3"
                                                :class="
                                                    i <= review.rating
                                                        ? 'text-yellow-400'
                                                        : 'text-gray-700'
                                                "
                                            />
                                        </div>
                                    </div>
                                </div>
                                <span
                                    class="text-[10px] text-gray-500 font-bold bg-white/5 px-2 py-1 rounded"
                                    >{{
                                        new Date(
                                            review.created_at
                                        ).toLocaleDateString("en-GB", {
                                            day: "numeric",
                                            month: "short",
                                            year: "numeric",
                                        })
                                    }}</span
                                >
                            </div>
                            <p
                                class="text-gray-300 text-sm leading-relaxed pl-14 italic"
                            >
                                "{{ review.comment }}"
                            </p>
                        </div>

                        <div
                            v-if="
                                !product.reviews || product.reviews.length === 0
                            "
                            class="col-span-full text-center py-12 bg-white/5 rounded-3xl border border-dashed border-white/10"
                        >
                            <ChatBubbleLeftRightIcon
                                class="w-12 h-12 text-gray-600 mx-auto mb-4"
                            />
                            <p class="text-gray-400 font-bold">
                                No reviews yet.
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Be the first to share your experience!
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    v-if="
                        product.related_products &&
                        product.related_products.length > 0
                    "
                    class="mt-24 border-t border-white/10 pt-16"
                >
                    <div class="flex justify-between items-end mb-8">
                        <h2
                            class="text-3xl lg:text-4xl font-black text-white tracking-tight"
                        >
                            You Might Also Like
                        </h2>
                    </div>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
                    >
                        <Link
                            v-for="related in product.related_products"
                            :key="related.id"
                            :href="route('product.details', related.slug)"
                            class="group bg-[#151925] border border-white/5 rounded-3xl p-4 hover:border-indigo-500/30 hover:bg-[#1A1F2E] transition-all duration-500 flex flex-col h-full"
                        >
                            <div
                                class="relative h-64 rounded-2xl bg-white overflow-hidden mb-5 flex-shrink-0"
                            >
                                <div class="block w-full h-full p-6">
                                    <img
                                        v-if="related.thumb_image"
                                        :src="'/storage/' + related.thumb_image"
                                        class="w-full h-full object-contain"
                                    />
                                    <div
                                        v-else
                                        class="w-full h-full flex items-center justify-center text-gray-300"
                                    >
                                        No Image
                                    </div>
                                </div>
                            </div>
                            <h3
                                class="font-bold text-base text-white group-hover:text-indigo-400 transition-colors mb-2"
                            >
                                {{ getLocalizedName(related.name) }}
                            </h3>

                            <div class="flex items-center gap-1 mb-4">
                                <div class="flex">
                                    <StarSolidIcon
                                        v-for="i in 5"
                                        :key="i"
                                        class="w-3 h-3"
                                        :class="
                                            i <=
                                            Math.round(
                                                related.reviews_avg_rating || 0
                                            )
                                                ? 'text-yellow-400'
                                                : 'text-gray-700'
                                        "
                                    />
                                </div>
                                <span class="text-[10px] text-gray-500 ml-1"
                                    >({{ related.reviews_count || 0 }})</span
                                >
                            </div>

                            <div
                                class="mt-auto flex items-center justify-between border-t border-white/5 pt-4"
                            >
                                <span class="text-lg font-black text-white"
                                    >৳{{ related.base_price }}</span
                                >
                            </div>
                        </Link>
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
