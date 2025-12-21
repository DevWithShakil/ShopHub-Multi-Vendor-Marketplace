<script setup>
import { Head, Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { useCartStore } from "@/Stores/cartStore";
import {
    TrashIcon,
    ArrowLeftIcon,
    ShoppingBagIcon,
} from "@heroicons/vue/24/outline";

const cartStore = useCartStore();
</script>

<template>
    <Head title="My Cart" />

    <MainLayout>
        <div class="container mx-auto px-4 py-12">
            <h1
                class="text-3xl font-bold mb-8 text-gray-800 flex items-center gap-3"
            >
                <ShoppingBagIcon class="w-8 h-8 text-indigo-600" />
                Shopping Cart
            </h1>

            <div
                v-if="cartStore.items.length === 0"
                class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100"
            >
                <div
                    class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6"
                >
                    <ShoppingBagIcon class="w-10 h-10 text-indigo-400" />
                </div>
                <h2 class="text-2xl font-bold text-gray-700 mb-2">
                    Your cart is empty
                </h2>
                <p class="text-gray-500 mb-8">
                    Looks like you haven't added anything to your cart yet.
                </p>
                <Link
                    href="/"
                    class="bg-indigo-600 text-white px-8 py-3 rounded-full font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200"
                >
                    Start Shopping
                </Link>
            </div>

            <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-4">
                    <div
                        v-for="item in cartStore.items"
                        :key="item.cartId"
                        class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row items-center gap-6 transition hover:shadow-md"
                    >
                        <div
                            class="w-24 h-24 bg-gray-50 rounded-lg p-2 flex-shrink-0"
                        >
                            <img
                                :src="'/storage/' + item.image"
                                class="w-full h-full object-contain"
                                :alt="item.name"
                            />
                        </div>

                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="font-bold text-lg text-gray-800">
                                <Link
                                    :href="
                                        route(
                                            'product.details',
                                            item.slug || '#'
                                        )
                                    "
                                    class="hover:text-indigo-600"
                                >
                                    {{ item.name }}
                                </Link>
                            </h3>

                            <div
                                v-if="item.variant"
                                class="text-sm text-gray-500 mt-1 flex gap-2 justify-center sm:justify-start"
                            >
                                <span
                                    v-for="(val, key) in item.variant
                                        .attributes"
                                    :key="key"
                                    class="bg-gray-100 px-2 py-0.5 rounded text-xs uppercase font-semibold"
                                >
                                    {{ key }}: {{ val }}
                                </span>
                            </div>

                            <p class="text-indigo-600 font-bold mt-2">
                                ৳{{ item.price }}
                            </p>
                        </div>

                        <div class="flex flex-col items-center gap-3">
                            <div
                                class="flex items-center border border-gray-300 rounded-lg"
                            >
                                <button
                                    @click="
                                        item.quantity > 1
                                            ? item.quantity--
                                            : cartStore.removeFromCart(
                                                  item.cartId
                                              )
                                    "
                                    class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 text-gray-600"
                                >
                                    -
                                </button>
                                <span
                                    class="w-10 text-center font-medium text-sm"
                                    >{{ item.quantity }}</span
                                >
                                <button
                                    @click="item.quantity++"
                                    class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 text-gray-600"
                                >
                                    +
                                </button>
                            </div>

                            <button
                                @click="cartStore.removeFromCart(item.cartId)"
                                class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1 hover:underline"
                            >
                                <TrashIcon class="w-3 h-3" /> Remove
                            </button>
                        </div>
                    </div>

                    <Link
                        href="/"
                        class="inline-flex items-center gap-2 text-indigo-600 font-semibold hover:underline mt-4"
                    >
                        <ArrowLeftIcon class="w-4 h-4" /> Continue Shopping
                    </Link>
                </div>

                <div class="lg:col-span-1">
                    <div
                        class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24"
                    >
                        <h3 class="text-xl font-bold text-gray-800 mb-6">
                            Order Summary
                        </h3>

                        <div
                            class="space-y-3 text-sm text-gray-600 mb-6 border-b border-dashed border-gray-200 pb-6"
                        >
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="font-medium"
                                    >৳{{ cartStore.totalPrice }}</span
                                >
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping Estimate</span>
                                <span class="font-medium">৳120</span>
                            </div>
                            <div class="flex justify-between text-green-600">
                                <span>Discount</span>
                                <span>-৳0</span>
                            </div>
                        </div>

                        <div
                            class="flex justify-between text-lg font-bold text-gray-900 mb-6"
                        >
                            <span>Total</span>
                            <span>৳{{ cartStore.totalPrice + 120 }}</span>
                        </div>

                        <Link
                            :href="route('checkout.index')"
                            class="block text-center w-full bg-indigo-600 text-white py-3.5 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition transform active:scale-95"
                        >
                            Proceed to Checkout
                        </Link>

                        <div
                            class="mt-4 flex items-center justify-center gap-2 text-xs text-gray-400"
                        >
                            <span
                                class="w-2 h-2 bg-green-500 rounded-full"
                            ></span>
                            Secure SSL Encryption
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
