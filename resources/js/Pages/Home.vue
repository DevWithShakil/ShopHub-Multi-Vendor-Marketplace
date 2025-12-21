<script setup>
import { Head, Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {
    ShoppingCartIcon,
    StarIcon,
    ArrowRightIcon,
    HeartIcon,
    SparklesIcon,
    TruckIcon,
    ShieldCheckIcon,
    ChevronRightIcon,
    ChevronLeftIcon,
    EyeIcon,
    FireIcon,
} from "@heroicons/vue/24/outline";
import { HeartIcon as HeartSolidIcon } from "@heroicons/vue/24/solid";
import { useCartStore } from "@/Stores/cartStore";
import { useWishlistStore } from "@/Stores/wishlistStore";
import { ref, onMounted, onUnmounted } from "vue";

defineProps({
    products: Array,
});

const cartStore = useCartStore();
const wishlistStore = useWishlistStore();

// --- 🌟 Slider Logic (Same as before) ---
const currentSlide = ref(0);
const slides = [
    {
        id: 1,
        tag: "New Arrival",
        title: "Next Gen",
        highlight: "Headphones",
        desc: "Experience sound like never before. Noise cancellation meets premium comfort.",
        bgClass: "from-slate-900 via-purple-900 to-slate-900",
        btnClass: "bg-purple-500 hover:bg-purple-600",
        image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=2070&auto=format&fit=crop",
        floatingText: "Immersive Sound",
        price: "৳25,000",
    },
    {
        id: 2,
        tag: "Summer Collection",
        title: "Street",
        highlight: "Fashion",
        desc: "Upgrade your wardrobe with the coolest trends of the season. 100% Cotton.",
        bgClass: "from-orange-900 via-red-900 to-slate-900",
        btnClass: "bg-orange-500 hover:bg-orange-600",
        image: "https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=2070&auto=format&fit=crop",
        floatingText: "Premium Cotton",
        price: "৳1,200",
    },
    {
        id: 3,
        tag: "Exclusive Deal",
        title: "Smart",
        highlight: "Watches",
        desc: "Stay connected and healthy with the new Series 9. Now with 20% Discount.",
        bgClass: "from-emerald-900 via-teal-900 to-slate-900",
        btnClass: "bg-emerald-500 hover:bg-emerald-600",
        image: "https://images.unsplash.com/photo-1546868871-7041f2a55e12?q=80&w=2064&auto=format&fit=crop",
        floatingText: "Water Proof",
        price: "৳5,500",
    },
];

let interval;
const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % slides.length;
};
const prevSlide = () => {
    currentSlide.value =
        (currentSlide.value - 1 + slides.length) % slides.length;
};
const setSlide = (index) => {
    currentSlide.value = index;
};
onMounted(() => {
    interval = setInterval(nextSlide, 5000);
});
onUnmounted(() => clearInterval(interval));

const addToCart = (product) => {
    cartStore.addToCart(product);
};
const toggleWishlist = (product) => {
    wishlistStore.toggle(product.id);
};
</script>

<template>
    <Head title="Home" />

    <MainLayout>
        <div
            class="relative overflow-hidden transition-colors duration-1000 bg-gradient-to-br"
            :class="slides[currentSlide].bgClass"
        >
            <div
                class="absolute top-0 right-0 w-[800px] h-[800px] bg-white/5 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2 animate-pulse"
            ></div>
            <div
                class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-black/20 rounded-full blur-[120px] translate-y-1/3 -translate-x-1/4"
            ></div>

            <div
                class="container mx-auto px-6 lg:px-12 relative z-10 h-[650px] flex items-center"
            >
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-12 w-full items-center"
                >
                    <div
                        class="space-y-6 transform transition-all duration-700"
                        :key="slides[currentSlide].id"
                    >
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/20 bg-white/5 backdrop-blur-md animate-fade-in-up"
                        >
                            <SparklesIcon class="w-4 h-4 text-yellow-400" />
                            <span
                                class="text-xs font-bold text-white uppercase tracking-widest"
                                >{{ slides[currentSlide].tag }}</span
                            >
                        </div>
                        <h1
                            class="text-5xl lg:text-8xl font-black text-white leading-tight animate-fade-in-right"
                        >
                            {{ slides[currentSlide].title }} <br />
                            <span
                                :class="'text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400'"
                            >
                                {{ slides[currentSlide].highlight }}
                            </span>
                        </h1>
                        <p
                            class="text-lg text-gray-300 max-w-md leading-relaxed animate-fade-in-up delay-100"
                        >
                            {{ slides[currentSlide].desc }}
                        </p>
                        <div
                            class="flex items-center gap-4 pt-4 animate-fade-in-up delay-200"
                        >
                            <button
                                :class="`px-10 py-4 rounded-2xl font-bold text-white shadow-xl hover:scale-105 transition transform flex items-center gap-2 ${slides[currentSlide].btnClass}`"
                            >
                                {{ __("Shop Now") }}
                                <ArrowRightIcon class="w-5 h-5" />
                            </button>
                            <span class="text-2xl font-bold text-white ml-4">{{
                                slides[currentSlide].price
                            }}</span>
                        </div>
                    </div>
                    <div
                        class="relative hidden lg:block"
                        :key="'img-' + slides[currentSlide].id"
                    >
                        <div
                            class="relative z-10 w-[450px] h-[550px] mx-auto rotate-3 hover:rotate-0 transition-transform duration-700 ease-out"
                        >
                            <div
                                class="absolute inset-0 bg-white/10 backdrop-blur-sm rounded-[3rem] transform translate-x-4 translate-y-4"
                            ></div>
                            <img
                                :src="slides[currentSlide].image"
                                class="w-full h-full object-cover rounded-[3rem] shadow-2xl border border-white/20 animate-scale-up"
                                alt="Product"
                            />
                            <div
                                class="absolute bottom-8 -left-12 bg-white/90 backdrop-blur-md p-4 rounded-2xl shadow-xl animate-bounce-slow flex items-center gap-3"
                            >
                                <div
                                    class="bg-black p-3 rounded-full text-white"
                                >
                                    <ShieldCheckIcon class="w-6 h-6" />
                                </div>
                                <div>
                                    <p
                                        class="text-xs text-gray-500 uppercase font-bold"
                                    >
                                        Featured
                                    </p>
                                    <p class="font-bold text-gray-900">
                                        {{ slides[currentSlide].floatingText }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="absolute bottom-10 left-1/2 -translate-x-1/2 flex items-center gap-4"
                >
                    <button
                        @click="prevSlide"
                        class="p-3 rounded-full border border-white/20 text-white hover:bg-white/10 transition"
                    >
                        <ChevronLeftIcon class="w-5 h-5" />
                    </button>
                    <div class="flex gap-2">
                        <button
                            v-for="(slide, index) in slides"
                            :key="index"
                            @click="setSlide(index)"
                            class="h-1.5 rounded-full transition-all duration-300"
                            :class="
                                currentSlide === index
                                    ? 'w-8 bg-white'
                                    : 'w-2 bg-white/30 hover:bg-white/50'
                            "
                        ></button>
                    </div>
                    <button
                        @click="nextSlide"
                        class="p-3 rounded-full border border-white/20 text-white hover:bg-white/10 transition"
                    >
                        <ChevronRightIcon class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-[#0B0F19] py-24 relative overflow-hidden">
            <div
                class="absolute top-20 left-0 w-[500px] h-[500px] bg-indigo-600/20 rounded-full blur-[120px] pointer-events-none"
            ></div>
            <div
                class="absolute bottom-20 right-0 w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[120px] pointer-events-none"
            ></div>

            <div class="container mx-auto px-4 lg:px-8 relative z-10">
                <div
                    class="flex justify-between items-end mb-12 border-b border-white/10 pb-6"
                >
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <FireIcon
                                class="w-6 h-6 text-orange-500 animate-pulse"
                            />
                            <span
                                class="text-sm font-bold text-orange-500 uppercase tracking-widest"
                                >{{ __("Hot Deals") }}</span
                            >
                        </div>
                        <h2
                            class="text-4xl font-black text-white tracking-tight"
                        >
                            {{ __("Trending Now") }}
                        </h2>
                    </div>
                    <Link
                        href="#"
                        class="hidden md:flex items-center gap-2 text-sm font-bold text-white hover:text-indigo-400 transition bg-white/5 px-4 py-2 rounded-full border border-white/10"
                    >
                        {{ __("View All Products") }}
                        <ArrowRightIcon class="w-4 h-4" />
                    </Link>
                </div>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6"
                >
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="group relative bg-white/5 backdrop-blur-sm rounded-3xl p-4 border border-white/10 hover:border-indigo-500/50 hover:bg-white/10 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-indigo-500/20"
                    >
                        <div
                            class="relative h-60 rounded-2xl bg-white overflow-hidden mb-4"
                        >
                            <Link
                                :href="route('product.details', product.slug)"
                                class="block w-full h-full"
                            >
                                <img
                                    v-if="product.thumb_image"
                                    :src="'/storage/' + product.thumb_image"
                                    :alt="product.name"
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

                        <div class="px-1">
                            <p
                                class="text-xs text-indigo-400 font-bold uppercase tracking-wider mb-1"
                            >
                                {{ product.category?.name || "General" }}
                            </p>

                            <Link
                                :href="route('product.details', product.slug)"
                            >
                                <h3
                                    class="font-bold text-lg text-white leading-snug group-hover:text-indigo-400 transition-colors mb-2 line-clamp-2"
                                >
                                    {{ product.name }}
                                </h3>
                            </Link>

                            <div class="flex items-center gap-1 mb-4">
                                <div class="flex text-yellow-400">
                                    <StarIcon
                                        class="w-3.5 h-3.5 fill-current"
                                    />
                                    <StarIcon
                                        class="w-3.5 h-3.5 fill-current"
                                    />
                                    <StarIcon
                                        class="w-3.5 h-3.5 fill-current"
                                    />
                                    <StarIcon
                                        class="w-3.5 h-3.5 fill-current"
                                    />
                                    <StarIcon
                                        class="w-3.5 h-3.5 text-white/20"
                                    />
                                </div>
                                <span class="text-xs font-bold text-gray-400"
                                    >(4.5)</span
                                >
                            </div>

                            <div
                                class="flex items-center justify-between border-t border-white/10 pt-4"
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
                                        class="text-xl font-black text-white tracking-tight"
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
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
/* Animations */
.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
}
.animate-fade-in-right {
    animation: fadeInRight 0.8s ease-out forwards;
}
.animate-scale-up {
    animation: scaleUp 0.8s ease-out forwards;
}
.animate-bounce-slow {
    animation: bounce 4s infinite;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
@keyframes scaleUp {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
@keyframes bounce {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}
</style>
