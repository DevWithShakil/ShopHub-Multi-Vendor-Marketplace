<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {
    ShoppingCartIcon,
    TrashIcon,
    HeartIcon,
    ArrowRightIcon,
} from "@heroicons/vue/24/outline";
import { useCartStore } from "@/Stores/cartStore";
import { useWishlistStore } from "@/Stores/wishlistStore";
import { ref } from "vue";

const props = defineProps({
    products: Array,
});

const page = usePage();
const wishlistItems = ref(props.products);
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();

const addToCart = (product) => {
    cartStore.addToCart(product);
};

const removeItem = (productId) => {
    wishlistStore.toggle(productId);
    wishlistItems.value = wishlistItems.value.filter(
        (item) => item.id !== productId
    );
};

// ✅ নামের JSON ফরম্যাট ঠিক করার ফাংশন
const getLocalizedName = (name) => {
    try {
        // যদি নাম স্ট্রিং হয় এবং { দিয়ে শুরু হয়, তাহলে এটি JSON
        const parsed =
            typeof name === "string" && name.startsWith("{")
                ? JSON.parse(name)
                : name;

        // যদি এটি অবজেক্ট হয় (যেমন: {en: "Name", bn: "নাম"})
        if (typeof parsed === "object" && parsed !== null) {
            // বর্তমান ভাষা অনুযায়ী নাম দেখাবে, না পেলে ইংলিশ
            const locale = page.props.locale || "en";
            return parsed[locale] || parsed["en"] || Object.values(parsed)[0];
        }
        return name;
    } catch (e) {
        return name; // কোনো সমস্যা হলে যা আছে তাই দেখাবে
    }
};
</script>

<template>
    <Head title="My Wishlist" />

    <MainLayout>
        <div class="min-h-screen bg-gray-50 py-12">
            <div class="container mx-auto px-4">
                <div
                    class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8"
                >
                    <div>
                        <h1
                            class="text-3xl font-bold text-gray-900 flex items-center gap-3"
                        >
                            <HeartIcon
                                class="w-8 h-8 text-red-500 fill-red-500"
                            />
                            {{ __("My Wishlist") }}
                        </h1>
                        <p class="text-gray-500 mt-1 text-sm">
                            {{ __("You have") }}
                            <span class="font-bold text-indigo-600">{{
                                wishlistItems.length
                            }}</span>
                            {{ __("items saved for later") }}
                        </p>
                    </div>

                    <Link
                        href="/"
                        class="flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition"
                    >
                        {{ __("Continue Shopping") }}
                        <ArrowRightIcon class="w-4 h-4" />
                    </Link>
                </div>

                <div
                    v-if="wishlistItems.length > 0"
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="product in wishlistItems"
                        :key="product.id"
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-lg transition-all duration-300 relative"
                    >
                        <button
                            @click="removeItem(product.id)"
                            class="absolute top-3 right-3 p-2 bg-white/90 backdrop-blur rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 transition shadow-sm z-10"
                            :title="__('Remove from Wishlist')"
                        >
                            <TrashIcon class="w-5 h-5" />
                        </button>

                        <Link
                            :href="route('product.details', product.slug)"
                            class="block relative h-56 bg-gray-100 overflow-hidden"
                        >
                            <img
                                v-if="product.thumb_image"
                                :src="'/storage/' + product.thumb_image"
                                :alt="getLocalizedName(product.name)"
                                class="w-full h-full object-contain p-4 group-hover:scale-110 transition-transform duration-500 mix-blend-multiply"
                            />
                            <div
                                v-else
                                class="w-full h-full flex items-center justify-center text-gray-300"
                            >
                                No Image
                            </div>

                            <span
                                v-if="product.discount_price"
                                class="absolute top-3 left-3 bg-rose-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm"
                            >
                                Sale
                            </span>
                        </Link>

                        <div class="p-5">
                            <div class="mb-3">
                                <p
                                    class="text-xs text-gray-500 mb-1 uppercase tracking-wider"
                                >
                                    {{ product.category?.name || "General" }}
                                </p>

                                <Link
                                    :href="
                                        route('product.details', product.slug)
                                    "
                                >
                                    <h3
                                        class="font-bold text-gray-800 text-lg leading-tight truncate hover:text-indigo-600 transition"
                                    >
                                        {{ getLocalizedName(product.name) }}
                                    </h3>
                                </Link>
                            </div>

                            <div class="flex justify-between items-end mb-4">
                                <div class="flex flex-col">
                                    <span
                                        v-if="product.discount_price"
                                        class="text-xs text-gray-400 line-through font-medium"
                                    >
                                        ৳{{
                                            parseInt(product.base_price) + 500
                                        }}
                                    </span>
                                    <span
                                        class="text-xl font-black text-indigo-900"
                                    >
                                        ৳{{ product.base_price }}
                                    </span>
                                </div>
                                <span
                                    class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full"
                                >
                                    In Stock
                                </span>
                            </div>

                            <button
                                @click="addToCart(product)"
                                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl font-bold transition-all duration-200 bg-gray-900 text-white hover:bg-indigo-600 shadow-md hover:shadow-indigo-200 active:scale-95"
                            >
                                <ShoppingCartIcon class="w-5 h-5" />
                                {{ __("Add to Cart") }}
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    v-else
                    class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200"
                >
                    <div
                        class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6"
                    >
                        <HeartIcon class="w-12 h-12 text-gray-300" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        {{ __("Your wishlist is empty") }}
                    </h2>
                    <p class="text-gray-500 mb-8 max-w-md text-center">
                        {{
                            __(
                                "Looks like you haven't added anything to your wishlist yet."
                            )
                        }}
                    </p>
                    <Link
                        href="/"
                        class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200"
                    >
                        {{ __("Start Shopping") }}
                    </Link>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
