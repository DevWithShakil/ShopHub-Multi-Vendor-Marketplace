<script setup>
import { Link } from "@inertiajs/vue3";
import { ArrowRightIcon } from "@heroicons/vue/24/solid";
</script>

<template>
    <section class="py-16 bg-[#0B0F19]">
        <div class="container mx-auto px-4 lg:px-8">
            <div
                class="relative rounded-[3rem] overflow-hidden min-h-[400px] flex items-center border border-white/10 group"
            >
                <div class="absolute inset-0">
                    <img
                        src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=2070&auto=format&fit=crop"
                        class="w-full h-full object-cover opacity-40 group-hover:scale-105 transition-transform duration-1000"
                    />
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent"
                    ></div>
                </div>

                <div class="relative z-10 px-8 md:px-16 max-w-2xl">
                    <span
                        class="inline-block px-4 py-1 rounded-full bg-indigo-600 text-white text-xs font-bold uppercase tracking-widest mb-4"
                    >
                        Limited Edition
                    </span>
                    <h2
                        class="text-4xl md:text-6xl font-black text-white leading-tight mb-6"
                    >
                        Level Up Your <br />
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400"
                            >Gaming Setup</span
                        >
                    </h2>
                    <p class="text-gray-300 text-lg mb-8 leading-relaxed">
                        Experience precision and performance with our exclusive
                        collection of gaming peripherals. Up to 40% OFF this
                        week.
                    </p>
                    <Link
                        href="/products"
                        class="inline-flex items-center gap-3 bg-white text-gray-900 px-8 py-4 rounded-xl font-bold hover:bg-indigo-500 hover:text-white transition-all shadow-[0_0_20px_rgba(255,255,255,0.3)] hover:shadow-[0_0_30px_rgba(99,102,241,0.6)]"
                    >
                        Shop Now <ArrowRightIcon class="w-5 h-5" />
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>
