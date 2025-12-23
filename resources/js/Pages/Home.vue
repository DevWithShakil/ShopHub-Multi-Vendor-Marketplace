<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {
    ShoppingCartIcon,
    ArrowRightIcon,
    HeartIcon,
    ChevronRightIcon,
    ChevronLeftIcon,
    EyeIcon,
    TagIcon,
    FireIcon,
    SparklesIcon,
} from "@heroicons/vue/24/outline";
import {
    HeartIcon as HeartSolidIcon,
    StarIcon as StarSolidIcon,
} from "@heroicons/vue/24/solid";
import { useCartStore } from "@/Stores/cartStore";
import { useWishlistStore } from "@/Stores/wishlistStore";
import { ref, onMounted, onUnmounted, computed } from "vue";

// Props
const props = defineProps({
    products: Array,
    slides: Array,
});

const page = usePage();
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();

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

const getRootCategory = (category) => {
    if (!category) return "Uncategorized";
    let current = category;
    while (current.parent) {
        current = current.parent;
    }
    return getLocalizedName(current.name);
};

const productsByCategory = computed(() => {
    const grouped = {};
    if (props.products) {
        props.products.forEach((product) => {
            if (!product.category) return;
            const rootCatName = getRootCategory(product.category);
            if (!grouped[rootCatName]) {
                grouped[rootCatName] = {
                    products: [],
                    subCategories: new Set(),
                };
            }
            grouped[rootCatName].products.push(product);
            const currentCatName = getLocalizedName(product.category.name);
            if (currentCatName !== rootCatName) {
                grouped[rootCatName].subCategories.add(currentCatName);
            }
        });
    }
    return grouped;
});

const categoriesList = computed(() =>
    Object.keys(productsByCategory.value).sort()
);

// --- 🌟 Slider Logic ---
const currentSlide = ref(0);

// সরাসরি কন্ট্রোলারের ডাটা ব্যবহার (প্রসেসড)
const processedSlides = computed(() => {
    if (props.slides && props.slides.length > 0) {
        return props.slides;
    }
    // Fallback Slide
    return [
        {
            id: 0,
            slug: "#",
            tag: "Welcome",
            name: "Future Tech",
            highlight: "Premium",
            desc: "Experience the next generation of premium gadgets.",
            price: "",
            image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=2070&auto=format&fit=crop",
            bgClass:
                "bg-gradient-radial from-indigo-900/40 via-[#0B0F19] to-[#0B0F19]",
            textGradient: "from-indigo-400 via-purple-400 to-pink-400",
            btnClass: "bg-indigo-600 hover:bg-indigo-500",
        },
    ];
});

let interval;
const nextSlide = () => {
    currentSlide.value =
        (currentSlide.value + 1) % processedSlides.value.length;
};
const prevSlide = () => {
    currentSlide.value =
        (currentSlide.value - 1 + processedSlides.value.length) %
        processedSlides.value.length;
};
const setSlide = (index) => {
    currentSlide.value = index;
};

onMounted(() => {
    if (processedSlides.value.length > 1) {
        interval = setInterval(nextSlide, 6000);
    }
});
onUnmounted(() => clearInterval(interval));

// Actions
const addToCart = (product) => cartStore.addToCart(product);
const toggleWishlist = (product) => wishlistStore.toggle(product.id);

const scrollContainer = (id, direction) => {
    const container = document.getElementById(id);
    if (container) {
        const scrollAmount = 340;
        if (direction === "left")
            container.scrollBy({ left: -scrollAmount, behavior: "smooth" });
        else container.scrollBy({ left: scrollAmount, behavior: "smooth" });
    }
};

const scrollToSection = (categoryName) => {
    const element = document.getElementById("cat-section-" + categoryName);
    if (element) {
        const offset = 180;
        const rect = element.getBoundingClientRect();
        const scrollTop =
            window.pageYOffset || document.documentElement.scrollTop;
        window.scrollTo({
            top: rect.top + scrollTop - offset,
            behavior: "smooth",
        });
    }
};
const scrollToTop = () => window.scrollTo({ top: 0, behavior: "smooth" });
</script>

<template>
    <Head title="Home" />

    <MainLayout>
        <div
            class="relative w-full h-[680px] lg:h-[800px] overflow-hidden bg-[#0B0F19] font-sans flex items-center justify-center"
        >
            <div
                class="absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-50 blur-[150px]"
                :class="
                    processedSlides[currentSlide].bgClass.replace(
                        'bg-gradient-radial',
                        'bg-gradient-to-tr'
                    )
                "
            ></div>

            <div
                class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay pointer-events-none"
            ></div>

            <div
                class="container mx-auto px-6 lg:px-12 relative z-10 w-full h-full flex items-center"
            >
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-12 w-full items-center"
                >
                    <div
                        class="space-y-8 order-2 lg:order-1 relative z-20 mt-10 lg:mt-0"
                        :key="processedSlides[currentSlide].id"
                    >
                        <div
                            class="inline-flex items-center gap-2 px-5 py-2 rounded-full border border-white/10 bg-white/5 backdrop-blur-xl animate-fade-in-up w-fit shadow-[0_0_30px_rgba(255,255,255,0.05)]"
                        >
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 bg-white"
                                ></span>
                                <span
                                    class="relative inline-flex rounded-full h-2 w-2 bg-white"
                                ></span>
                            </span>
                            <span
                                class="text-xs font-bold text-gray-200 uppercase tracking-[0.2em]"
                            >
                                {{ processedSlides[currentSlide].tag }}
                            </span>
                        </div>

                        <div class="animate-fade-in-right">
                            <h1
                                class="text-5xl md:text-7xl lg:text-8xl font-black text-white leading-[0.95] tracking-tight drop-shadow-2xl"
                            >
                                {{ processedSlides[currentSlide].name }}
                                <span
                                    class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r pb-3"
                                    :class="
                                        processedSlides[currentSlide]
                                            .textGradient ||
                                        'from-indigo-400 via-purple-400 to-pink-400'
                                    "
                                >
                                    {{
                                        processedSlides[currentSlide].highlight
                                    }}
                                </span>
                            </h1>
                        </div>

                        <div class="space-y-6 animate-fade-in-up delay-100">
                            <div
                                v-if="processedSlides[currentSlide].price"
                                class="flex items-center gap-4"
                            >
                                <span
                                    class="text-5xl lg:text-6xl font-black text-white tracking-tighter shadow-black drop-shadow-lg"
                                >
                                    {{ processedSlides[currentSlide].price }}
                                </span>
                                <span
                                    class="text-sm font-bold text-gray-400 border border-gray-600 px-2 py-1 rounded"
                                    >BDT</span
                                >
                            </div>
                            <p
                                class="text-gray-300 text-lg leading-relaxed max-w-lg border-l-4 border-white/20 pl-6 line-clamp-3"
                            >
                                {{ processedSlides[currentSlide].desc }}
                            </p>
                        </div>

                        <div
                            class="flex flex-wrap items-center gap-6 pt-4 animate-fade-in-up delay-200"
                        >
                            <Link
                                :href="
                                    route(
                                        'product.details',
                                        processedSlides[currentSlide].slug
                                    )
                                "
                                class="group relative px-10 py-5 bg-white text-black font-black text-lg rounded-full shadow-[0_0_40px_rgba(255,255,255,0.2)] hover:shadow-[0_0_60px_rgba(255,255,255,0.4)] transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-3 overflow-hidden"
                            >
                                <span
                                    class="relative z-10 uppercase tracking-widest text-sm"
                                    >Shop Now</span
                                >
                                <ArrowRightIcon
                                    class="w-5 h-5 relative z-10 group-hover:translate-x-1 transition-transform"
                                />
                            </Link>

                            <Link
                                :href="
                                    route(
                                        'product.details',
                                        processedSlides[currentSlide].slug
                                    )
                                "
                                class="p-5 rounded-full border border-white/10 text-white hover:bg-white/10 transition-all active:scale-95 group backdrop-blur-sm"
                            >
                                <EyeIcon
                                    class="w-7 h-7 group-hover:scale-110 transition-transform text-gray-400 group-hover:text-white"
                                />
                            </Link>
                        </div>
                    </div>

                    <div
                        class="relative order-1 lg:order-2 h-[400px] lg:h-[650px] flex items-center justify-center perspective-1000"
                        :key="'img-' + processedSlides[currentSlide].id"
                    >
                        <div
                            class="absolute w-[350px] h-[350px] lg:w-[600px] lg:h-[500px] bg-white/10 rounded-full blur-[120px] animate-pulse-slow"
                        ></div>

                        <div
                            class="relative z-10 group/card w-[340px] md:w-[500px] aspect-[4/3] rounded-[2.5rem] bg-white/5 backdrop-blur-xl border border-white/10 shadow-[0_35px_60px_-15px_rgba(0,0,0,0.5)] overflow-hidden transition-all duration-700 hover:rotate-2 hover:scale-[1.02] animate-float"
                        >
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-black/30 pointer-events-none"
                            ></div>

                            <div
                                class="w-full h-full flex items-center justify-center p-0 overflow-hidden relative"
                            >
                                <img
                                    :src="processedSlides[currentSlide].image"
                                    class="w-full h-full object-cover transition-transform duration-1000 group-hover/card:scale-110"
                                    alt="Product"
                                />

                                <div class="absolute bottom-6 left-6 z-20">
                                    <p
                                        class="text-white/80 text-xs font-bold tracking-[0.3em] uppercase backdrop-blur-md bg-black/30 px-3 py-1 rounded-full border border-white/10"
                                    >
                                        Premium Collection
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="absolute bottom-10 right-6 lg:right-20 flex items-center gap-6 z-30"
                >
                    <div class="flex gap-2">
                        <button
                            v-for="(slide, index) in processedSlides"
                            :key="index"
                            @click="setSlide(index)"
                            class="h-1.5 rounded-full transition-all duration-500"
                            :class="
                                currentSlide === index
                                    ? 'w-12 bg-white shadow-[0_0_15px_white]'
                                    : 'w-2 bg-white/20 hover:bg-white/40'
                            "
                        ></button>
                    </div>
                    <div class="flex gap-3 ml-4">
                        <button
                            @click="prevSlide"
                            class="p-4 rounded-full border border-white/10 bg-black/20 backdrop-blur-md text-white hover:bg-white hover:text-black transition-all active:scale-95 shadow-lg group"
                        >
                            <ChevronLeftIcon
                                class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform"
                            />
                        </button>
                        <button
                            @click="nextSlide"
                            class="p-4 rounded-full border border-white/10 bg-black/20 backdrop-blur-md text-white hover:bg-white hover:text-black transition-all active:scale-95 shadow-lg group"
                        >
                            <ChevronRightIcon
                                class="w-5 h-5 group-hover:translate-x-0.5 transition-transform"
                            />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="bg-[#0B0F19]/90 border-b border-white/10 py-4 sticky top-[80px] z-40 backdrop-blur-xl shadow-lg transition-all"
        >
            <div class="container mx-auto px-4 lg:px-8">
                <div
                    class="flex items-center gap-3 overflow-x-auto no-scrollbar"
                >
                    <button
                        @click="scrollToTop"
                        class="px-5 py-2 rounded-full bg-white text-gray-900 text-sm font-bold whitespace-nowrap shadow-lg shadow-white/10 hover:scale-105 transition-transform flex items-center gap-2 flex-shrink-0"
                    >
                        <FireIcon class="w-4 h-4 text-orange-500" /> All
                        Categories
                    </button>
                    <button
                        v-for="cat in categoriesList"
                        :key="cat"
                        @click="scrollToSection(cat)"
                        class="px-5 py-2 rounded-full bg-white/5 border border-white/10 text-gray-300 text-sm font-semibold whitespace-nowrap hover:bg-white/10 hover:text-white transition-colors hover:border-indigo-500/50 flex-shrink-0"
                    >
                        {{ cat }}
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-[#0B0F19] py-16 relative overflow-hidden">
            <div
                class="container mx-auto px-4 lg:px-8 relative z-10 space-y-24"
            >
                <div
                    v-for="(group, categoryName) in productsByCategory"
                    :key="categoryName"
                    :id="'cat-section-' + categoryName"
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
                                    category: categoryName,
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
                        :id="'scroll-' + categoryName"
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
                                    <Link
                                        :href="
                                            route(
                                                'product.details',
                                                product.slug
                                            )
                                        "
                                        class="w-9 h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-lg text-gray-500 hover:text-indigo-600 transition-colors"
                                    >
                                        <EyeIcon class="w-5 h-5" />
                                    </Link>
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
                                    <div
                                        class="flex items-center gap-1 group-hover/rating:text-white transition-colors"
                                    >
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
                                        <span
                                            class="text-[10px] text-gray-500 ml-1 decoration-gray-600 group-hover/rating:underline"
                                            >| {{ product.reviews_count || 0 }}
                                            {{ __("Reviews") }}</span
                                        >
                                    </div>
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
/* Scrollbar Hiding */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Ultra Smooth Animations */
@keyframes float {
    0%,
    100% {
        transform: translateY(0) rotate(0deg);
    }
    50% {
        transform: translateY(-10px) rotate(1deg);
    }
}
.animate-float {
    animation: float 6s ease-in-out infinite;
}

@keyframes pulse-slow {
    0%,
    100% {
        opacity: 0.3;
        transform: scale(1);
    }
    50% {
        opacity: 0.5;
        transform: scale(1.05);
    }
}
.animate-pulse-slow {
    animation: pulse-slow 8s infinite;
}

/* Slide Transitions */
.animate-fade-in-up {
    animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
.animate-fade-in-right {
    animation: fadeInRight 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Gradient Utility */
.bg-clip-text {
    -webkit-background-clip: text;
}
</style>
