<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import {
    MagnifyingGlassIcon,
    ShoppingBagIcon,
    UserIcon,
    Bars3Icon,
    XMarkIcon,
    HeartIcon,
} from "@heroicons/vue/24/outline";

const isMobileMenuOpen = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-50 font-sans text-gray-800">
        <nav
            class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm"
        >
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <Link href="/" class="flex items-center gap-2 group">
                        <div
                            class="bg-gradient-to-tr from-indigo-600 to-purple-600 text-white p-2 rounded-lg shadow-lg group-hover:shadow-indigo-500/30 transition-all duration-300"
                        >
                            <ShoppingBagIcon class="w-6 h-6" />
                        </div>
                        <span
                            class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600"
                        >
                            ShopHub
                        </span>
                    </Link>

                    <div class="hidden md:flex flex-1 max-w-lg mx-8 relative">
                        <input
                            type="text"
                            placeholder="Search for products..."
                            class="w-full bg-gray-100 border-none rounded-full py-3 pl-12 pr-4 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all duration-300 shadow-inner"
                        />
                        <MagnifyingGlassIcon
                            class="w-5 h-5 text-gray-400 absolute left-4 top-3.5"
                        />
                    </div>

                    <div class="hidden md:flex items-center gap-6">
                        <Link
                            href="#"
                            class="relative hover:text-indigo-600 transition-colors"
                        >
                            <HeartIcon class="w-7 h-7" />
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                                >0</span
                            >
                        </Link>

                        <Link
                            href="#"
                            class="relative hover:text-indigo-600 transition-colors"
                        >
                            <ShoppingBagIcon class="w-7 h-7" />
                            <span
                                class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                                >2</span
                            >
                        </Link>

                        <Link
                            href="/login"
                            class="flex items-center gap-2 hover:text-indigo-600 transition font-medium"
                        >
                            <UserIcon class="w-6 h-6" />
                            <span>Account</span>
                        </Link>
                    </div>

                    <button
                        @click="isMobileMenuOpen = !isMobileMenuOpen"
                        class="md:hidden text-gray-600"
                    >
                        <Bars3Icon v-if="!isMobileMenuOpen" class="w-8 h-8" />
                        <XMarkIcon v-else class="w-8 h-8" />
                    </button>
                </div>
            </div>

            <transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="transform -translate-y-4 opacity-0"
                enter-to-class="transform translate-y-0 opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="transform translate-y-0 opacity-100"
                leave-to-class="transform -translate-y-4 opacity-0"
            >
                <div
                    v-if="isMobileMenuOpen"
                    class="md:hidden bg-white border-b p-4 space-y-4 shadow-lg"
                >
                    <input
                        type="text"
                        placeholder="Search..."
                        class="w-full bg-gray-100 rounded-lg p-3"
                    />
                    <Link href="#" class="block py-2 text-gray-600 font-medium"
                        >Wishlist</Link
                    >
                    <Link href="#" class="block py-2 text-gray-600 font-medium"
                        >Cart</Link
                    >
                    <Link
                        href="/login"
                        class="block py-2 text-indigo-600 font-bold"
                        >Login / Register</Link
                    >
                </div>
            </transition>
        </nav>

        <main>
            <slot />
        </main>

        <footer class="bg-gray-900 text-gray-300 py-12 mt-12">
            <div
                class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-8"
            >
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">ShopHub</h3>
                    <p class="text-sm leading-relaxed">
                        The premium destination for all your shopping needs.
                        Quality products, fast delivery.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="#" class="hover:text-indigo-400 transition"
                                >About Us</a
                            >
                        </li>
                        <li>
                            <a href="#" class="hover:text-indigo-400 transition"
                                >Contact</a
                            >
                        </li>
                        <li>
                            <a href="#" class="hover:text-indigo-400 transition"
                                >Privacy Policy</a
                            >
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Customer Care</h4>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="#" class="hover:text-indigo-400 transition"
                                >Track Order</a
                            >
                        </li>
                        <li>
                            <a href="#" class="hover:text-indigo-400 transition"
                                >Returns</a
                            >
                        </li>
                        <li>
                            <a href="#" class="hover:text-indigo-400 transition"
                                >FAQs</a
                            >
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Newsletter</h4>
                    <div class="flex">
                        <input
                            type="email"
                            placeholder="Your email"
                            class="bg-gray-800 border-none rounded-l-lg w-full text-sm"
                        />
                        <button
                            class="bg-indigo-600 px-4 rounded-r-lg hover:bg-indigo-700 transition"
                        >
                            Subscribe
                        </button>
                    </div>
                </div>
            </div>
            <div
                class="text-center text-xs text-gray-500 mt-12 border-t border-gray-800 pt-8"
            >
                &copy; 2024 ShopHub. All rights reserved.
            </div>
        </footer>
    </div>
</template>
