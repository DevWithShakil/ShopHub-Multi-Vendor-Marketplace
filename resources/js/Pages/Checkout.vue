<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { useCartStore } from "@/Stores/cartStore";
import { LockClosedIcon, MapPinIcon } from "@heroicons/vue/24/outline";

const cartStore = useCartStore();

const form = useForm({
    name: "",
    phone: "",
    address: "",
    city: "",
    payment_method: "cod",
    items: cartStore.items,
    total_price: cartStore.totalPrice,
});

const submitOrder = () => {
    form.post(route("checkout.store"), {
        onSuccess: () => {
            cartStore.clearCart();
        },
    });
};
</script>

<template>
    <Head title="Checkout" />

    <MainLayout>
        <div class="container mx-auto px-4 py-10 bg-gray-50 min-h-screen">
            <h1
                class="text-3xl font-bold text-gray-800 mb-8 flex items-center gap-2"
            >
                <LockClosedIcon class="w-8 h-8 text-green-600" /> Secure
                Checkout
            </h1>

            <form
                @submit.prevent="submitOrder"
                class="grid grid-cols-1 lg:grid-cols-3 gap-8"
            >
                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100"
                    >
                        <h2
                            class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2"
                        >
                            <MapPinIcon class="w-6 h-6 text-indigo-600" />
                            Shipping Information
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Full Name</label
                                >
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                    required
                                />
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Phone Number</label
                                >
                                <input
                                    v-model="form.phone"
                                    type="text"
                                    class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                    required
                                />
                            </div>
                            <div class="col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Full Address</label
                                >
                                <textarea
                                    v-model="form.address"
                                    rows="3"
                                    class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="House no, Road no, Area..."
                                    required
                                ></textarea>
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >City</label
                                >
                                <select
                                    v-model="form.city"
                                    class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                    <option value="Dhaka">Dhaka</option>
                                    <option value="Chittagong">
                                        Chittagong
                                    </option>
                                    <option value="Sylhet">Sylhet</option>
                                    <option value="Rajshahi">Rajshahi</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100"
                    >
                        <h2 class="text-xl font-bold text-gray-800 mb-4">
                            Payment Method
                        </h2>
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:border-indigo-500 transition"
                                :class="{
                                    'border-indigo-600 bg-indigo-50':
                                        form.payment_method === 'cod',
                                }"
                            >
                                <input
                                    type="radio"
                                    v-model="form.payment_method"
                                    value="cod"
                                    class="text-indigo-600 focus:ring-indigo-500"
                                />
                                <span class="font-medium"
                                    >Cash on Delivery (COD)</span
                                >
                            </label>

                            <label
                                class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:border-indigo-500 transition"
                                :class="{
                                    'border-indigo-600 bg-indigo-50':
                                        form.payment_method === 'bkash',
                                }"
                            >
                                <input
                                    type="radio"
                                    v-model="form.payment_method"
                                    value="bkash"
                                    class="text-indigo-600 focus:ring-indigo-500"
                                />
                                <div class="flex items-center gap-2">
                                    <span class="font-medium"
                                        >bKash Payment</span
                                    >
                                    <span
                                        class="text-xs bg-pink-600 text-white px-2 py-0.5 rounded"
                                        >Secure</span
                                    >
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-24"
                    >
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            Order Summary
                        </h3>

                        <div
                            class="space-y-3 max-h-60 overflow-y-auto mb-4 custom-scrollbar"
                        >
                            <div
                                v-for="item in cartStore.items"
                                :key="item.cartId"
                                class="flex gap-3 text-sm"
                            >
                                <img
                                    :src="'/storage/' + item.image"
                                    class="w-12 h-12 rounded object-cover border"
                                />
                                <div class="flex-1">
                                    <p
                                        class="font-medium text-gray-800 truncate"
                                    >
                                        {{ item.name }}
                                    </p>
                                    <p class="text-gray-500 text-xs">
                                        Qty: {{ item.quantity }}
                                    </p>
                                </div>
                                <div class="font-bold text-gray-700">
                                    ৳{{ item.price * item.quantity }}
                                </div>
                            </div>
                        </div>

                        <div
                            class="border-t border-dashed border-gray-200 pt-4 space-y-2 text-sm text-gray-600"
                        >
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span>৳{{ cartStore.totalPrice }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Delivery Charge</span>
                                <span>৳120</span>
                            </div>
                        </div>

                        <div
                            class="border-t border-gray-200 mt-4 pt-4 flex justify-between text-xl font-bold text-gray-900"
                        >
                            <span>Total</span>
                            <span>৳{{ cartStore.totalPrice + 120 }}</span>
                        </div>

                        <button
                            type="submit"
                            :disabled="
                                form.processing || cartStore.items.length === 0
                            "
                            class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold mt-6 hover:bg-indigo-700 transition disabled:opacity-50"
                        >
                            {{
                                form.processing
                                    ? "Processing..."
                                    : "Place Order"
                            }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </MainLayout>
</template>
