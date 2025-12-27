<script setup>
import { computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { ShoppingCartIcon, FireIcon, BoltIcon } from "@heroicons/vue/24/solid";
import { HeartIcon as HeartSolidIcon } from "@heroicons/vue/24/solid";
import { HeartIcon as HeartOutline } from "@heroicons/vue/24/outline";
import { useCartStore } from "@/Stores/cartStore";
import { useWishlistStore } from "@/Stores/wishlistStore";

const props = defineProps({
    product: Object,
});

const page = usePage();
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();

// --- Logic ---

// 1. Localized Name Helper
const getName = (nameField) => {
    try {
        if (typeof nameField === "object") {
            return nameField[page.props.locale] || nameField["en"] || "Product";
        }
        if (typeof nameField === "string" && nameField.startsWith("{")) {
            const parsed = JSON.parse(nameField);
            return parsed[page.props.locale] || parsed["en"] || "Product";
        }
        return nameField;
    } catch (e) {
        return nameField;
    }
};

// 2. Price Logic
const priceData = computed(() => {
    const base = Number(props.product.base_price);
    const sale = props.product.pivot?.discount_price
        ? Number(props.product.pivot.discount_price)
        : props.product.discount_price
        ? Number(props.product.discount_price)
        : base;

    const discountPercent =
        base > sale ? Math.round(((base - sale) / base) * 100) : 0;

    return { base, sale, discountPercent };
});

// 3. Stock Logic
const stockData = computed(() => {
    const sold = props.product.pivot?.sold || 0;
    const limit = props.product.pivot?.stock_limit || 10;
    const percent = Math.min(Math.round((sold / limit) * 100), 100);
    const left = Math.max(limit - sold, 0);
    return { sold, limit, percent, left };
});

// 4. Image Logic
const imageUrl = computed(() => {
    if (!props.product.thumb_image) return "https://placehold.co/400x400";
    return props.product.thumb_image.startsWith("http")
        ? props.product.thumb_image
        : `/storage/${props.product.thumb_image}`;
});

// --- Actions (Matches ProductCard.vue Logic) ---

const addToCart = () => {
    cartStore.addToCart({
        id: props.product.id,
        name: getName(props.product.name),
        price: priceData.value.sale,
        image: imageUrl.value,
        vendor_id: props.product.vendor_id,
        quantity: 1,
    });
    // টোস্ট স্টোর বা লেআউট থেকে হ্যান্ডেল হবে
};

const toggleWishlist = () => {
    wishlistStore.toggle(props.product.id);
    // টোস্ট স্টোর বা লেআউট থেকে হ্যান্ডেল হবে
};
</script>

<template>
    <div
        class="group relative bg-[#1A1F2E] border border-orange-500/20 rounded-2xl overflow-hidden hover:border-orange-500/60 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-orange-900/20 flex flex-col h-full"
    >
        <div class="absolute top-0 left-0 z-20">
            <div
                class="bg-gradient-to-r from-orange-600 to-red-600 text-white text-[10px] font-bold px-3 py-1 rounded-br-xl shadow-lg flex items-center gap-1"
            >
                <BoltIcon class="w-3 h-3 animate-pulse" /> FLASH DEAL
            </div>
        </div>

        <div
            v-if="priceData.discountPercent > 0"
            class="absolute top-3 right-3 z-20"
        >
            <div
                class="bg-red-500/90 backdrop-blur text-white text-xs font-bold w-12 h-12 flex flex-col items-center justify-center rounded-full border-2 border-white/10 shadow-lg"
            >
                <span>{{ priceData.discountPercent }}%</span>
                <span class="text-[8px] uppercase">Off</span>
            </div>
        </div>

        <div class="relative h-60 bg-white p-4 overflow-hidden">
            <Link
                :href="route('product.details', product.slug)"
                class="block w-full h-full"
            >
                <img
                    :src="imageUrl"
                    class="w-full h-full object-contain transition-transform duration-700 group-hover:scale-110 ease-out"
                    alt="Product Image"
                />
            </Link>

            <div
                class="absolute bottom-3 right-3 flex flex-col gap-2 translate-x-12 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300"
            >
                <button
                    @click.prevent="toggleWishlist"
                    class="w-10 h-10 bg-white text-gray-600 rounded-full flex items-center justify-center shadow-lg hover:bg-red-50 hover:text-red-600 transition-colors"
                    title="Wishlist"
                >
                    <HeartSolidIcon
                        v-if="wishlistStore.isInWishlist(product.id)"
                        class="w-6 h-6 text-red-500 animate-pulse"
                    />
                    <HeartOutline v-else class="w-6 h-6" />
                </button>
            </div>
        </div>

        <div class="p-4 flex-1 flex flex-col">
            <Link :href="route('product.details', product.slug)">
                <h3
                    class="text-white font-bold text-sm leading-snug line-clamp-2 mb-2 group-hover:text-orange-400 transition-colors min-h-[2.5rem]"
                    :title="getName(product.name)"
                >
                    {{ getName(product.name) }}
                </h3>
            </Link>

            <div class="flex items-center gap-2 mb-3">
                <span class="text-xl font-black text-white"
                    >৳{{ priceData.sale }}</span
                >
                <span
                    v-if="priceData.discountPercent > 0"
                    class="text-xs text-gray-500 line-through decoration-gray-500"
                    >৳{{ priceData.base }}</span
                >
            </div>

            <div class="mb-4">
                <div
                    class="flex justify-between text-[10px] font-bold uppercase mb-1"
                >
                    <span class="text-orange-400 flex items-center gap-1">
                        <FireIcon class="w-3 h-3" /> {{ stockData.left }} Left
                    </span>
                    <span class="text-gray-500"
                        >{{ stockData.percent }}% Sold</span
                    >
                </div>
                <div
                    class="w-full h-2 bg-gray-700 rounded-full overflow-hidden"
                >
                    <div
                        class="h-full bg-gradient-to-r from-orange-500 to-red-600 rounded-full relative"
                        :style="`width: ${stockData.percent}%`"
                    >
                        <div
                            class="absolute inset-0 bg-white/30 w-full animate-[shimmer_2s_infinite]"
                        ></div>
                    </div>
                </div>
            </div>

            <button
                @click.prevent="addToCart"
                class="w-full mt-auto py-2.5 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-500 hover:to-red-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-orange-900/20 flex items-center justify-center gap-2 transition-all active:scale-95"
            >
                <ShoppingCartIcon class="w-5 h-5" /> Add to Cart
            </button>
        </div>
    </div>
</template>

<style scoped>
@keyframes shimmer {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}
</style>
