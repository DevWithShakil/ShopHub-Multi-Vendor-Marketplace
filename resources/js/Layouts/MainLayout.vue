<script setup>
import { ref, computed, onMounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
    MagnifyingGlassIcon,
    ShoppingBagIcon,
    Bars3Icon,
    XMarkIcon,
    HeartIcon,
    PhoneIcon,
    DevicePhoneMobileIcon,
    ChevronDownIcon,
    ArrowRightOnRectangleIcon,
    Squares2X2Icon,
    UserCircleIcon,
    LanguageIcon,
    EnvelopeIcon,
    MapPinIcon,
} from "@heroicons/vue/24/outline";
import { useCartStore } from "@/Stores/cartStore";
import { useWishlistStore } from "@/Stores/wishlistStore";

// 1. Initialize Stores
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();
const page = usePage();

// 2. Computed User
const user = computed(() => page.props.auth.user);

// 3. Sync Wishlist on Load
onMounted(() => {
    if (page.props.auth?.wishlist_ids) {
        wishlistStore.setWishlist(page.props.auth.wishlist_ids);
    }
});

const isMobileMenuOpen = ref(false);
const isUserDropdownOpen = ref(false);
</script>

<template>
    <div class="min-h-screen font-sans text-gray-800 bg-gray-50 flex flex-col">
        <div
            class="bg-gradient-to-r from-slate-900 via-indigo-900 to-slate-900 text-white text-xs py-2.5 hidden md:block border-b border-white/10"
        >
            <div
                class="container mx-auto px-4 lg:px-8 flex justify-between items-center"
            >
                <div
                    class="flex gap-6 opacity-80 hover:opacity-100 transition-opacity"
                >
                    <span class="flex items-center gap-1.5 cursor-pointer">
                        <PhoneIcon class="w-3.5 h-3.5" /> +880 1568-013150
                    </span>
                    <span class="flex items-center gap-1.5 cursor-pointer">
                        <EnvelopeIcon class="w-3.5 h-3.5" /> support@shophub.com
                    </span>
                </div>
                <div class="flex gap-6 opacity-80">
                    <Link href="#" class="hover:text-indigo-300 transition">{{
                        __("Sell on ShopHub")
                    }}</Link>
                    <Link href="#" class="hover:text-indigo-300 transition">{{
                        __("Track Order")
                    }}</Link>
                </div>
            </div>
        </div>

        <nav
            class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-white/20 shadow-sm transition-all duration-300"
        >
            <div class="container mx-auto px-4 lg:px-8">
                <div
                    class="flex items-center justify-between h-20 gap-4 md:gap-8"
                >
                    <Link
                        href="/"
                        class="flex-shrink-0 flex items-center gap-2 group"
                    >
                        <div
                            class="bg-gradient-to-tr from-indigo-600 to-purple-600 text-white p-2.5 rounded-xl shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300"
                        >
                            <ShoppingBagIcon class="w-6 h-6" />
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-slate-800 to-slate-600 tracking-tight leading-none"
                                >ShopHub</span
                            >
                            <span
                                class="text-[10px] font-bold text-indigo-500 tracking-[0.2em] uppercase"
                                >{{ __("Premium Store") }}</span
                            >
                        </div>
                    </Link>

                    <div class="hidden md:flex flex-1 max-w-2xl relative group">
                        <input
                            type="text"
                            :placeholder="__('Search for products...')"
                            class="w-full bg-slate-100/50 border border-slate-200 rounded-full py-3 pl-5 pr-14 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-inner"
                        />
                        <button
                            class="absolute right-1.5 top-1.5 bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700 transition-colors shadow-md"
                        >
                            <MagnifyingGlassIcon class="w-5 h-5" />
                        </button>
                    </div>

                    <div class="flex items-center gap-3 md:gap-6">
                        <div
                            class="hidden md:flex items-center bg-slate-100 rounded-full p-1 border border-slate-200"
                        >
                            <a
                                href="/language/bn"
                                class="px-3 py-1 text-xs font-bold rounded-full transition-all"
                                :class="
                                    $page.props.locale === 'bn'
                                        ? 'bg-white text-indigo-600 shadow-sm'
                                        : 'text-gray-500 hover:text-gray-900'
                                "
                                >BN</a
                            >
                            <a
                                href="/language/en"
                                class="px-3 py-1 text-xs font-bold rounded-full transition-all"
                                :class="
                                    $page.props.locale === 'en'
                                        ? 'bg-white text-indigo-600 shadow-sm'
                                        : 'text-gray-500 hover:text-gray-900'
                                "
                                >EN</a
                            >
                        </div>

                        <button
                            @click="isMobileMenuOpen = true"
                            class="md:hidden text-gray-700 hover:bg-slate-100 p-2 rounded-lg transition"
                        >
                            <Bars3Icon class="w-7 h-7" />
                        </button>

                        <Link
                            :href="route('wishlist.index')"
                            class="hidden md:flex flex-col items-center text-gray-500 hover:text-rose-500 transition group relative"
                        >
                            <div class="relative p-1">
                                <HeartIcon
                                    class="w-7 h-7 group-hover:scale-110 transition-transform duration-300"
                                />
                                <span
                                    v-if="wishlistStore.count > 0"
                                    class="absolute -top-1 -right-1 bg-rose-500 text-white text-[10px] h-4 w-4 rounded-full flex items-center justify-center font-bold shadow-sm animate-pulse border border-white"
                                >
                                    {{ wishlistStore.count }}
                                </span>
                            </div>
                        </Link>

                        <Link
                            :href="route('cart.index')"
                            class="flex flex-col items-center text-gray-500 hover:text-indigo-600 transition group relative"
                        >
                            <div class="relative p-1">
                                <ShoppingBagIcon
                                    class="w-7 h-7 group-hover:scale-110 transition-transform duration-300"
                                />
                                <span
                                    v-if="cartStore.itemCount > 0"
                                    class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[10px] h-4 w-4 rounded-full flex items-center justify-center font-bold shadow-sm animate-bounce border border-white"
                                >
                                    {{ cartStore.itemCount }}
                                </span>
                            </div>
                        </Link>

                        <div class="hidden md:block relative">
                            <div v-if="user">
                                <button
                                    @click="
                                        isUserDropdownOpen = !isUserDropdownOpen
                                    "
                                    class="flex items-center gap-3 hover:bg-slate-50 pl-1 pr-3 py-1 rounded-full transition border border-transparent hover:border-slate-200 group"
                                >
                                    <img
                                        :src="
                                            user.profile_photo_url ||
                                            `https://ui-avatars.com/api/?name=${user.name}&background=4f46e5&color=fff`
                                        "
                                        class="w-9 h-9 rounded-full border-2 border-white shadow-sm"
                                        alt="Avatar"
                                    />
                                    <div class="text-left hidden lg:block">
                                        <p
                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-wider"
                                        >
                                            {{ __("Hello") }}
                                        </p>
                                        <p
                                            class="text-sm font-bold text-gray-800 leading-none group-hover:text-indigo-600 transition"
                                        >
                                            {{ user.name.split(" ")[0] }}
                                        </p>
                                    </div>
                                    <ChevronDownIcon
                                        class="w-4 h-4 text-gray-400 group-hover:rotate-180 transition-transform duration-300"
                                    />
                                </button>

                                <div
                                    v-if="isUserDropdownOpen"
                                    class="absolute right-0 mt-4 w-60 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 animate-fade-in-up origin-top-right"
                                >
                                    <div
                                        class="px-5 py-4 border-b border-gray-50 bg-slate-50/50"
                                    >
                                        <p
                                            class="text-sm font-bold text-gray-900 truncate"
                                        >
                                            {{ user.name }}
                                        </p>
                                        <p
                                            class="text-xs text-gray-500 truncate"
                                        >
                                            {{ user.email }}
                                        </p>
                                    </div>
                                    <Link
                                        href="/dashboard"
                                        class="flex items-center gap-3 px-5 py-3 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors"
                                    >
                                        <Squares2X2Icon class="w-4 h-4" />
                                        {{ __("Dashboard") }}
                                    </Link>
                                    <Link
                                        href="/dashboard/orders"
                                        class="flex items-center gap-3 px-5 py-3 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors"
                                    >
                                        <ShoppingBagIcon class="w-4 h-4" />
                                        {{ __("My Orders") }}
                                    </Link>
                                    <div
                                        class="border-t border-gray-50 my-1"
                                    ></div>
                                    <Link
                                        href="/logout"
                                        method="post"
                                        as="button"
                                        class="w-full text-left flex items-center gap-3 px-5 py-3 text-sm text-rose-500 hover:bg-rose-50 transition-colors"
                                    >
                                        <ArrowRightOnRectangleIcon
                                            class="w-4 h-4"
                                        />
                                        {{ __("Logout") }}
                                    </Link>
                                </div>
                            </div>

                            <div v-else class="flex items-center gap-2">
                                <Link
                                    href="/login"
                                    class="text-gray-600 font-bold hover:text-indigo-600 text-sm px-3 py-2 hover:bg-slate-50 rounded-lg transition"
                                    >{{ __("Login") }}</Link
                                >
                                <Link
                                    href="/register"
                                    class="bg-black text-white px-5 py-2.5 rounded-full font-bold text-sm hover:bg-gray-800 transition shadow-lg shadow-gray-200"
                                    >{{ __("Register") }}</Link
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:hidden pb-4">
                    <div class="relative">
                        <input
                            type="text"
                            :placeholder="__('Search for products...')"
                            class="w-full bg-slate-100 rounded-xl py-3 pl-11 pr-4 text-sm focus:ring-2 focus:ring-indigo-500/20 border-none"
                        />
                        <MagnifyingGlassIcon
                            class="w-5 h-5 text-gray-400 absolute left-4 top-3"
                        />
                    </div>
                </div>
            </div>
        </nav>

        <div v-if="isMobileMenuOpen" class="relative z-[60]">
            <div
                @click="isMobileMenuOpen = false"
                class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
            ></div>
            <div
                class="fixed inset-y-0 left-0 w-4/5 max-w-xs bg-white shadow-2xl transform transition-transform duration-300 ease-in-out flex flex-col"
            >
                <div
                    class="bg-gradient-to-br from-slate-900 to-indigo-900 p-6 text-white relative overflow-hidden"
                >
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"
                    ></div>

                    <div
                        v-if="user"
                        class="flex items-center gap-4 relative z-10"
                    >
                        <img
                            :src="
                                user.profile_photo_url ||
                                `https://ui-avatars.com/api/?name=${user.name}&background=fff&color=4f46e5`
                            "
                            class="w-14 h-14 rounded-full border-2 border-white/50 shadow-md"
                            alt="Avatar"
                        />
                        <div>
                            <p class="font-bold text-lg leading-tight">
                                {{ user.name }}
                            </p>
                            <p class="text-xs text-indigo-200 mt-1">
                                {{ user.email }}
                            </p>
                        </div>
                    </div>
                    <div v-else class="flex flex-col gap-4 relative z-10">
                        <p class="font-bold text-xl">
                            {{ __("Welcome Guest!") }}
                        </p>
                        <div class="flex gap-3">
                            <Link
                                href="/login"
                                class="flex-1 text-center py-2 bg-white text-indigo-900 rounded-lg font-bold text-sm shadow-sm"
                                >{{ __("Login") }}</Link
                            >
                            <Link
                                href="/register"
                                class="flex-1 text-center py-2 border border-white/30 text-white rounded-lg font-bold text-sm hover:bg-white/10"
                                >{{ __("Register") }}</Link
                            >
                        </div>
                    </div>
                    <button
                        @click="isMobileMenuOpen = false"
                        class="absolute top-4 right-4 p-1 hover:bg-white/20 rounded-full transition"
                    >
                        <XMarkIcon class="w-6 h-6 text-white" />
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-2">
                    <div
                        class="flex items-center justify-between px-4 py-3 mb-6 bg-slate-50 rounded-xl border border-slate-100"
                    >
                        <div class="flex items-center gap-2 text-slate-600">
                            <LanguageIcon class="w-5 h-5" />
                            <span class="text-sm font-bold">Language</span>
                        </div>
                        <div class="flex bg-white rounded-lg p-1 shadow-sm">
                            <a
                                href="/language/bn"
                                class="px-3 py-1 text-xs font-bold rounded-md transition"
                                :class="
                                    $page.props.locale === 'bn'
                                        ? 'bg-indigo-600 text-white'
                                        : 'text-gray-500'
                                "
                                >BN</a
                            >
                            <a
                                href="/language/en"
                                class="px-3 py-1 text-xs font-bold rounded-md transition"
                                :class="
                                    $page.props.locale === 'en'
                                        ? 'bg-indigo-600 text-white'
                                        : 'text-gray-500'
                                "
                                >EN</a
                            >
                        </div>
                    </div>

                    <p
                        class="text-xs font-bold text-gray-400 uppercase tracking-wider px-2 pb-2"
                    >
                        {{ __("Menu") }}
                    </p>
                    <Link
                        href="/"
                        class="flex items-center gap-4 px-4 py-3 rounded-xl text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition"
                        ><ShoppingBagIcon class="w-5 h-5" />
                        {{ __("Home") }}</Link
                    >
                    <Link
                        :href="route('wishlist.index')"
                        class="flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 hover:bg-rose-50 hover:text-rose-600 font-medium transition"
                    >
                        <div class="flex items-center gap-4">
                            <HeartIcon class="w-5 h-5" /> {{ __("Wishlist") }}
                        </div>
                        <span
                            v-if="wishlistStore.count > 0"
                            class="bg-rose-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"
                            >{{ wishlistStore.count }}</span
                        >
                    </Link>
                    <Link
                        :href="route('cart.index')"
                        class="flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition"
                    >
                        <div class="flex items-center gap-4">
                            <ShoppingBagIcon class="w-5 h-5" /> {{ __("Cart") }}
                        </div>
                        <span
                            v-if="cartStore.itemCount > 0"
                            class="bg-indigo-600 text-white text-xs font-bold px-2 py-0.5 rounded-full"
                            >{{ cartStore.itemCount }}</span
                        >
                    </Link>

                    <div class="my-4 border-t border-gray-100"></div>
                    <Link
                        v-if="user"
                        href="/dashboard"
                        class="flex items-center gap-4 px-4 py-3 rounded-xl text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium transition"
                        ><Squares2X2Icon class="w-5 h-5" />
                        {{ __("Dashboard") }}</Link
                    >
                    <Link
                        v-if="user"
                        href="/logout"
                        method="post"
                        as="button"
                        class="flex items-center gap-4 px-4 py-3 rounded-xl text-rose-500 hover:bg-rose-50 font-medium transition w-full"
                        ><ArrowRightOnRectangleIcon class="w-5 h-5" />
                        {{ __("Logout") }}</Link
                    >
                </div>
            </div>
        </div>

        <main class="flex-grow">
            <slot />
        </main>

        <footer
            class="bg-[#0B0F19] text-white pt-20 pb-10 border-t border-white/5 mt-auto"
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
                                class="text-2xl font-black tracking-tight text-white"
                                >ShopHub</span
                            >
                        </Link>
                        <p class="text-gray-400 leading-relaxed text-sm">
                            The premium destination for all your shopping needs.
                            We ensure quality, speed, and trust in every order.
                        </p>

                        <div class="flex items-center gap-4 pt-2">
                            <div
                                class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-indigo-600 transition cursor-pointer text-gray-400 hover:text-white"
                            >
                                <i class="fa-brands fa-facebook-f text-lg"></i>
                                <span class="font-bold text-xs">FB</span>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-sky-500 transition cursor-pointer text-gray-400 hover:text-white"
                            >
                                <span class="font-bold text-xs">TW</span>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-pink-600 transition cursor-pointer text-gray-400 hover:text-white"
                            >
                                <span class="font-bold text-xs">IG</span>
                            </div>
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
                            <li>
                                <Link
                                    href="#"
                                    class="hover:text-indigo-400 hover:translate-x-1 transition-transform inline-block"
                                    >Accessories</Link
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
                                    href="#"
                                    class="hover:text-indigo-400 hover:translate-x-1 transition-transform inline-block"
                                    >Help Center</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="#"
                                    class="hover:text-indigo-400 hover:translate-x-1 transition-transform inline-block"
                                    >Order Tracking</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="#"
                                    class="hover:text-indigo-400 hover:translate-x-1 transition-transform inline-block"
                                    >Returns & Refunds</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="#"
                                    class="hover:text-indigo-400 hover:translate-x-1 transition-transform inline-block"
                                    >Contact Us</Link
                                >
                            </li>
                        </ul>
                    </div>

                    <div
                        class="bg-white/5 p-6 rounded-2xl border border-white/5"
                    >
                        <h4 class="text-white font-bold text-lg mb-4">
                            {{ __("Stay in the loop") }}
                        </h4>
                        <p class="text-gray-400 text-xs mb-4 leading-relaxed">
                            Be the first to know about new collections and
                            exclusive offers.
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
                        <img
                            src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/2560px-Visa_Inc._logo.svg.png"
                            class="h-4 object-contain"
                            alt="Visa"
                        />
                        <img
                            src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png"
                            class="h-6 object-contain"
                            alt="Mastercard"
                        />
                        <img
                            src="https://freepnglogo.com/images/all_img/1701670291bKash-App-Logo-PNG.png"
                            class="h-6 object-contain"
                            alt="Bkash"
                        />
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 0.2s ease-out forwards;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
