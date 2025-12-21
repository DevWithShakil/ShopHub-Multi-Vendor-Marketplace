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
} from "@heroicons/vue/24/outline";
import { useCartStore } from "@/Stores/cartStore";
import { useWishlistStore } from "@/Stores/wishlistStore";

// 1. Initialize Stores
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();
const page = usePage();

// 2. Computed User
const user = computed(() => page.props.auth.user);

// 3. Sync Wishlist on Load (Fixed logic)
onMounted(() => {
    // যদি ব্যাকএন্ড থেকে wishlist_ids আসে, তবে স্টোরে সেট করো
    if (page.props.auth?.wishlist_ids) {
        wishlistStore.setWishlist(page.props.auth.wishlist_ids);
    }
});

const isMobileMenuOpen = ref(false);
const isUserDropdownOpen = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-50 font-sans text-gray-800">
        <div class="bg-indigo-900 text-white text-xs py-2 hidden md:block">
            <div
                class="container mx-auto px-4 lg:px-8 flex justify-between items-center"
            >
                <div class="flex gap-6">
                    <span
                        class="flex items-center gap-1 hover:text-indigo-200 cursor-pointer"
                    >
                        <PhoneIcon class="w-3 h-3" /> +880 1568-013150
                    </span>
                    <span
                        class="flex items-center gap-1 hover:text-indigo-200 cursor-pointer"
                    >
                        <DevicePhoneMobileIcon class="w-3 h-3" />
                        {{ __("Save more on App") }}
                    </span>
                </div>
                <div class="flex gap-4">
                    <Link href="#" class="hover:text-indigo-200">{{
                        __("Sell on ShopHub")
                    }}</Link>
                    <Link href="#" class="hover:text-indigo-200">{{
                        __("Track Order")
                    }}</Link>
                </div>
            </div>
        </div>

        <nav
            class="sticky top-0 z-50 bg-white shadow-md border-b border-indigo-50"
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
                            class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-2 rounded-lg shadow-lg group-hover:rotate-3 transition-transform"
                        >
                            <ShoppingBagIcon class="w-7 h-7" />
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-2xl font-black text-indigo-900 leading-none tracking-tight"
                                >ShopHub</span
                            >
                            <span
                                class="text-[10px] font-bold text-gray-500 tracking-widest uppercase"
                                >{{ __("Premium Store") }}</span
                            >
                        </div>
                    </Link>

                    <div class="hidden md:flex flex-1 max-w-2xl relative">
                        <input
                            type="text"
                            :placeholder="__('Search in ShopHub...')"
                            class="w-full bg-gray-100 border-2 border-transparent focus:bg-white focus:border-indigo-500 rounded-l-lg py-2.5 px-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-0 transition-all"
                        />
                        <button
                            class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 rounded-r-lg hover:opacity-90 transition-opacity flex items-center justify-center"
                        >
                            <MagnifyingGlassIcon class="w-6 h-6" />
                        </button>
                    </div>

                    <div class="flex items-center gap-3 md:gap-5">
                        <div class="hidden md:flex items-center gap-2 mr-2">
                            <a
                                href="/language/bn"
                                class="px-2 py-1 text-xs font-bold border rounded transition"
                                :class="
                                    $page.props.locale === 'bn'
                                        ? 'bg-indigo-600 text-white border-indigo-600'
                                        : 'text-gray-600 hover:bg-gray-100'
                                "
                                >বাংলা</a
                            >
                            <a
                                href="/language/en"
                                class="px-2 py-1 text-xs font-bold border rounded transition"
                                :class="
                                    $page.props.locale === 'en'
                                        ? 'bg-indigo-600 text-white border-indigo-600'
                                        : 'text-gray-600 hover:bg-gray-100'
                                "
                                >EN</a
                            >
                        </div>

                        <button
                            @click="isMobileMenuOpen = true"
                            class="md:hidden text-gray-600 p-2"
                        >
                            <Bars3Icon class="w-7 h-7" />
                        </button>

                        <Link
                            :href="route('wishlist.index')"
                            class="hidden md:flex flex-col items-center text-gray-500 hover:text-red-500 transition group relative"
                        >
                            <div class="relative">
                                <HeartIcon
                                    class="w-6 h-6 group-hover:scale-110 transition-transform"
                                />
                                <span
                                    v-if="wishlistStore.count > 0"
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] h-4 w-4 rounded-full flex items-center justify-center font-bold animate-pulse"
                                >
                                    {{ wishlistStore.count }}
                                </span>
                                <span
                                    v-else
                                    class="absolute -top-1 -right-1 bg-gray-400 text-white text-[10px] h-4 w-4 rounded-full flex items-center justify-center font-bold"
                                    >0</span
                                >
                            </div>
                            <span class="text-[10px] font-medium mt-0.5">{{
                                __("Wishlist")
                            }}</span>
                        </Link>

                        <Link
                            :href="route('cart.index')"
                            class="hidden md:flex flex-col items-center text-gray-500 hover:text-indigo-600 transition group relative"
                        >
                            <div class="relative">
                                <ShoppingBagIcon
                                    class="w-6 h-6 group-hover:scale-110 transition-transform"
                                />
                                <span
                                    v-if="cartStore.itemCount > 0"
                                    class="absolute -top-1 -right-2 bg-orange-500 text-white text-[10px] h-4 w-5 rounded-full flex items-center justify-center font-bold border-2 border-white animate-bounce"
                                >
                                    {{ cartStore.itemCount }}
                                </span>
                            </div>
                            <span class="text-[10px] font-medium mt-0.5">{{
                                __("Cart")
                            }}</span>
                        </Link>

                        <div class="hidden md:block relative">
                            <div v-if="user">
                                <button
                                    @click="
                                        isUserDropdownOpen = !isUserDropdownOpen
                                    "
                                    class="flex items-center gap-2 hover:bg-gray-50 p-1.5 rounded-lg transition border border-transparent hover:border-gray-200"
                                >
                                    <img
                                        :src="
                                            user.profile_photo_url ||
                                            `https://ui-avatars.com/api/?name=${user.name}&background=4f46e5&color=fff`
                                        "
                                        class="w-8 h-8 rounded-full border border-gray-200"
                                        alt="Avatar"
                                    />
                                    <div class="text-left">
                                        <p class="text-xs text-gray-500">
                                            {{ __("Hello") }},
                                        </p>
                                        <p
                                            class="text-sm font-bold text-gray-800 leading-none"
                                        >
                                            {{ user.name.split(" ")[0] }}
                                        </p>
                                    </div>
                                    <ChevronDownIcon
                                        class="w-4 h-4 text-gray-400"
                                    />
                                </button>

                                <div
                                    v-if="isUserDropdownOpen"
                                    class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 animate-fade-in-up"
                                >
                                    <div
                                        class="px-4 py-3 border-b border-gray-50 bg-gray-50/50"
                                    >
                                        <p
                                            class="text-sm font-bold text-gray-800"
                                        >
                                            {{ user.name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ user.email }}
                                        </p>
                                    </div>
                                    <Link
                                        href="/dashboard"
                                        class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-600"
                                    >
                                        <Squares2X2Icon class="w-4 h-4" />
                                        {{ __("Dashboard") }}
                                    </Link>
                                    <Link
                                        href="/dashboard/orders"
                                        class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-600"
                                    >
                                        <ShoppingBagIcon class="w-4 h-4" />
                                        {{ __("My Orders") }}
                                    </Link>
                                    <Link
                                        href="/dashboard/profile"
                                        class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-600"
                                    >
                                        <UserCircleIcon class="w-4 h-4" />
                                        {{ __("Profile Settings") }}
                                    </Link>
                                    <div
                                        class="border-t border-gray-50 my-1"
                                    ></div>
                                    <Link
                                        href="/logout"
                                        method="post"
                                        as="button"
                                        class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50"
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
                                    class="text-gray-600 font-bold hover:text-indigo-600 text-sm"
                                    >{{ __("Login") }}</Link
                                >
                                <span class="text-gray-300">|</span>
                                <Link
                                    href="/register"
                                    class="text-gray-600 font-bold hover:text-indigo-600 text-sm"
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
                            class="w-full bg-gray-100 rounded-lg py-2.5 pl-10 pr-4 text-sm focus:ring-1 focus:ring-indigo-500 border-none"
                        />
                        <MagnifyingGlassIcon
                            class="w-5 h-5 text-gray-400 absolute left-3 top-2.5"
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
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 text-white relative"
                >
                    <div v-if="user" class="flex items-center gap-3">
                        <img
                            :src="
                                user.profile_photo_url ||
                                `https://ui-avatars.com/api/?name=${user.name}&background=fff&color=4f46e5`
                            "
                            class="w-12 h-12 rounded-full border-2 border-white/50"
                            alt="Avatar"
                        />
                        <div>
                            <p class="font-bold text-lg">{{ user.name }}</p>
                            <p class="text-xs text-indigo-100">
                                {{ user.email }}
                            </p>
                        </div>
                    </div>
                    <div v-else class="flex flex-col gap-3">
                        <p class="font-bold text-lg mb-1">
                            {{ __("Welcome Guest!") }}
                        </p>
                        <div class="flex gap-3">
                            <Link
                                href="/login"
                                class="px-4 py-1.5 bg-white text-indigo-600 rounded text-sm font-bold"
                                >{{ __("Login") }}</Link
                            >
                            <Link
                                href="/register"
                                class="px-4 py-1.5 border border-white text-white rounded text-sm font-bold"
                                >{{ __("Register") }}</Link
                            >
                        </div>
                    </div>
                    <button
                        @click="isMobileMenuOpen = false"
                        class="absolute top-4 right-4 p-1 hover:bg-white/20 rounded-full"
                    >
                        <XMarkIcon class="w-6 h-6 text-white" />
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-1">
                    <div
                        class="flex items-center justify-between px-3 py-3 mb-4 bg-gray-50 rounded-lg border border-gray-100"
                    >
                        <div class="flex items-center gap-2 text-gray-600">
                            <LanguageIcon class="w-5 h-5" />
                            <span class="text-sm font-bold">Language</span>
                        </div>
                        <div class="flex gap-2">
                            <a
                                href="/language/bn"
                                class="px-3 py-1 text-xs font-bold border rounded transition"
                                :class="
                                    $page.props.locale === 'bn'
                                        ? 'bg-indigo-600 text-white border-indigo-600'
                                        : 'bg-white text-gray-600 border-gray-300'
                                "
                                >বাংলা</a
                            >
                            <a
                                href="/language/en"
                                class="px-3 py-1 text-xs font-bold border rounded transition"
                                :class="
                                    $page.props.locale === 'en'
                                        ? 'bg-indigo-600 text-white border-indigo-600'
                                        : 'bg-white text-gray-600 border-gray-300'
                                "
                                >EN</a
                            >
                        </div>
                    </div>

                    <p
                        class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 px-2"
                    >
                        {{ __("Menu") }}
                    </p>

                    <Link
                        href="/"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium"
                    >
                        <ShoppingBagIcon class="w-5 h-5" /> {{ __("Home") }}
                    </Link>

                    <Link
                        :href="route('wishlist.index')"
                        class="flex items-center justify-between px-3 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium"
                    >
                        <div class="flex items-center gap-3">
                            <HeartIcon class="w-5 h-5" /> {{ __("Wishlist") }}
                        </div>
                        <span
                            v-if="wishlistStore.count > 0"
                            class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"
                        >
                            {{ wishlistStore.count }}
                        </span>
                    </Link>

                    <Link
                        :href="route('cart.index')"
                        class="flex items-center justify-between px-3 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium"
                    >
                        <div class="flex items-center gap-3">
                            <ShoppingBagIcon class="w-5 h-5" /> {{ __("Cart") }}
                        </div>
                        <span
                            v-if="cartStore.itemCount > 0"
                            class="bg-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"
                        >
                            {{ cartStore.itemCount }}
                        </span>
                    </Link>

                    <div class="border-t border-gray-100 my-2"></div>

                    <p
                        class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 px-2 mt-4"
                    >
                        {{ __("Account") }}
                    </p>
                    <Link
                        v-if="user"
                        href="/dashboard"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium"
                    >
                        <Squares2X2Icon class="w-5 h-5" /> {{ __("Dashboard") }}
                    </Link>
                    <Link
                        v-if="user"
                        href="/dashboard/orders"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium"
                    >
                        <ShoppingBagIcon class="w-5 h-5" />
                        {{ __("My Orders") }}
                    </Link>
                    <Link
                        v-if="user"
                        href="/dashboard/profile"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium"
                    >
                        <UserCircleIcon class="w-5 h-5" />
                        {{ __("Profile Settings") }}
                    </Link>
                </div>

                <div v-if="user" class="p-4 border-t border-gray-100">
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="flex items-center justify-center gap-2 w-full py-2.5 bg-red-50 text-red-600 rounded-lg font-bold hover:bg-red-100 transition"
                    >
                        <ArrowRightOnRectangleIcon class="w-5 h-5" />
                        {{ __("Logout") }}
                    </Link>
                </div>
            </div>
        </div>

        <main>
            <slot />
        </main>

        <footer class="bg-indigo-950 text-gray-300 py-12 mt-12">
            <div class="container mx-auto px-6 text-center">
                <p class="text-sm">
                    &copy; 2025 ShopHub Bangladesh. All rights reserved.
                </p>
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
