<script setup>
import {
    TruckIcon,
    ShieldCheckIcon,
    ArrowPathIcon,
    ChatBubbleLeftRightIcon,
} from "@heroicons/vue/24/solid";

const features = [
    {
        icon: TruckIcon,
        title: "Fast Delivery",
        desc: "All over Bangladesh",
        color: "text-blue-400",
        bg: "bg-blue-500/10 border-blue-500/20",
    },
    {
        icon: ShieldCheckIcon,
        title: "Official Warranty",
        desc: "100% Authentic Items",
        color: "text-green-400",
        bg: "bg-green-500/10 border-green-500/20",
    },
    {
        icon: ArrowPathIcon,
        title: "Easy Returns",
        desc: "7 Days Return Policy",
        color: "text-orange-400",
        bg: "bg-orange-500/10 border-orange-500/20",
    },
    {
        icon: ChatBubbleLeftRightIcon,
        title: "24/7 Support",
        desc: "Expert Customer Care",
        color: "text-purple-400",
        bg: "bg-purple-500/10 border-purple-500/20",
    },
];
</script>

<template>
    <section class="py-10 bg-[#0B0F19] border-y border-white/5">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div
                    v-for="(feature, index) in features"
                    :key="index"
                    class="flex items-center gap-4 p-5 rounded-2xl border transition-all duration-300 hover:-translate-y-1 hover:shadow-lg bg-[#151925]"
                    :class="feature.bg"
                >
                    <div
                        class="w-12 h-12 rounded-full flex items-center justify-center bg-white/5"
                    >
                        <component
                            :is="feature.icon"
                            class="w-6 h-6"
                            :class="feature.color"
                        />
                    </div>

                    <div>
                        <h3 class="text-white font-bold text-base">
                            {{ feature.title }}
                        </h3>
                        <p class="text-gray-400 text-xs mt-0.5">
                            {{ feature.desc }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
