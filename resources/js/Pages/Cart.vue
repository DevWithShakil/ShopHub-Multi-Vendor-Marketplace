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
} from "@heroicons/vue/24/outline";

const cartStore = useCartStore();
const page = usePage();

// ✅ নামের JSON ফরম্যাট ঠিক করার ফাংশন
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
        <div class="min-h-screen bg-[#FAFAFA] py-12 font-sans text-gray-800">
            <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        {{ __("Shopping Cart") }}
                        <span class="text-base font-normal text-gray-500 ml-2"
                            >({{ cartStore.itemCount }} {{ __("items") }})</span
                        >
                    </h1>
                    <Link
                        href="/"
                        class="hidden md:flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition"
                    >
                        <ArrowLeftIcon class="w-4 h-4" />
                        {{ __("Continue Shopping") }}
                    </Link>
                </div>

                <div
                    v-if="cartStore.items.length === 0"
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center"
                >
                    <div
                        class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6"
                    >
                        <ShoppingBagIcon class="w-10 h-10 text-gray-400" />
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">
                        {{ __("Your cart is empty") }}
                    </h2>
                    <p class="text-gray-500 mb-8">
                        {{ __("Looks like you haven't added anything yet.") }}
                    </p>
                    <Link
                        href="/"
                        class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition"
                    >
                        {{ __("Start Shopping") }}
                    </Link>
                </div>

                <div v-else class="flex flex-col lg:flex-row gap-8 items-start">
                    <div
                        class="flex-1 w-full bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
                    >
                        <div
                            class="hidden md:grid grid-cols-12 gap-4 border-b border-gray-100 bg-gray-50/50 px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider"
                        >
                            <div class="col-span-6">{{ __("Product") }}</div>
                            <div class="col-span-3 text-center">
                                {{ __("Quantity") }}
                            </div>
                            <div class="col-span-3 text-right">
                                {{ __("Total") }}
                            </div>
                        </div>

                        <div class="divide-y divide-gray-100">
                            <div
                                v-for="item in cartStore.items"
                                :key="item.cartId"
                                class="p-6 md:grid md:grid-cols-12 md:gap-4 md:items-center flex flex-col gap-4"
                            >
                                <div class="col-span-6 flex gap-4">
                                    <Link
                                        :href="
                                            route(
                                                'product.details',
                                                item.slug || '#'
                                            )
                                        "
                                        class="block w-20 h-20 md:w-24 md:h-24 bg-gray-50 rounded-lg border border-gray-100 flex-shrink-0 p-2"
                                    >
                                        <img
                                            :src="
                                                item.image
                                                    ? `/storage/${item.image}`
                                                    : 'https://placehold.co/400x400'
                                            "
                                            class="w-full h-full object-contain mix-blend-multiply"
                                            :alt="getLocalizedName(item.name)"
                                        />
                                    </Link>

                                    <div class="flex flex-col justify-center">
                                        <p
                                            class="text-xs text-gray-500 mb-1 uppercase"
                                        >
                                            {{ item.category || "Item" }}
                                        </p>
                                        <Link
                                            :href="
                                                route(
                                                    'product.details',
                                                    item.slug || '#'
                                                )
                                            "
                                            class="font-bold text-gray-900 text-base hover:text-indigo-600 transition line-clamp-2"
                                        >
                                            {{ getLocalizedName(item.name) }}
                                        </Link>

                                        <div
                                            v-if="item.variant"
                                            class="flex flex-wrap gap-2 mt-1"
                                        >
                                            <span
                                                v-for="(val, key) in item
                                                    .variant.attributes"
                                                :key="key"
                                                class="text-xs text-gray-500 bg-gray-50 px-2 py-0.5 rounded border border-gray-200"
                                            >
                                                {{ key }}: {{ val }}
                                            </span>
                                        </div>
                                        <p
                                            class="md:hidden text-indigo-600 font-bold mt-2"
                                        >
                                            ৳{{ item.price }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="col-span-3 flex justify-between md:justify-center items-center"
                                >
                                    <div
                                        class="flex items-center border border-gray-200 rounded-lg"
                                    >
                                        <button
                                            @click="
                                                item.quantity > 1
                                                    ? item.quantity--
                                                    : cartStore.removeFromCart(
                                                          item.cartId
                                                      )
                                            "
                                            class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition"
                                        >
                                            <MinusIcon class="w-3 h-3" />
                                        </button>
                                        <input
                                            type="text"
                                            readonly
                                            :value="item.quantity"
                                            class="w-10 text-center text-sm font-semibold border-none p-0 focus:ring-0 text-gray-900"
                                        />
                                        <button
                                            @click="item.quantity++"
                                            class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition"
                                        >
                                            <PlusIcon class="w-3 h-3" />
                                        </button>
                                    </div>

                                    <button
                                        @click="
                                            cartStore.removeFromCart(
                                                item.cartId
                                            )
                                        "
                                        class="md:hidden text-red-500 p-2"
                                    >
                                        <TrashIcon class="w-5 h-5" />
                                    </button>
                                </div>

                                <div
                                    class="col-span-3 text-right hidden md:block"
                                >
                                    <p class="text-lg font-bold text-gray-900">
                                        ৳{{ item.price * item.quantity }}
                                    </p>
                                    <button
                                        @click="
                                            cartStore.removeFromCart(
                                                item.cartId
                                            )
                                        "
                                        class="text-xs text-red-500 hover:text-red-700 hover:underline mt-1 inline-flex items-center gap-1 transition"
                                    >
                                        <TrashIcon class="w-3 h-3" />
                                        {{ __("Remove") }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:w-96 xl:w-[450px] flex-shrink-0">
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24"
                        >
                            <h3 class="text-lg font-bold text-gray-900 mb-4">
                                {{ __("Order Summary") }}
                            </h3>

                            <div class="flex gap-2 mb-6">
                                <input
                                    type="text"
                                    :placeholder="__('Promo Code')"
                                    class="flex-1 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                                <button
                                    class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-black transition"
                                >
                                    {{ __("Apply") }}
                                </button>
                            </div>

                            <div
                                class="space-y-3 text-sm text-gray-600 pb-4 border-b border-gray-100"
                            >
                                <div class="flex justify-between">
                                    <span>{{ __("Subtotal") }}</span>
                                    <span class="font-medium text-gray-900"
                                        >৳{{ cartStore.totalPrice }}</span
                                    >
                                </div>
                                <div class="flex justify-between">
                                    <span>{{ __("Shipping") }}</span>
                                    <span class="font-medium text-gray-900"
                                        >৳120</span
                                    >
                                </div>
                                <div
                                    class="flex justify-between text-green-600"
                                >
                                    <span>{{ __("Discount") }}</span>
                                    <span>-৳0</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center py-4">
                                <span class="font-bold text-gray-900">{{
                                    __("Total")
                                }}</span>
                                <span class="text-2xl font-bold text-indigo-600"
                                    >৳{{ cartStore.totalPrice + 120 }}</span
                                >
                            </div>

                            <Link
                                :href="route('checkout.index')"
                                class="w-full block bg-indigo-600 hover:bg-indigo-700 text-white text-center font-bold py-3.5 rounded-lg shadow-sm transition-all duration-200 flex justify-center items-center gap-2"
                            >
                                <LockClosedIcon class="w-4 h-4" />
                                {{ __("Proceed to Checkout") }}
                            </Link>

                            <div
                                class="mt-4 flex items-center justify-center gap-2 text-xs text-gray-400"
                            >
                                <ShieldCheckIcon
                                    class="w-4 h-4 text-green-500"
                                />
                                <span>{{
                                    __("Guaranteed Safe Checkout")
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
