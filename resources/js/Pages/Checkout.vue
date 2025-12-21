<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { useCartStore } from "@/Stores/cartStore";
import {
    LockClosedIcon,
    MapPinIcon,
    CreditCardIcon,
    BanknotesIcon,
    CheckBadgeIcon,
    ShieldCheckIcon,
    TruckIcon
} from "@heroicons/vue/24/outline";

const cartStore = useCartStore();
const page = usePage();

const form = useForm({
    name: page.props.auth.user?.name || "",
    phone: "",
    address: "",
    city: "Dhaka",
    payment_method: "cod",
    items: cartStore.items,
    total_price: cartStore.totalPrice + 120, // With Shipping
});

const submitOrder = () => {
    form.post(route("checkout.store"), {
        onSuccess: () => {
            cartStore.clearCart();
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
};

// ✅ নামের JSON ফরম্যাট ঠিক করার ফাংশন
const getLocalizedName = (name) => {
    try {
        const parsed = typeof name === 'string' && name.startsWith('{') ? JSON.parse(name) : name;
        if (typeof parsed === 'object' && parsed !== null) {
            const locale = page.props.locale || 'en';
            return parsed[locale] || parsed['en'] || Object.values(parsed)[0];
        }
        return name;
    } catch (e) {
        return name;
    }
};
</script>

<template>
    <Head title="Secure Checkout" />

    <MainLayout>
        <div class="min-h-screen bg-gray-50 py-12 font-sans text-gray-800">
            <div class="container mx-auto px-4 lg:px-8 max-w-7xl">

                <div class="flex items-center justify-center mb-10 gap-2">
                    <ShieldCheckIcon class="w-6 h-6 text-green-600" />
                    <h1 class="text-2xl font-bold text-gray-800">{{ __('Secure Checkout') }}</h1>
                </div>

                <form @submit.prevent="submitOrder" class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                    <div class="lg:col-span-8 space-y-8">

                        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold">1</span>
                                {{ __('Shipping Information') }}
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2 md:col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Full Name') }}</label>
                                    <input v-model="form.name" type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition outline-none" required placeholder="John Doe">
                                    <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                                </div>

                                <div class="col-span-2 md:col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Phone Number') }}</label>
                                    <input v-model="form.phone" type="tel" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition outline-none" required placeholder="017xxxxxxxx">
                                    <p v-if="form.errors.phone" class="text-red-500 text-xs mt-1">{{ form.errors.phone }}</p>
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Full Address') }}</label>
                                    <textarea v-model="form.address" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition outline-none resize-none" required placeholder="House #12, Road #5, Block #A..."></textarea>
                                    <p v-if="form.errors.address" class="text-red-500 text-xs mt-1">{{ form.errors.address }}</p>
                                </div>

                                <div class="col-span-2 md:col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('City') }}</label>
                                    <select v-model="form.city" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition outline-none bg-white">
                                        <option value="Dhaka">Dhaka</option>
                                        <option value="Chittagong">Chittagong</option>
                                        <option value="Sylhet">Sylhet</option>
                                        <option value="Rajshahi">Rajshahi</option>
                                        <option value="Khulna">Khulna</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold">2</span>
                                {{ __('Payment Method') }}
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="cursor-pointer relative">
                                    <input type="radio" v-model="form.payment_method" value="cod" class="peer sr-only">
                                    <div class="p-5 border-2 rounded-xl transition-all duration-200 hover:bg-gray-50 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 flex items-center gap-4">
                                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                            <BanknotesIcon class="w-6 h-6" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800">Cash on Delivery</p>
                                            <p class="text-xs text-gray-500">Pay when you receive</p>
                                        </div>
                                        <CheckBadgeIcon class="w-6 h-6 text-indigo-600 ml-auto opacity-0 peer-checked:opacity-100 transition-opacity" />
                                    </div>
                                </label>

                                <label class="cursor-pointer relative">
                                    <input type="radio" v-model="form.payment_method" value="sslcommerz" class="peer sr-only">
                                    <div class="p-5 border-2 rounded-xl transition-all duration-200 hover:bg-gray-50 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 flex items-center gap-4">
                                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                            <CreditCardIcon class="w-6 h-6" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800">Pay Online</p>
                                            <p class="text-xs text-gray-500">Cards, Mobile Banking</p>
                                        </div>
                                        <CheckBadgeIcon class="w-6 h-6 text-indigo-600 ml-auto opacity-0 peer-checked:opacity-100 transition-opacity" />
                                    </div>
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="lg:col-span-4">
                        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 sticky top-24">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100">
                                {{ __('Order Summary') }}
                            </h3>

                            <div class="space-y-4 max-h-80 overflow-y-auto custom-scrollbar mb-6 pr-2">
                                <div v-for="item in cartStore.items" :key="item.cartId" class="flex gap-4">
                                    <div class="w-16 h-16 bg-gray-50 rounded-lg border border-gray-100 flex-shrink-0 p-1">
                                        <img :src="'/storage/' + item.image" class="w-full h-full object-contain mix-blend-multiply" :alt="getLocalizedName(item.name)">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-800 truncate">{{ getLocalizedName(item.name) }}</p>
                                        <p class="text-xs text-gray-500 mt-1">Qty: {{ item.quantity }}</p>
                                    </div>
                                    <div class="text-sm font-bold text-gray-900">
                                        ৳{{ item.price * item.quantity }}
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3 py-6 border-t border-b border-dashed border-gray-200 text-sm">
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ __('Subtotal') }}</span>
                                    <span class="font-bold text-gray-900">৳{{ cartStore.totalPrice }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span class="flex items-center gap-1"><TruckIcon class="w-4 h-4"/> {{ __('Delivery Charge') }}</span>
                                    <span class="font-bold text-gray-900">৳120</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center py-6">
                                <span class="text-lg font-bold text-gray-900">{{ __('Total to Pay') }}</span>
                                <span class="text-2xl font-black text-indigo-600">৳{{ cartStore.totalPrice + 120 }}</span>
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing || cartStore.items.length === 0"
                                class="w-full bg-gray-900 hover:bg-black text-white py-4 rounded-xl font-bold shadow-lg shadow-gray-200 transform transition-all active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed flex justify-center items-center gap-2"
                            >
                                <LockClosedIcon v-if="!form.processing" class="w-5 h-5" />
                                <span v-if="form.processing">{{ __('Processing...') }}</span>
                                <span v-else>{{ __('Place Order') }}</span>
                            </button>

                            <p class="mt-4 text-center text-xs text-gray-400">
                                {{ __('By placing this order, you agree to our Terms of Service and Privacy Policy.') }}
                            </p>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
/* Custom Scrollbar for Items List */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
</style>
