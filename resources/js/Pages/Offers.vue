<script setup>
import { Head } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import ActivePromos from "@/Components/Sections/ActivePromos.vue";
import { SparklesIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    promos: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Exclusive Offers" />

    <MainLayout>
        <div
            class="min-h-screen bg-[#050505] relative overflow-hidden selection:bg-purple-500 selection:text-white"
        >
            <div
                class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none"
            >
                <div
                    class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] bg-purple-600/20 rounded-full blur-[120px] animate-blob"
                ></div>
                <div
                    class="absolute top-[20%] right-[-10%] w-[500px] h-[500px] bg-pink-600/10 rounded-full blur-[120px] animate-blob animation-delay-2000"
                ></div>
                <div
                    class="absolute bottom-[-10%] left-[20%] w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[120px] animate-blob animation-delay-4000"
                ></div>
                <div
                    class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"
                ></div>
            </div>

            <div
                class="container mx-auto px-4 lg:px-8 relative z-10 pt-16 pb-20"
            >
                <div class="text-center mb-20 relative">
                    <div
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-md text-pink-300 text-xs font-bold uppercase tracking-[0.2em] mb-6 hover:bg-white/10 transition-colors cursor-default shadow-lg shadow-purple-500/10 animate-fade-in-down"
                    >
                        <SparklesIcon
                            class="w-4 h-4 text-yellow-400 animate-pulse"
                        />
                        Limited Time Offers
                    </div>

                    <h1
                        class="text-5xl md:text-7xl font-black text-white tracking-tight mb-6 leading-tight drop-shadow-2xl animate-fade-in-up"
                    >
                        Unlock Premium <br />
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-pink-500 to-orange-500 animate-gradient-x"
                        >
                            Rewards & Deals
                        </span>
                    </h1>

                    <p
                        class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed animate-fade-in-up delay-100"
                    >
                        Discover exclusive vouchers curated just for you. Copy
                        the code, shop your favorites, and enjoy massive savings
                        at checkout!
                    </p>
                </div>

                <div class="animate-fade-in-up delay-200">
                    <ActivePromos :promos="promos" />
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
    100% {
        transform: translate(0px, 0px) scale(1);
    }
}
.animate-blob {
    animation: blob 7s infinite;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-4000 {
    animation-delay: 4s;
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
.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
    opacity: 0;
}
.delay-100 {
    animation-delay: 0.1s;
}
.delay-200 {
    animation-delay: 0.2s;
}
</style>
