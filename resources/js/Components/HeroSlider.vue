<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import {
    ArrowRightIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    StarIcon,
    ShieldCheckIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    slides: Array,
});

const currentSlide = ref(0);

// 🎨 Theme Logic
const getTheme = (slide) => {
    const colorKey = slide.bgClass || "";

    if (colorKey.includes("orange")) {
        return {
            bg: "bg-[#1a1008]",
            accent: "text-orange-500",
            glowBg: "bg-orange-600/30",
            topGlow: "from-orange-500/20 via-transparent to-transparent",
            btn: "bg-white text-black hover:bg-orange-500 hover:text-white",
            backText: "text-orange-900/20",
            frameBorder: "border-orange-500/30",
            ring: "ring-orange-500/20",
        };
    } else if (colorKey.includes("emerald") || colorKey.includes("green")) {
        return {
            bg: "bg-[#05110d]",
            accent: "text-emerald-400",
            glowBg: "bg-emerald-600/30",
            topGlow: "from-emerald-500/20 via-transparent to-transparent",
            btn: "bg-white text-black hover:bg-emerald-500 hover:text-white",
            backText: "text-emerald-900/20",
            frameBorder: "border-emerald-500/30",
            ring: "ring-emerald-500/20",
        };
    } else if (colorKey.includes("purple")) {
        return {
            bg: "bg-[#0f0518]",
            accent: "text-purple-400",
            glowBg: "bg-purple-600/30",
            topGlow: "from-purple-500/20 via-transparent to-transparent",
            btn: "bg-white text-black hover:bg-purple-500 hover:text-white",
            backText: "text-purple-900/20",
            frameBorder: "border-purple-500/30",
            ring: "ring-purple-500/20",
        };
    }

    // Default
    return {
        bg: "bg-[#080b14]",
        accent: "text-indigo-400",
        glowBg: "bg-indigo-600/30",
        topGlow: "from-indigo-500/20 via-transparent to-transparent",
        btn: "bg-white text-black hover:bg-indigo-500 hover:text-white",
        backText: "text-indigo-900/20",
        frameBorder: "border-indigo-500/30",
        ring: "ring-indigo-500/20",
    };
};

const processedSlides = computed(() => {
    if (props.slides && props.slides.length > 0) {
        return props.slides.map((slide) => ({
            ...slide,
            theme: getTheme(slide),
        }));
    }
    const fallbackTheme = getTheme({});
    return [
        {
            id: 0,
            slug: "#",
            tag: "NEW ARRIVAL",
            name: "Future Tech",
            highlight: "Unleashed",
            desc: "Experience the next generation of premium gadgets.",
            price: "120,000",
            image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=2070&auto=format&fit=crop",
            theme: fallbackTheme,
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
    if (processedSlides.value.length > 1)
        interval = setInterval(nextSlide, 7000);
});
onUnmounted(() => clearInterval(interval));
</script>

<template>
    <div
        class="relative w-full min-h-[850px] lg:h-[750px] lg:min-h-0 overflow-hidden font-sans flex items-center justify-center transition-colors duration-1000 ease-in-out py-20 lg:py-0"
        :class="processedSlides[currentSlide].theme.bg"
    >
        <div class="absolute inset-0 w-full h-full pointer-events-none">
            <div
                class="absolute top-0 right-0 w-[100%] lg:w-[80%] h-[80%] bg-gradient-radial opacity-40 blur-[80px] lg:blur-[120px] transition-all duration-1000"
                :class="processedSlides[currentSlide].theme.topGlow"
            ></div>
            <div
                class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] mix-blend-overlay"
            ></div>
        </div>

        <div
            class="absolute left-0 top-20 lg:top-auto lg:bottom-20 w-full overflow-hidden select-none pointer-events-none"
        >
            <h1
                class="text-[4rem] md:text-[8rem] lg:text-[18rem] xl:text-[20rem] font-black leading-none tracking-tighter whitespace-nowrap opacity-100 transition-colors duration-1000 transform -translate-x-4 lg:-translate-x-10"
                :class="processedSlides[currentSlide].theme.backText"
            >
                {{ processedSlides[currentSlide].highlight || "PREMIUM" }}
            </h1>
        </div>

        <div
            class="container mx-auto px-4 sm:px-6 lg:px-12 relative z-10 h-full flex flex-col justify-center"
        >
            <div
                class="grid grid-cols-1 lg:grid-cols-12 h-full items-center gap-12 lg:gap-0"
            >
                <div
                    class="lg:col-span-6 space-y-6 lg:space-y-8 order-1 text-center lg:text-left pt-10 lg:pt-0"
                    :key="processedSlides[currentSlide].id"
                >
                    <div
                        class="animate-fade-up flex justify-center lg:justify-start"
                    >
                        <span
                            class="text-[10px] lg:text-xs font-bold tracking-[0.3em] uppercase pl-1 border-l-2 border-white/20"
                            :class="processedSlides[currentSlide].theme.accent"
                        >
                            {{ processedSlides[currentSlide].tag }}
                        </span>
                    </div>

                    <div class="animate-fade-up delay-100">
                        <h2
                            class="text-5xl md:text-7xl lg:text-7xl xl:text-8xl font-black text-white leading-[1.1] lg:leading-[0.95] tracking-tight"
                        >
                            {{ processedSlides[currentSlide].name }}
                            <br class="hidden lg:block" />
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-500"
                            >
                                {{ processedSlides[currentSlide].highlight }}
                            </span>
                        </h2>
                    </div>

                    <div
                        class="flex flex-col gap-4 lg:gap-6 animate-fade-up delay-200 items-center lg:items-start"
                    >
                        <p
                            class="text-gray-400 text-sm md:text-base lg:text-lg max-w-md leading-relaxed"
                        >
                            {{ processedSlides[currentSlide].desc }}
                        </p>
                        <div class="flex items-baseline gap-2">
                            <span
                                class="text-xs lg:text-sm text-gray-500 font-bold uppercase tracking-wide"
                                >Price</span
                            >
                            <span
                                class="text-2xl lg:text-3xl font-bold text-white tracking-tight"
                                >{{ processedSlides[currentSlide].price }}</span
                            >
                        </div>
                    </div>

                    <div
                        class="pt-2 lg:pt-4 animate-fade-up delay-300 flex justify-center lg:justify-start"
                    >
                        <Link
                            :href="
                                route(
                                    'product.details',
                                    processedSlides[currentSlide].slug
                                )
                            "
                            class="group relative inline-flex items-center gap-3 px-8 py-3.5 lg:px-8 lg:py-4 rounded-full font-bold text-sm transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg"
                            :class="processedSlides[currentSlide].theme.btn"
                        >
                            <span>Explore Product</span>
                            <div
                                class="w-6 h-6 rounded-full bg-black/10 flex items-center justify-center group-hover:bg-black/20 transition"
                            >
                                <ArrowRightIcon class="w-3 h-3" />
                            </div>
                        </Link>
                    </div>
                </div>

                <div
                    class="lg:col-span-6 min-h-[350px] lg:h-full flex items-center justify-center relative order-2 pb-16 lg:pb-0"
                    :key="'img-' + processedSlides[currentSlide].id"
                >
                    <div
                        class="absolute w-[80%] h-[80%] rounded-[3rem] blur-[80px] lg:blur-[100px] opacity-60 transition-colors duration-1000 -z-10 animate-pulse-slow"
                        :class="processedSlides[currentSlide].theme.glowBg"
                    ></div>

                    <div
                        class="relative w-[300px] sm:w-[400px] lg:w-full max-w-[550px] aspect-[4/3] rounded-[2rem] lg:rounded-[2.5rem] bg-white/5 border backdrop-blur-md shadow-2xl overflow-hidden group animate-float-slow ring-1"
                        :class="[
                            processedSlides[currentSlide].theme.frameBorder,
                            processedSlides[currentSlide].theme.ring,
                        ]"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-tr from-white/5 to-transparent opacity-50 pointer-events-none"
                        ></div>

                        <div
                            class="w-full h-full p-6 lg:p-8 flex items-center justify-center relative z-10"
                        >
                            <img
                                :src="processedSlides[currentSlide].image"
                                class="w-full h-full object-contain drop-shadow-2xl transform transition-transform duration-700 group-hover:scale-105"
                                alt="Product"
                            />
                        </div>

                        <div
                            class="absolute top-4 right-4 lg:top-6 lg:right-6 z-20"
                        >
                            <div
                                class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-black/20 backdrop-blur-md border border-white/10 flex items-center justify-center shadow-lg"
                            >
                                <StarIcon
                                    class="w-5 h-5 lg:w-6 lg:h-6 text-yellow-400 fill-current"
                                />
                            </div>
                        </div>

                        <div
                            class="absolute bottom-4 left-4 lg:bottom-6 lg:left-6 z-20 flex items-center gap-2 lg:gap-3 bg-black/40 backdrop-blur-md px-3 py-1.5 lg:px-4 lg:py-2 rounded-xl border border-white/10 shadow-lg"
                        >
                            <div class="p-1 rounded-lg bg-white/10">
                                <ShieldCheckIcon
                                    class="w-3 h-3 lg:w-4 lg:h-4 text-white"
                                />
                            </div>
                            <div>
                                <p
                                    class="text-[8px] lg:text-[10px] text-gray-400 uppercase tracking-wide"
                                >
                                    Authentic
                                </p>
                                <p
                                    class="text-[10px] lg:text-xs font-bold text-white"
                                >
                                    100% Genuine
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="absolute bottom-6 lg:bottom-10 left-1/2 -translate-x-1/2 lg:translate-x-0 lg:left-12 z-30 flex items-center gap-8 lg:gap-12 w-full lg:w-auto justify-center lg:justify-start"
        >
            <div class="flex gap-2">
                <button
                    v-for="(slide, index) in processedSlides"
                    :key="index"
                    @click="setSlide(index)"
                    class="h-1 transition-all duration-500 rounded-full"
                    :class="
                        currentSlide === index
                            ? `w-8 lg:w-12 bg-white`
                            : 'w-3 lg:w-4 bg-white/20 hover:bg-white/40'
                    "
                ></button>
            </div>
            <div class="flex gap-2">
                <button
                    @click="prevSlide"
                    class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center text-white hover:bg-white hover:text-black transition-all"
                >
                    <ChevronLeftIcon class="w-4 h-4" />
                </button>
                <button
                    @click="nextSlide"
                    class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center text-white hover:bg-white hover:text-black transition-all"
                >
                    <ChevronRightIcon class="w-4 h-4" />
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Animations remain unchanged */
@keyframes float-slow {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-15px);
    }
}
.animate-float-slow {
    animation: float-slow 6s ease-in-out infinite;
}

@keyframes pulse-slow {
    0%,
    100% {
        opacity: 0.4;
        transform: scale(1);
    }
    50% {
        opacity: 0.6;
        transform: scale(1.05);
    }
}
.animate-pulse-slow {
    animation: pulse-slow 8s infinite;
}

.animate-fade-up {
    animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    opacity: 0;
    transform: translateY(20px);
}
@keyframes fadeUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.delay-100 {
    animation-delay: 0.1s;
}
.delay-200 {
    animation-delay: 0.2s;
}
.delay-300 {
    animation-delay: 0.3s;
}

.bg-gradient-radial {
    background-image: radial-gradient(var(--tw-gradient-stops));
}
</style>
