<script setup>
import { Head } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import ActivePromos from "@/Components/Sections/ActivePromos.vue"; // আগের তৈরি করা কম্পোনেন্ট
import { SparklesIcon } from "@heroicons/vue/24/solid";
</script>

<template>
    <Head title="Exclusive Offers" />

    <MainLayout>
        <div class="min-h-screen bg-[#0B0F19] py-16 relative overflow-hidden">
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-[500px] bg-purple-600/10 rounded-full blur-[120px] pointer-events-none"
            ></div>

            <div class="container mx-auto px-4 lg:px-8 relative z-10">
                <div class="text-center mb-16">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-400 text-xs font-bold uppercase tracking-widest mb-4"
                    >
                        <SparklesIcon class="w-4 h-4" /> Save More Today
                    </div>
                    <h1
                        class="text-4xl md:text-6xl font-black text-white tracking-tight mb-4"
                    >
                        Exclusive
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-500"
                            >Deals & Coupons</span
                        >
                    </h1>
                    <p class="text-gray-400 max-w-2xl mx-auto">
                        Copy these codes and apply them at checkout to get
                        amazing discounts. Hurry up, they expire soon!
                    </p>
                </div>

                <ActivePromos />
            </div>
        </div>
    </MainLayout>
</template>
