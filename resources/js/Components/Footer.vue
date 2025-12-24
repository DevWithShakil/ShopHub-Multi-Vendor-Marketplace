<script setup>
import { Link } from "@inertiajs/vue3";
import {
    ShoppingBagIcon,
    PhoneIcon,
    EnvelopeIcon,
} from "@heroicons/vue/24/outline";
</script>

<template>
    <footer
        class="bg-[#0B0F19] text-white pt-20 pb-10 border-t border-white/5 mt-auto relative z-10"
    >
        <div class="container mx-auto px-6 lg:px-12">
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 mb-16"
            >
                <div class="space-y-6">
                    <Link href="/" class="flex items-center gap-2 group">
                        <div
                            class="bg-gradient-to-br from-indigo-600 to-purple-600 text-white p-2.5 rounded-xl shadow-lg shadow-indigo-900/50"
                        >
                            <ShoppingBagIcon class="w-6 h-6" />
                        </div>
                        <span
                            class="text-2xl font-black tracking-tight text-white group-hover:text-indigo-400 transition"
                            >ShopHub</span
                        >
                    </Link>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        The premium destination for all your shopping needs.
                    </p>
                    <div class="space-y-2 pt-2">
                        <p
                            class="flex items-center gap-3 text-gray-400 text-sm hover:text-white transition"
                        >
                            <PhoneIcon class="w-4 h-4 text-indigo-500" /> +880
                            1568-013150
                        </p>
                        <p
                            class="flex items-center gap-3 text-gray-400 text-sm hover:text-white transition"
                        >
                            <EnvelopeIcon class="w-4 h-4 text-indigo-500" />
                            support@shophub.com
                        </p>
                    </div>
                </div>

                <div>
                    <h4
                        class="text-white font-bold text-lg mb-6 flex items-center gap-2"
                    >
                        <span
                            class="w-1.5 h-6 bg-indigo-500 rounded-full"
                        ></span>
                        {{ __("Shop") }}
                    </h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li>
                            <Link
                                href="#"
                                class="hover:text-indigo-400 hover:translate-x-1 transition-transform inline-block"
                                >New Arrivals</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="#"
                                class="hover:text-indigo-400 hover:translate-x-1 transition-transform inline-block"
                                >Best Sellers</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="#"
                                class="hover:text-indigo-400 hover:translate-x-1 transition-transform inline-block"
                                >Discounted</Link
                            >
                        </li>
                    </ul>
                </div>

                <div>
                    <h4
                        class="text-white font-bold text-lg mb-6 flex items-center gap-2"
                    >
                        <span
                            class="w-1.5 h-6 bg-purple-500 rounded-full"
                        ></span>
                        {{ __("Support") }}
                    </h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li>
                            <Link
                                :href="route('track.order')"
                                class="hover:text-indigo-400 hover:translate-x-1 transition-transform inline-block font-bold text-white"
                                >Track Order</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="#"
                                class="hover:text-indigo-400 hover:translate-x-1 transition-transform inline-block"
                                >Help Center</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="#"
                                class="hover:text-indigo-400 hover:translate-x-1 transition-transform inline-block"
                                >Returns & Refunds</Link
                            >
                        </li>
                    </ul>
                </div>

                <div
                    class="bg-white/5 p-6 rounded-2xl border border-white/5 backdrop-blur-sm"
                >
                    <h4 class="text-white font-bold text-lg mb-4">
                        {{ __("Stay in the loop") }}
                    </h4>
                    <p class="text-gray-400 text-xs mb-4 leading-relaxed">
                        Be the first to know about new collections and exclusive
                        offers.
                    </p>
                    <form class="relative">
                        <div class="relative">
                            <EnvelopeIcon
                                class="w-5 h-5 text-gray-500 absolute left-3 top-3"
                            />
                            <input
                                type="email"
                                :placeholder="__('Your email address')"
                                class="w-full bg-black/30 border border-white/10 rounded-xl py-2.5 pl-10 pr-4 text-white placeholder-gray-500 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition outline-none"
                            />
                        </div>
                        <button
                            class="w-full mt-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl transition shadow-lg shadow-indigo-900/50 text-sm"
                        >
                            {{ __("Subscribe") }}
                        </button>
                    </form>
                </div>
            </div>

            <div
                class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4"
            >
                <p class="text-gray-500 text-xs font-medium">
                    &copy; 2025 ShopHub Bangladesh. All rights reserved.
                </p>
                <div
                    class="flex gap-4 opacity-70 grayscale hover:grayscale-0 transition duration-500"
                >
                    <div
                        class="h-6 w-10 bg-white/10 rounded flex items-center justify-center text-[8px] text-gray-500"
                    >
                        VISA
                    </div>
                    <div
                        class="h-6 w-10 bg-white/10 rounded flex items-center justify-center text-[8px] text-gray-500"
                    >
                        MC
                    </div>
                    <div
                        class="h-6 w-10 bg-white/10 rounded flex items-center justify-center text-[8px] text-gray-500"
                    >
                        BKASH
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
