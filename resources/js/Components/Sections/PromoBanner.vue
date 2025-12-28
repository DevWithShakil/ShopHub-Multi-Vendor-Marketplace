<script setup>
import { Link } from "@inertiajs/vue3";
import { ArrowRightIcon, SparklesIcon } from "@heroicons/vue/24/solid";
</script>

<template>
    <section class="py-20 bg-[#0B0F19] relative overflow-hidden">
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[500px] bg-indigo-600/20 rounded-full blur-[120px] pointer-events-none"
        ></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div
                class="relative rounded-[2.5rem] overflow-hidden min-h-[450px] md:min-h-[500px] flex items-center border border-white/10 group shadow-2xl shadow-indigo-900/20"
            >
                <div class="absolute inset-0 overflow-hidden">
                    <img
                        src="https://images.unsplash.com/photo-1593583845845-7d67ede93328?q=80&w=2070&auto=format&fit=crop"
                        class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                        alt="Gaming Setup"
                    />
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-[#0B0F19] via-[#0B0F19]/80 to-transparent"
                    ></div>
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-[#0B0F19] via-transparent to-transparent opacity-80"
                    ></div>
                </div>

                <div class="relative z-10 px-8 md:px-16 lg:px-20 max-w-3xl">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/10 backdrop-blur-md mb-6 animate-fade-in-up"
                    >
                        <SparklesIcon
                            class="w-4 h-4 text-yellow-400 animate-pulse"
                        />
                        <span
                            class="text-xs font-bold text-white uppercase tracking-widest"
                        >
                            Pro Gaming Gear
                        </span>
                    </div>

                    <h2
                        class="text-4xl md:text-6xl lg:text-7xl font-black text-white leading-tight mb-6 drop-shadow-xl animate-fade-in-up delay-100"
                    >
                        Dominate <br />
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400"
                        >
                            Every Match
                        </span>
                    </h2>

                    <p
                        class="text-gray-300 text-lg md:text-xl mb-10 leading-relaxed max-w-xl animate-fade-in-up delay-200"
                    >
                        Upgrade your arsenal with high-performance peripherals.
                        Engineered for speed, precision, and victory.
                    </p>

                    <div class="animate-fade-in-up delay-300">
                        <Link
                            href="/shop"
                            class="inline-flex items-center gap-3 bg-white text-[#0B0F19] px-8 py-4 rounded-xl font-bold transition-all duration-300 hover:bg-indigo-500 hover:text-white group/btn shadow-[0_0_20px_rgba(255,255,255,0.2)] hover:shadow-[0_0_30px_rgba(99,102,241,0.5)]"
                        >
                            <span class="tracking-wide"
                                >Explore Collection</span
                            >
                            <ArrowRightIcon
                                class="w-5 h-5 transition-transform duration-300 group-hover/btn:translate-x-1"
                            />
                        </Link>
                    </div>
                </div>

                <div
                    class="absolute right-10 bottom-10 hidden lg:block animate-float"
                >
                    <div
                        class="bg-black/30 backdrop-blur-xl border border-white/10 p-4 rounded-2xl flex items-center gap-4"
                    >
                        <div
                            class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center"
                        >
                            <span class="text-white font-bold text-lg"
                                >50%</span
                            >
                        </div>
                        <div>
                            <p class="text-white font-bold text-sm">
                                Flash Sale
                            </p>
                            <p class="text-gray-400 text-xs">Ends in 24h</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
@keyframes float {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}
.animate-float {
    animation: float 4s ease-in-out infinite;
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
    opacity: 0; /* Initially hidden */
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
</style>
