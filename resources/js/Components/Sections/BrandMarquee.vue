<script setup>
import { onMounted } from "vue";
import { BuildingStorefrontIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    brands: {
        type: Array,
        default: () => [],
    },
});

// ✅ ১. ডিবাগিং (কনসোলে চেক করার জন্য)
onMounted(() => {
    console.log("Brands Data:", props.brands);
});

// ✅ ২. ইমেজ পাথ জেনারেটর (Bulletproof Logic)
const getBrandLogo = (logoPath) => {
    if (!logoPath) return null;

    // যদি ফুল URL থাকে (S3 বা রিমোট স্টোরেজ)
    if (logoPath.startsWith("http")) return logoPath;

    // স্ল্যাশ ক্লিন করা (যাতে ডাবল স্ল্যাশ না হয়)
    const cleanPath = logoPath.replace(/^\/+/, "");

    // সঠিক পাথ রিটার্ন করা
    return `/storage/${cleanPath}`;
};
</script>

<template>
    <section
        v-if="brands && brands.length > 0"
        class="py-16 border-y border-white/5 bg-[#0B0F19] overflow-hidden relative"
    >
        <div
            class="absolute inset-0 bg-indigo-500/5 blur-[100px] pointer-events-none"
        ></div>

        <div class="container mx-auto px-4 mb-14 text-center relative z-10">
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
                class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-[#0B0F19] to-transparent z-10 pointer-events-none"
            ></div>
            <div
                class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-[#0B0F19] to-transparent z-10 pointer-events-none"
            ></div>

            <div class="flex overflow-x-hidden group">
                <div
                    class="animate-marquee whitespace-nowrap flex gap-16 items-center group-hover:pause"
                >
                    <div
                        v-for="brand in brands"
                        :key="brand.id"
                        class="flex items-center gap-4 group/item cursor-pointer select-none"
                    >
                        <div
                            class="w-16 h-16 bg-white rounded-xl flex items-center justify-center p-2 border border-white/10 shadow-lg group-hover/item:scale-110 transition-all duration-300 overflow-hidden"
                        >
                            <img
                                v-if="brand.logo"
                                :src="getBrandLogo(brand.logo)"
                                :alt="brand.name"
                                class="w-full h-full object-contain"
                                @error="
                                    $event.target.src =
                                        'https://placehold.co/100x100?text=No+Img'
                                "
                            />

                            <BuildingStorefrontIcon
                                v-else
                                class="w-8 h-8 text-gray-400"
                            />
                        </div>

                        <span
                            class="text-2xl font-black text-transparent stroke-text uppercase group-hover/item:stroke-0 group-hover/item:text-white transition-all duration-300"
                        >
                            {{ brand.name }}
                        </span>
                    </div>

                    <div
                        v-for="brand in brands"
                        :key="'dup' + brand.id"
                        class="flex items-center gap-4 group/item cursor-pointer select-none"
                    >
                        <div
                            class="w-16 h-16 bg-white rounded-xl flex items-center justify-center p-2 border border-white/10 shadow-lg group-hover/item:scale-110 transition-all duration-300 overflow-hidden"
                        >
                            <img
                                v-if="brand.logo"
                                :src="getBrandLogo(brand.logo)"
                                :alt="brand.name"
                                class="w-full h-full object-contain"
                                @error="
                                    $event.target.src =
                                        'https://placehold.co/100x100?text=No+Img'
                                "
                            />
                            <BuildingStorefrontIcon
                                v-else
                                class="w-8 h-8 text-gray-400"
                            />
                        </div>

                        <span
                            class="text-2xl font-black text-transparent stroke-text uppercase group-hover/item:stroke-0 group-hover/item:text-white transition-all duration-300"
                        >
                            {{ brand.name }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.animate-marquee {
    animation: marquee 40s linear infinite;
}
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
.stroke-text {
    -webkit-text-stroke: 1px rgba(255, 255, 255, 0.3);
    color: transparent;
}
.group-hover\/item\:text-white {
    -webkit-text-stroke: 0px;
    color: #ffffff;
    text-shadow: 0 0 15px rgba(129, 140, 248, 0.6);
}
</style>
