<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {
    ShoppingCartIcon,
    TrashIcon,
    HeartIcon,
    ArrowRightIcon,
    CheckCircleIcon,
    XMarkIcon,
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

// --- 🔔 Toast Logic ---
const toastMessage = ref(null);
const toastType = ref("success");

const showToast = (message, type = "success") => {
    toastMessage.value = message;
    toastType.value = type;
    setTimeout(() => {
        toastMessage.value = null;
    }, 3000);
};

// --- Actions ---
const addToCart = (product) => {
    cartStore.addToCart(product);
    showToast("Moved to Cart!", "success");
};

const removeItem = (productId) => {
    wishlistStore.toggle(productId);
    wishlistItems.value = wishlistItems.value.filter(
        (item) => item.id !== productId
    );
    showToast("Removed from Wishlist", "info");
};

// --- Helper ---
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
</script>

<template>
    <Head title="My Wishlist" />

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
            class="fixed bottom-6 right-6 z-[100] max-w-sm w-full border shadow-2xl rounded-2xl p-4 flex items-center gap-4 backdrop-blur-xl border-l-4"
            :class="
                toastType === 'success'
                    ? 'bg-[#1A1F2E] border-green-500/20 border-l-green-500'
                    : 'bg-[#1A1F2E] border-blue-500/20 border-l-blue-500'
            "
        >
            <div
                class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                :class="
                    toastType === 'success'
                        ? 'bg-green-500/10'
                        : 'bg-blue-500/10'
                "
            >
                <CheckCircleIcon
                    v-if="toastType === 'success'"
                    class="w-6 h-6 text-green-400"
                />
                <HeartIcon v-else class="w-6 h-6 text-blue-400" />
            </div>
            <div class="flex-1 text-white">
                <h4 class="font-bold text-sm">
                    {{ toastType === "success" ? "Success!" : "Updated" }}
                </h4>
                <p class="text-xs text-gray-400">{{ toastMessage }}</p>
            </div>
            <button
                @click="toastMessage = null"
                class="p-2 text-gray-400 hover:text-white"
            >
                <XMarkIcon class="w-4 h-4" />
            </button>
        </div>
    </transition>

    <MainLayout>
        <div class="min-h-screen bg-[#0B0F19] py-12 relative overflow-hidden">
            <div
                class="absolute top-0 left-0 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[150px] pointer-events-none"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[150px] pointer-events-none"
            ></div>

            <div class="container mx-auto px-4 lg:px-8 relative z-10">
                <div
                    class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12 border-b border-white/10 pb-6"
                >
                    <div>
                        <h1
                            class="text-3xl md:text-4xl font-black text-white flex items-center gap-3 tracking-tight"
                        >
                            <HeartIcon class="w-8 h-8 text-rose-500" />
                            {{ __("My Wishlist") }}
                        </h1>
                        <p class="text-gray-400 mt-2 text-sm">
                            {{ __("You have") }}
                            <span class="font-bold text-indigo-400">{{
                                wishlistItems.length
                            }}</span>
                            {{ __("items saved for later") }}
                        </p>
                    </div>

                    <Link
                        href="/"
                        class="group flex items-center gap-2 text-sm font-bold text-white bg-white/5 border border-white/10 px-5 py-2.5 rounded-xl hover:bg-white/10 hover:border-indigo-500/50 transition-all"
                    >
                        {{ __("Continue Shopping") }}
                        <ArrowRightIcon
                            class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                        />
                    </Link>
                </div>

                <div
                    v-if="wishlistItems.length > 0"
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="product in wishlistItems"
                        :key="product.id"
                        class="group relative bg-[#151925] border border-white/5 rounded-[2rem] overflow-hidden hover:border-indigo-500/30 hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500 hover:-translate-y-2"
                    >
                        <button
                            @click="removeItem(product.id)"
                            class="absolute top-3 right-3 z-20 w-9 h-9 bg-black/50 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-black/70 transition border border-white/10"
                            :title="__('Remove')"
                        >
                            <TrashIcon class="w-5 h-5" />
                        </button>

                        <Link
                            :href="route('product.details', product.slug)"
                            class="block relative h-60 bg-white p-6 overflow-hidden"
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

                            <span
                                v-if="product.discount_price"
                                class="absolute top-3 left-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg z-10"
                            >
                                Sale
                            </span>
                        </Link>

                        <div class="p-5">
                            <p
                                class="text-xs text-indigo-400 font-bold uppercase tracking-wider mb-2"
                            >
                                {{ product.category?.name || "General" }}
                            </p>

                            <Link
                                :href="route('product.details', product.slug)"
                            >
                                <h3
                                    class="font-bold text-white text-lg leading-snug truncate group-hover:text-indigo-400 transition mb-4"
                                >
                                    {{ getLocalizedName(product.name) }}
                                </h3>
                            </Link>

                            <div class="flex items-center justify-between mb-6">
                                <div class="flex flex-col">
                                    <span
                                        v-if="product.discount_price"
                                        class="text-xs text-gray-500 line-through font-medium"
                                    >
                                        ৳{{
                                            parseInt(product.base_price) + 500
                                        }}
                                    </span>
                                    <span
                                        class="text-xl font-black text-white tracking-tight"
                                    >
                                        ৳{{ product.base_price }}
                                    </span>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-green-400 bg-green-500/10 px-2 py-1 rounded border border-green-500/20"
                                >
                                    In Stock
                                </span>
                            </div>

                            <button
                                @click="addToCart(product)"
                                class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold transition-all bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-500/20 group-hover:shadow-indigo-500/40"
                            >
                                <ShoppingCartIcon class="w-5 h-5" />
                                {{ __("Move to Cart") }}
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    v-else
                    class="flex flex-col items-center justify-center py-24 bg-[#151925] rounded-[3rem] border border-dashed border-white/10"
                >
                    <div
                        class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mb-6 animate-pulse"
                    >
                        <HeartIcon class="w-10 h-10 text-gray-500" />
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">
                        {{ __("Your wishlist is empty") }}
                    </h2>
                    <p class="text-gray-400 mb-8 max-w-md text-center text-sm">
                        {{
                            __(
                                "Looks like you haven't added anything to your wishlist yet. Go ahead and explore top categories."
                            )
                        }}
                    </p>
                    <Link
                        href="/"
                        class="bg-indigo-600 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30 flex items-center gap-2"
                    >
                        <ArrowRightIcon class="w-5 h-5" />
                        {{ __("Start Shopping") }}
                    </Link>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
