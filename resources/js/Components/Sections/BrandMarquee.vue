<script setup>
import { BuildingStorefrontIcon } from "@heroicons/vue/24/solid";
</script>

<template>
    <section
        class="py-16 border-y border-white/5 bg-[#0B0F19] overflow-hidden relative"
    >
        <div
            class="absolute inset-0 bg-indigo-500/5 blur-[100px] pointer-events-none"
        ></div>

        <div class="container mx-auto px-4 mb-12 text-center relative z-10">
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-indigo-400 text-xs font-bold uppercase tracking-widest mb-3 shadow-lg"
            >
                <BuildingStorefrontIcon class="w-4 h-4" /> Trusted Global Brands
            </div>
            <h3
                class="text-3xl md:text-4xl font-black text-white tracking-tight"
            >
                Our Official
                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400"
                    >Partners</span
                >
            </h3>
        </div>

        <div class="relative w-full">
            <div
                class="absolute inset-0 pointer-events-none z-10 bg-gradient-to-r from-[#0B0F19] via-transparent to-[#0B0F19]"
            ></div>

            <div class="flex overflow-x-hidden group">
                <div
                    class="animate-marquee whitespace-nowrap flex gap-20 items-center group-hover:pause"
                >
                    <span
                        v-for="i in 10"
                        :key="i"
                        class="text-4xl md:text-5xl font-black text-transparent stroke-text uppercase hover:text-indigo-500 hover:stroke-0 transition-all duration-300 cursor-pointer opacity-30 hover:opacity-100 select-none"
                    >
                        BRANDNAME {{ i }}
                    </span>

                    <span
                        v-for="i in 10"
                        :key="'dup' + i"
                        class="text-4xl md:text-5xl font-black text-transparent stroke-text uppercase hover:text-indigo-500 hover:stroke-0 transition-all duration-300 cursor-pointer opacity-30 hover:opacity-100 select-none"
                    >
                        BRANDNAME {{ i }}
                    </span>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* 🔥 Slow & Smooth Animation */
.animate-marquee {
    animation: marquee 50s linear infinite; /* Slowed down to 50s */
}

/* Pause on Hover */
.group-hover\:pause:hover {
    animation-play-state: paused;
}

@keyframes marquee {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

/* 🔥 Text Stroke Effect (Outline Text) */
.stroke-text {
    -webkit-text-stroke: 2px rgba(255, 255, 255, 0.2);
    color: transparent;
}
.stroke-text:hover {
    -webkit-text-stroke: 0px;
    color: #818cf8; /* Indigo-400 */
    text-shadow: 0 0 20px rgba(99, 102, 241, 0.5); /* Glow effect on hover */
}
</style>
