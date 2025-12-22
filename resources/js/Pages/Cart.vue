<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { useCartStore } from "@/Stores/cartStore";
import {
    TrashIcon,
    ArrowLeftIcon,
    ShoppingBagIcon,
    MinusIcon,
    PlusIcon,
    ShieldCheckIcon,
    LockClosedIcon,
    TicketIcon,
    HomeIcon,
} from "@heroicons/vue/24/outline";

const cartStore = useCartStore();
const page = usePage();

// ✅ JSON Name Helper
const getLocalizedName = (name) => {
    try {
        const parsed =
            typeof name === "string" && name.startsWith("{")
                ? JSON.parse(name)
                : name;
        if (typeof parsed === "object" && parsed !== null) {
            const locale = page.props.locale || "en";
            return parsed[locale] || parsed["en"] || Object.values(parsed)[0];
        }
        return name;
    } catch (e) {
        return name;
    }
};
</script>

<template>
    <Head title="My Cart" />

    <MainLayout>
        <div
            class="min-h-screen bg-[#0B0F19] py-12 relative overflow-hidden font-sans text-gray-200"
        >
            <div
                class="absolute top-0 left-0 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[150px] pointer-events-none"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[150px] pointer-events-none"
            ></div>

            <div class="container mx-auto px-4 lg:px-8 max-w-7xl relative z-10">
                <div
                    class="flex flex-col md:flex-row items-start md:items-center justify-between mb-10 gap-4"
                >
                    <div>
                        <div
                            class="flex items-center gap-2 text-sm text-gray-400 mb-2"
                        >
                            <Link href="/" class="hover:text-white transition"
                                ><HomeIcon class="w-4 h-4"
                            /></Link>
                            <span>/</span>
                            <span class="text-white font-bold">{{
                                __("Shopping Cart")
                            }}</span>
                        </div>
                        <h1
                            class="text-3xl font-black text-white tracking-tight flex items-center gap-3"
                        >
                            {{ __("Your Cart") }}
                            <span
                                class="text-sm font-bold text-indigo-400 bg-indigo-500/10 px-3 py-1 rounded-full border border-indigo-500/20"
                            >
                                {{ cartStore.itemCount }} {{ __("Items") }}
                            </span>
                        </h1>
                    </div>
                    <Link
                        href="/"
                        class="hidden md:flex items-center gap-2 text-sm font-bold text-indigo-400 hover:text-white transition bg-white/5 px-4 py-2.5 rounded-xl border border-white/10 hover:bg-white/10"
                    >
                        <ArrowLeftIcon class="w-4 h-4" />
                        {{ __("Continue Shopping") }}
                    </Link>
                </div>

                <div
                    v-if="cartStore.items.length === 0"
                    class="flex flex-col items-center justify-center py-24 bg-white/5 backdrop-blur-md rounded-3xl border border-white/10 text-center"
                >
                    <div
                        class="w-24 h-24 bg-indigo-500/10 rounded-full flex items-center justify-center mb-6 animate-pulse border border-indigo-500/20"
                    >
                        <ShoppingBagIcon class="w-10 h-10 text-indigo-400" />
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">
                        {{ __("Your cart is empty") }}
                    </h2>
                    <p class="text-gray-400 mb-8 max-w-md">
                        {{
                            __(
                                "Looks like you haven't added anything to your cart yet. Explore our products and find something you love."
                            )
                        }}
                    </p>
                    <Link
                        href="/"
                        class="bg-white text-gray-900 px-8 py-3.5 rounded-xl font-bold hover:bg-indigo-500 hover:text-white transition shadow-lg flex items-center gap-2"
                    >
                        <ArrowLeftIcon class="w-5 h-5" />
                        {{ __("Start Shopping") }}
                    </Link>
                </div>

                <div v-else class="flex flex-col lg:flex-row gap-8 items-start">
                    <div class="flex-1 w-full space-y-6">
                        <div
                            v-for="item in cartStore.items"
                            :key="item.cartId"
                            class="bg-white/5 backdrop-blur-sm p-5 rounded-3xl border border-white/10 flex flex-col sm:flex-row items-center gap-6 group hover:border-indigo-500/30 transition-all duration-300"
                        >
                            <div
                                class="w-28 h-28 bg-white rounded-2xl p-3 flex-shrink-0 border border-white/10 relative overflow-hidden"
                            >
                                <img
                                    :src="
                                        item.image
                                            ? `/storage/${item.image}`
                                            : 'https://placehold.co/400x400'
                                    "
                                    class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-500"
                                    :alt="getLocalizedName(item.name)"
                                />
                            </div>

                            <div class="flex-1 w-full text-center sm:text-left">
                                <div
                                    class="flex flex-col sm:flex-row justify-between items-start gap-4"
                                >
                                    <div>
                                        <p
                                            class="text-xs text-indigo-400 font-bold uppercase tracking-wider mb-1"
                                        >
                                            {{ item.category || "Product" }}
                                        </p>
                                        <Link
                                            :href="
                                                route(
                                                    'product.details',
                                                    item.slug || '#'
                                                )
                                            "
                                        >
                                            <h3
                                                class="font-bold text-lg text-white hover:text-indigo-400 transition leading-tight mb-2"
                                            >
                                                {{
                                                    getLocalizedName(item.name)
                                                }}
                                            </h3>
                                        </Link>

                                        <div
                                            v-if="item.variant"
                                            class="flex flex-wrap justify-center sm:justify-start gap-2"
                                        >
                                            <span
                                                v-for="(val, key) in item
                                                    .variant.attributes"
                                                :key="key"
                                                class="bg-white/5 text-gray-400 text-xs px-2 py-1 rounded border border-white/10"
                                            >
                                                {{ key }}:
                                                <span
                                                    class="text-white font-bold"
                                                    >{{ val }}</span
                                                >
                                            </span>
                                        </div>
                                    </div>

                                    <p class="text-2xl font-black text-white">
                                        ৳{{ item.price * item.quantity }}
                                    </p>
                                </div>

                                <div
                                    class="flex flex-col sm:flex-row items-center justify-between mt-6 pt-4 border-t border-white/10 gap-4"
                                >
                                    <div
                                        class="flex items-center bg-white/5 rounded-xl border border-white/10 p-1"
                                    >
                                        <button
                                            @click="
                                                item.quantity > 1
                                                    ? item.quantity--
                                                    : cartStore.removeFromCart(
                                                          item.cartId
                                                      )
                                            "
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-white/10 hover:text-rose-500 transition"
                                        >
                                            <MinusIcon class="w-4 h-4" />
                                        </button>
                                        <span
                                            class="w-10 text-center font-bold text-white"
                                            >{{ item.quantity }}</span
                                        >
                                        <button
                                            @click="item.quantity++"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-white/10 hover:text-green-500 transition"
                                        >
                                            <PlusIcon class="w-4 h-4" />
                                        </button>
                                    </div>

                                    <button
                                        @click="
                                            cartStore.removeFromCart(
                                                item.cartId
                                            )
                                        "
                                        class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-rose-500 transition-colors"
                                    >
                                        <TrashIcon class="w-4 h-4" />
                                        {{ __("Remove") }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <Link
                            href="/"
                            class="md:hidden flex justify-center items-center gap-2 text-sm font-bold text-indigo-400 hover:text-white py-4"
                        >
                            <ArrowLeftIcon class="w-4 h-4" />
                            {{ __("Continue Shopping") }}
                        </Link>
                    </div>

                    <div class="lg:w-96 xl:w-[420px] flex-shrink-0">
                        <div
                            class="bg-white/5 backdrop-blur-md rounded-3xl border border-white/10 p-6 sticky top-24 shadow-xl shadow-black/20"
                        >
                            <h3
                                class="text-xl font-bold text-white mb-6 flex items-center gap-2"
                            >
                                <TicketIcon class="w-5 h-5 text-indigo-400" />
                                {{ __("Order Summary") }}
                            </h3>

                            <div class="flex gap-2 mb-6">
                                <div class="relative flex-1">
                                    <input
                                        type="text"
                                        :placeholder="__('Promo Code')"
                                        class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none placeholder-gray-600"
                                    />
                                </div>
                                <button
                                    class="bg-white text-gray-900 px-5 rounded-xl text-sm font-bold hover:bg-indigo-500 hover:text-white transition"
                                >
                                    {{ __("Apply") }}
                                </button>
                            </div>

                            <div
                                class="space-y-4 text-sm text-gray-400 pb-6 border-b border-white/10"
                            >
                                <div class="flex justify-between">
                                    <span>{{ __("Subtotal") }}</span>
                                    <span class="font-bold text-white"
                                        >৳{{ cartStore.totalPrice }}</span
                                    >
                                </div>
                                <div class="flex justify-between">
                                    <span>{{ __("Shipping Estimate") }}</span>
                                    <span class="font-bold text-white"
                                        >৳120</span
                                    >
                                </div>
                                <div
                                    class="flex justify-between text-green-400"
                                >
                                    <span>{{ __("Discount") }}</span>
                                    <span class="font-bold">-৳0</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-end py-6">
                                <span class="text-gray-400 font-medium">{{
                                    __("Total Amount")
                                }}</span>
                                <span class="text-3xl font-black text-white"
                                    >৳{{ cartStore.totalPrice + 120 }}</span
                                >
                            </div>

                            <Link
                                :href="route('checkout.index')"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center font-bold py-4 rounded-xl shadow-lg shadow-indigo-600/20 transform transition-all active:scale-95 flex justify-center items-center gap-2 group"
                            >
                                <LockClosedIcon
                                    class="w-5 h-5 group-hover:scale-110 transition-transform"
                                />
                                {{ __("Proceed to Checkout") }}
                            </Link>

                            <div class="mt-6 flex flex-col items-center gap-4">
                                <div
                                    class="flex items-center gap-2 text-xs text-green-400 bg-green-500/10 px-3 py-1.5 rounded-lg border border-green-500/20"
                                >
                                    <ShieldCheckIcon class="w-4 h-4" />
                                    100% {{ __("Secure Payment") }}
                                </div>
                                <div
                                    class="flex gap-3 opacity-60 grayscale hover:grayscale-0 transition-all duration-300"
                                >
                                    <img
                                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/2560px-Visa_Inc._logo.svg.png"
                                        class="h-5 object-contain"
                                        alt="Visa"
                                    />
                                    <img
                                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png"
                                        class="h-7 object-contain"
                                        alt="Mastercard"
                                    />
                                    <img
                                        src="https://freepnglogo.com/images/all_img/1701670291bKash-App-Logo-PNG.png"
                                        class="h-7 object-contain"
                                        alt="Bkash"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
