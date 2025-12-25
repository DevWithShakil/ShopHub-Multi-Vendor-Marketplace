<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import {
    HomeIcon,
    Squares2X2Icon,
    ShoppingCartIcon,
    HeartIcon,
    UserIcon,
} from "@heroicons/vue/24/outline";
import { useCartStore } from "@/Stores/cartStore";

const cartStore = useCartStore();
const page = usePage();

const isActive = (route) => page.url.startsWith(route);
</script>

<template>
    <div
        class="fixed bottom-0 left-0 w-full z-50 bg-[#151925]/90 backdrop-blur-lg border-t border-white/10 md:hidden pb-safe"
    >
        <div class="flex justify-around items-center h-16">
            <Link
                href="/"
                class="flex flex-col items-center gap-1 text-[10px] font-medium transition-colors"
                :class="
                    isActive('/')
                        ? 'text-indigo-500'
                        : 'text-gray-400 hover:text-white'
                "
            >
                <HomeIcon class="w-6 h-6" />
                <span>Home</span>
            </Link>

            <Link
                href="/products"
                class="flex flex-col items-center gap-1 text-[10px] font-medium transition-colors"
                :class="
                    isActive('/products')
                        ? 'text-indigo-500'
                        : 'text-gray-400 hover:text-white'
                "
            >
                <Squares2X2Icon class="w-6 h-6" />
                <span>Shop</span>
            </Link>

            <Link
                href="/cart"
                class="relative -top-5 bg-indigo-600 p-3 rounded-full shadow-lg shadow-indigo-500/40 border-4 border-[#0B0F19] text-white"
            >
                <ShoppingCartIcon class="w-6 h-6" />
                <span
                    v-if="cartStore.itemCount > 0"
                    class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full text-[9px] flex items-center justify-center font-bold border border-[#0B0F19]"
                >
                    {{ cartStore.itemCount }}
                </span>
            </Link>

            <Link
                href="/wishlist"
                class="flex flex-col items-center gap-1 text-[10px] font-medium transition-colors"
                :class="
                    isActive('/wishlist')
                        ? 'text-indigo-500'
                        : 'text-gray-400 hover:text-white'
                "
            >
                <HeartIcon class="w-6 h-6" />
                <span>Wishlist</span>
            </Link>

            <Link
                href="/dashboard"
                class="flex flex-col items-center gap-1 text-[10px] font-medium transition-colors"
                :class="
                    isActive('/dashboard')
                        ? 'text-indigo-500'
                        : 'text-gray-400 hover:text-white'
                "
            >
                <UserIcon class="w-6 h-6" />
                <span>Account</span>
            </Link>
        </div>
    </div>
</template>

<style scoped>
/* Safe area padding for iPhones with notch/home bar */
.pb-safe {
    padding-bottom: env(safe-area-inset-bottom);
}
</style>
