<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {
    ShoppingCartIcon,
    StarIcon,
    HeartIcon,
    EyeIcon,
    FunnelIcon,
    Squares2X2Icon,
    ChevronLeftIcon,
    ChevronRightIcon,
    HomeIcon,
} from "@heroicons/vue/24/outline";
import { HeartIcon as HeartSolidIcon } from "@heroicons/vue/24/solid";
import { useCartStore } from "@/Stores/cartStore";
import { useWishlistStore } from "@/Stores/wishlistStore";

const props = defineProps({
    products: Object, // Paginated Object
    categoryName: String,
});

const page = usePage();
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();

const addToCart = (product) => {
    cartStore.addToCart(product);
};
const toggleWishlist = (product) => {
    wishlistStore.toggle(product.id);
};

// ✅ নামের JSON ফরম্যাট ঠিক করার ফাংশন
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
                            Showing {{ products.from }}-{{ products.to }} of
                            {{ products.total }} results
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white hover:bg-white/10 transition text-sm font-bold"
                        >
                            <FunnelIcon class="w-4 h-4" /> {{ __("Filter") }}
                        </button>
                        <button
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white hover:bg-white/10 transition text-sm font-bold"
                        >
                            <Squares2X2Icon class="w-4 h-4" /> {{ __("Sort") }}
                        </button>
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
                            >
                                Sale
                            </span>

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
                                <Link
                                    :href="
                                        route('product.details', product.slug)
                                    "
                                    class="w-9 h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-lg text-gray-500 hover:text-indigo-600 transition-colors"
                                >
                                    <EyeIcon class="w-5 h-5" />
                                </Link>
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
                                        class="w-3.5 h-3.5 fill-current"
                                    />
                                </div>
                                <span class="text-xs font-bold text-gray-400"
                                    >(4.5)</span
                                >
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
                        No products found in this category.
                    </p>
                    <Link
                        href="/"
                        class="mt-4 inline-block text-indigo-400 font-bold hover:underline"
                        >Go Back Home</Link
                    >
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
