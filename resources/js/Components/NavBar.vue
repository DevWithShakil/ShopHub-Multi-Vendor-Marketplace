<script setup>
import { ref, computed, watch } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
    MagnifyingGlassIcon,
    ShoppingBagIcon,
    Bars3Icon,
    XMarkIcon,
    HeartIcon,
    TruckIcon,
    Squares2X2Icon,
    ChevronDownIcon,
    ArrowRightOnRectangleIcon,
    HomeIcon,
} from "@heroicons/vue/24/outline";
import { useCartStore } from "@/Stores/cartStore";
import { useWishlistStore } from "@/Stores/wishlistStore";
import axios from "axios";
import debounce from "lodash/debounce";

const cartStore = useCartStore();
const wishlistStore = useWishlistStore();
const page = usePage();
const user = computed(() => page.props.auth.user);

const isMobileMenuOpen = ref(false);
const isUserDropdownOpen = ref(false);
const searchQuery = ref("");
const searchResults = ref([]);
const isSearching = ref(false);
const showDropdown = ref(false);

const getLocalizedName = (nameField) => {
    try {
        const locale = page.props.locale || "en";
        if (typeof nameField === "object" && nameField !== null) {
            return nameField[locale] || nameField["en"] || "Unknown";
        }
        if (typeof nameField === "string" && nameField.startsWith("{")) {
            const parsed = JSON.parse(nameField);
            return parsed[locale] || parsed["en"];
        }
        return nameField;
    } catch (e) {
        return nameField;
    }
};

const performSearch = debounce(async (query) => {
    if (query.length < 2) {
        searchResults.value = [];
        return;
    }
    isSearching.value = true;
    try {
        const response = await axios.get(route("api.search"), {
            params: { query: query },
        });
        searchResults.value = response.data;
        showDropdown.value = true;
    } catch (error) {
        console.error(error);
    } finally {
        isSearching.value = false;
    }
}, 300);

watch(searchQuery, (newVal) => {
    if (!newVal) {
        searchResults.value = [];
        showDropdown.value = false;
    } else {
        performSearch(newVal);
    }
});

const closeSearch = () => {
    setTimeout(() => {
        showDropdown.value = false;
    }, 200);
};
</script>

<template>
    <nav
        class="sticky top-0 z-50 bg-[#0B0F19]/80 backdrop-blur-xl border-b border-white/10 shadow-lg shadow-black/20 transition-all duration-300"
    >
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4 md:gap-8">
                <Link
                    href="/"
                    class="flex-shrink-0 flex items-center gap-3 group"
                >
                    <div
                        class="bg-gradient-to-br from-indigo-600 to-purple-600 text-white p-2.5 rounded-xl shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300"
                    >
                        <ShoppingBagIcon class="w-6 h-6" />
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="text-2xl font-black text-white tracking-tight leading-none group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-indigo-400 group-hover:to-purple-400 transition-all duration-300"
                            >ShopHub</span
                        >
                        <span
                            class="text-[10px] font-bold text-indigo-500 tracking-[0.2em] uppercase"
                            >{{ __("Premium Store") }}</span
                        >
                    </div>
                </Link>

                <div class="hidden md:flex flex-1 max-w-2xl relative group">
                    <div class="relative w-full">
                        <input
                            type="text"
                            v-model="searchQuery"
                            @focus="showDropdown = true"
                            @blur="closeSearch"
                            :placeholder="__('Search for products...')"
                            class="w-full bg-black/20 border border-white/10 rounded-full py-3 pl-5 pr-14 text-sm text-white placeholder-gray-500 focus:bg-black/40 focus:ring-1 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all shadow-inner"
                        />
                        <div class="absolute right-1.5 top-1.5">
                            <div v-if="isSearching" class="p-2">
                                <svg
                                    class="animate-spin h-5 w-5 text-indigo-500"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                            </div>
                            <button
                                v-else
                                class="bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-900/50"
                            >
                                <MagnifyingGlassIcon class="w-5 h-5" />
                            </button>
                        </div>
                    </div>

                    <div
                        v-if="
                            showDropdown &&
                            (searchResults.length > 0 ||
                                (searchQuery.length > 1 && !isSearching))
                        "
                        class="absolute top-full left-0 w-full mt-2 bg-[#151925] border border-white/10 rounded-2xl shadow-2xl overflow-hidden z-50 animate-fade-in-up backdrop-blur-xl"
                    >
                        <div v-if="searchResults.length > 0">
                            <div
                                class="px-4 py-2 bg-white/5 border-b border-white/5 text-[10px] font-bold text-gray-500 uppercase tracking-wider"
                            >
                                Products
                            </div>
                            <ul>
                                <li
                                    v-for="result in searchResults"
                                    :key="result.slug"
                                >
                                    <Link
                                        :href="
                                            route(
                                                'product.details',
                                                result.slug
                                            )
                                        "
                                        class="flex items-center gap-4 px-4 py-3 hover:bg-white/5 transition-colors group border-b border-white/5 last:border-0"
                                    >
                                        <div
                                            class="w-12 h-12 bg-white rounded-lg flex items-center justify-center p-1 flex-shrink-0 overflow-hidden"
                                        >
                                            <img
                                                v-if="result.image"
                                                :src="
                                                    '/storage/' + result.image
                                                "
                                                class="w-full h-full object-contain"
                                                alt="Product"
                                            />
                                            <ShoppingBagIcon
                                                v-else
                                                class="w-6 h-6 text-gray-400"
                                            />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4
                                                class="text-sm font-bold text-gray-200 group-hover:text-indigo-400 truncate transition-colors"
                                            >
                                                {{
                                                    getLocalizedName(
                                                        result.name
                                                    )
                                                }}
                                            </h4>
                                            <p
                                                class="text-xs text-gray-500 truncate"
                                            >
                                                {{
                                                    getLocalizedName(
                                                        result.category
                                                    )
                                                }}
                                            </p>
                                        </div>
                                        <div
                                            class="text-sm font-bold text-white"
                                        >
                                            ৳{{ result.price }}
                                        </div>
                                    </Link>
                                </li>
                            </ul>
                            <Link
                                :href="
                                    route('products.index', {
                                        search: searchQuery,
                                    })
                                "
                                class="block text-center py-3 bg-indigo-600/10 text-indigo-400 text-xs font-bold hover:bg-indigo-600 hover:text-white transition-colors uppercase tracking-wide"
                                >View all results for "{{ searchQuery }}"</Link
                            >
                        </div>
                        <div
                            v-else-if="!isSearching && searchQuery.length > 1"
                            class="p-8 text-center text-gray-400"
                        >
                            <MagnifyingGlassIcon
                                class="w-12 h-12 mx-auto mb-3 opacity-20"
                            />
                            <p class="text-sm">
                                No products found for "<span
                                    class="text-white font-bold"
                                    >{{ searchQuery }}</span
                                >"
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 md:gap-6">
                    <div
                        class="hidden md:flex items-center bg-white/5 rounded-full p-1 border border-white/10"
                    >
                        <a
                            href="/language/bn"
                            class="px-3 py-1 text-xs font-bold rounded-full transition-all"
                            :class="
                                $page.props.locale === 'bn'
                                    ? 'bg-indigo-600 text-white shadow-sm'
                                    : 'text-gray-400 hover:text-white'
                            "
                            >BN</a
                        >
                        <a
                            href="/language/en"
                            class="px-3 py-1 text-xs font-bold rounded-full transition-all"
                            :class="
                                $page.props.locale === 'en'
                                    ? 'bg-indigo-600 text-white shadow-sm'
                                    : 'text-gray-400 hover:text-white'
                            "
                            >EN</a
                        >
                    </div>

                    <button
                        @click="isMobileMenuOpen = true"
                        class="md:hidden text-gray-300 hover:text-white p-2 rounded-lg transition hover:bg-white/10"
                    >
                        <Bars3Icon class="w-7 h-7" />
                    </button>

                    <Link
                        :href="route('track.order')"
                        class="hidden md:flex flex-col items-center text-gray-400 hover:text-indigo-400 transition-colors duration-300 group relative mr-2"
                        title="Track Order"
                    >
                        <div class="relative p-1">
                            <TruckIcon
                                class="w-7 h-7 group-hover:scale-110 transition-transform duration-300"
                            />
                            <span
                                class="absolute top-0 right-0 w-2 h-2 bg-indigo-500 rounded-full animate-pulse border border-[#0B0F19]"
                            ></span>
                        </div>
                    </Link>

                    <Link
                        :href="route('wishlist.index')"
                        class="hidden md:flex flex-col items-center text-gray-400 hover:text-rose-500 transition-colors duration-300 group relative"
                    >
                        <div class="relative p-1">
                            <HeartIcon
                                class="w-7 h-7 group-hover:scale-110 transition-transform duration-300"
                            />
                            <span
                                v-if="wishlistStore.count > 0"
                                class="absolute -top-1 -right-1 bg-rose-500 text-white text-[10px] h-4 w-4 rounded-full flex items-center justify-center font-bold shadow-sm animate-pulse border border-[#0B0F19]"
                                >{{ wishlistStore.count }}</span
                            >
                        </div>
                    </Link>

                    <Link
                        :href="route('cart.index')"
                        class="flex flex-col items-center text-gray-400 hover:text-indigo-400 transition-colors duration-300 group relative"
                    >
                        <div class="relative p-1">
                            <ShoppingBagIcon
                                class="w-7 h-7 group-hover:scale-110 transition-transform duration-300"
                            />
                            <span
                                v-if="cartStore.itemCount > 0"
                                class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[10px] h-4 w-4 rounded-full flex items-center justify-center font-bold shadow-sm animate-bounce border border-[#0B0F19]"
                                >{{ cartStore.itemCount }}</span
                            >
                        </div>
                    </Link>

                    <div class="hidden md:block relative">
                        <div v-if="user">
                            <button
                                @click="
                                    isUserDropdownOpen = !isUserDropdownOpen
                                "
                                class="flex items-center gap-3 hover:bg-white/5 pl-1 pr-3 py-1 rounded-full transition border border-transparent hover:border-white/10 group"
                            >
                                <img
                                    :src="
                                        user.profile_photo_url ||
                                        `https://ui-avatars.com/api/?name=${user.name}&background=4f46e5&color=fff`
                                    "
                                    class="w-9 h-9 rounded-full border-2 border-white/10 shadow-sm object-cover"
                                    alt="Avatar"
                                />
                                <div class="text-left hidden lg:block">
                                    <p
                                        class="text-[10px] font-bold text-gray-500 uppercase tracking-wider"
                                    >
                                        {{ __("Hello") }}
                                    </p>
                                    <p
                                        class="text-sm font-bold text-gray-200 leading-none group-hover:text-indigo-400 transition"
                                    >
                                        {{ user.name.split(" ")[0] }}
                                    </p>
                                </div>
                                <ChevronDownIcon
                                    class="w-4 h-4 text-gray-500 group-hover:text-white group-hover:rotate-180 transition-transform duration-300"
                                />
                            </button>

                            <div
                                v-if="isUserDropdownOpen"
                                @mouseleave="isUserDropdownOpen = false"
                                class="absolute right-0 mt-4 w-64 bg-[#151925] rounded-2xl shadow-2xl border border-white/10 py-2 z-50 animate-fade-in-up origin-top-right backdrop-blur-xl"
                            >
                                <div
                                    class="px-5 py-4 border-b border-white/5 bg-white/5"
                                >
                                    <p
                                        class="text-sm font-bold text-white truncate"
                                    >
                                        {{ user.name }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ user.email }}
                                    </p>
                                </div>
                                <Link
                                    href="/dashboard"
                                    class="flex items-center gap-3 px-5 py-3 text-sm text-gray-400 hover:bg-white/5 hover:text-white transition-colors"
                                >
                                    <Squares2X2Icon class="w-4 h-4" />
                                    {{ __("Dashboard") }}
                                </Link>
                                <Link
                                    href="/dashboard/orders"
                                    class="flex items-center gap-3 px-5 py-3 text-sm text-gray-400 hover:bg-white/5 hover:text-white transition-colors"
                                >
                                    <ShoppingBagIcon class="w-4 h-4" />
                                    {{ __("My Orders") }}
                                </Link>
                                <div class="border-t border-white/5 my-1"></div>
                                <Link
                                    href="/logout"
                                    method="post"
                                    as="button"
                                    class="w-full text-left flex items-center gap-3 px-5 py-3 text-sm text-rose-500 hover:bg-rose-500/10 transition-colors"
                                >
                                    <ArrowRightOnRectangleIcon
                                        class="w-4 h-4"
                                    />
                                    {{ __("Logout") }}
                                </Link>
                            </div>
                        </div>
                        <div v-else class="flex items-center gap-3">
                            <Link
                                href="/login"
                                class="text-gray-400 font-bold hover:text-white text-sm px-4 py-2 hover:bg-white/5 rounded-lg transition-colors"
                                >{{ __("Login") }}</Link
                            >
                            <Link
                                href="/register"
                                class="bg-white text-gray-900 px-5 py-2.5 rounded-full font-bold text-sm hover:bg-indigo-500 hover:text-white transition-all shadow-lg shadow-white/10"
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
                        v-model="searchQuery"
                        @focus="showDropdown = true"
                        :placeholder="__('Search for products...')"
                        class="w-full bg-black/20 border border-white/10 rounded-xl py-3 pl-11 pr-4 text-sm text-white focus:ring-1 focus:ring-indigo-500/50 placeholder-gray-500"
                    />
                    <MagnifyingGlassIcon
                        class="w-5 h-5 text-gray-500 absolute left-4 top-3"
                    />
                </div>
            </div>
        </div>
    </nav>

    <div v-if="isMobileMenuOpen" class="fixed inset-0 z-50 flex">
        <div
            class="fixed inset-0 bg-black/80 backdrop-blur-sm"
            @click="isMobileMenuOpen = false"
        ></div>
        <div
            class="relative w-64 bg-[#151925] h-full shadow-2xl p-6 flex flex-col gap-6 animate-fade-in-right border-r border-white/10"
        >
            <button
                @click="isMobileMenuOpen = false"
                class="absolute top-4 right-4 p-2 text-gray-400 hover:text-white"
            >
                <XMarkIcon class="w-6 h-6" />
            </button>

            <div class="mt-8 space-y-4">
                <Link
                    href="/"
                    class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-white rounded-xl transition-colors"
                >
                    <HomeIcon class="w-6 h-6 text-indigo-400" />
                    <span class="font-bold">Home</span>
                </Link>
                <Link
                    :href="route('wishlist.index')"
                    class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-white rounded-xl transition-colors"
                >
                    <HeartIcon class="w-6 h-6 text-rose-500" />
                    <span class="font-bold">Wishlist</span>
                    <span
                        v-if="wishlistStore.count > 0"
                        class="ml-auto bg-rose-500 text-white text-[10px] px-2 py-0.5 rounded-full"
                        >{{ wishlistStore.count }}</span
                    >
                </Link>
                <Link
                    :href="route('track.order')"
                    class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-white rounded-xl transition-colors"
                >
                    <TruckIcon class="w-6 h-6 text-indigo-400" />
                    <span class="font-bold">Track Order</span>
                </Link>

                <div class="border-t border-white/10 my-2"></div>

                <div v-if="user" class="space-y-4">
                    <Link
                        href="/dashboard"
                        class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-white rounded-xl transition-colors"
                    >
                        <Squares2X2Icon class="w-6 h-6 text-gray-400" />
                        <span class="font-bold">Dashboard</span>
                    </Link>
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="flex w-full items-center gap-3 px-4 py-3 text-rose-500 hover:bg-rose-500/10 rounded-xl transition-colors"
                    >
                        <ArrowRightOnRectangleIcon class="w-6 h-6" />
                        <span class="font-bold">Logout</span>
                    </Link>
                </div>
                <div v-else class="space-y-3 px-4">
                    <Link
                        href="/login"
                        class="block w-full text-center bg-white/5 border border-white/10 text-white py-3 rounded-xl font-bold"
                        >Login</Link
                    >
                    <Link
                        href="/register"
                        class="block w-full text-center bg-indigo-600 text-white py-3 rounded-xl font-bold shadow-lg shadow-indigo-900/50"
                        >Register</Link
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 0.2s ease-out forwards;
}
.animate-fade-in-right {
    animation: fadeInRight 0.3s ease-out forwards;
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
@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(-100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>
