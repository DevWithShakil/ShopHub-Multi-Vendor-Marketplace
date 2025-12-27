<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { ShoppingCartIcon, HeartIcon } from "@heroicons/vue/24/outline";
import {
    HeartIcon as HeartSolidIcon,
    StarIcon as StarSolidIcon,
} from "@heroicons/vue/24/solid";
import { useCartStore } from "@/Stores/cartStore";
import { useWishlistStore } from "@/Stores/wishlistStore";
import { ref } from "vue";

const props = defineProps({
    product: Object,
    badge: String, // 'New', 'Hot', etc.
    badgeColor: String, // Tailwind gradient class
});

const page = usePage();
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();

// ✅ FIX: Robust Localized Name Helper
const getLocalizedName = (nameField) => {
    if (!nameField) return "General";

    try {
        // Case 1: nameField is already an object (Laravel casted it)
        if (typeof nameField === "object") {
            return nameField[page.props.locale] || nameField["en"] || "Unknown";
        }

        // Case 2: nameField is a JSON string
        if (typeof nameField === "string" && nameField.startsWith("{")) {
            const parsed = JSON.parse(nameField);
            return parsed[page.props.locale] || parsed["en"] || "Unknown";
        }

        // Case 3: nameField is a simple string
        return nameField;
    } catch (e) {
        return nameField; // Fallback to raw string if parsing fails
    }
};

const addToCart = () => {
    cartStore.addToCart(props.product, 1);
};
</script>

<template>
    <div
        class="min-w-[280px] w-[280px] group relative bg-[#151925] rounded-3xl p-4 border border-white/5 hover:border-indigo-500/50 hover:bg-[#1A1F2E] transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-indigo-500/10 flex flex-col h-full"
    >
        <div
            class="relative h-64 rounded-2xl bg-white overflow-hidden mb-5 flex-shrink-0 group-hover:shadow-inner transition-shadow"
        >
            <Link
                :href="route('product.details', product.slug)"
                class="block w-full h-full p-6"
            >
                <img
                    v-if="product.thumb_image"
                    :src="'/storage/' + product.thumb_image"
                    class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700 ease-out mix-blend-multiply"
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

            <span
                v-if="badge"
                :class="badgeColor"
                class="absolute top-3 left-16 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg z-10"
                >{{ badge }}</span
            >

            <div
                class="absolute top-3 right-3 flex flex-col gap-2 translate-x-12 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300"
            >
                <button
                    @click.prevent="wishlistStore.toggle(product.id)"
                    class="w-9 h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-lg text-gray-500 hover:text-red-500 transition-colors"
                >
                    <HeartSolidIcon
                        v-if="wishlistStore.isInWishlist(product.id)"
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
                {{ getLocalizedName(product.category?.name) }}
            </p>

            <Link :href="route('product.details', product.slug)">
                <h3
                    class="font-bold text-base text-white leading-snug group-hover:text-indigo-400 transition-colors mb-3 line-clamp-2 min-h-[3rem]"
                    :title="getLocalizedName(product.name)"
                >
                    {{ getLocalizedName(product.name) }}
                </h3>
            </Link>

            <div class="flex items-center gap-1 mb-4">
                <div class="flex items-center gap-0.5">
                    <StarSolidIcon
                        v-for="i in 5"
                        :key="i"
                        class="w-3.5 h-3.5"
                        :class="
                            i <= Math.round(product.reviews_avg_rating || 0)
                                ? 'text-yellow-400'
                                : 'text-gray-600'
                        "
                    />
                </div>
                <span class="text-xs font-bold text-gray-400 ml-1"
                    >({{ product.reviews_count || 0 }})</span
                >
            </div>

            <div
                class="mt-auto flex items-center justify-between border-t border-white/5 pt-4"
            >
                <div class="flex flex-col">
                    <span
                        v-if="product.discount_price"
                        class="text-xs text-gray-500 line-through font-medium"
                        >৳{{ parseInt(product.base_price) + 500 }}</span
                    >
                    <span class="text-xl font-black text-white tracking-tight"
                        >৳{{ product.base_price }}</span
                    >
                </div>
                <button
                    @click.prevent="addToCart"
                    class="w-10 h-10 bg-white text-gray-900 rounded-full flex items-center justify-center shadow-lg shadow-white/10 hover:bg-indigo-600 hover:text-white hover:scale-110 hover:rotate-12 transition-all duration-300"
                >
                    <ShoppingCartIcon class="w-5 h-5" />
                </button>
            </div>
        </div>
    </div>
</template>
