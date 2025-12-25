<script setup>
import { Head, Link, usePage, useForm } from "@inertiajs/vue3";
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
    XMarkIcon,
    CheckCircleIcon,
    ExclamationCircleIcon,
    InformationCircleIcon,
    XCircleIcon,
} from "@heroicons/vue/24/outline";
import { computed, ref } from "vue";

const cartStore = useCartStore();
const page = usePage();

const couponForm = useForm({
    code: "",
});

const appliedCoupon = computed(() => page.props.coupon || null);

// ✅ Toast Logic
const toastMessage = ref(null);
const toastType = ref("success");

const showToast = (message, type = "success") => {
    toastMessage.value = message;
    toastType.value = type;
    setTimeout(() => {
        toastMessage.value = null;
    }, 4000); // 4 seconds for better readability of long messages
};

// ✅ Handle Coupon Apply (Updated for Flash Sale & Vendor Logic)
const applyCoupon = () => {
    couponForm.clearErrors();

    couponForm
        .transform((data) => ({
            code: data.code,
            // ✅ Backend validation এর জন্য সম্পূর্ণ ডেটা পাঠানো হচ্ছে
            items: cartStore.items.map((item) => ({
                id: item.id,
                price: item.price,
                quantity: item.quantity,
                vendor_id: item.vendor_id, // Controller-এ ভেন্ডর চেক করার জন্য এটি প্রয়োজন
            })),
        }))
        .post(route("coupon.apply"), {
            preserveScroll: true,
            onSuccess: (page) => {
                couponForm.reset();
                // ব্যাকএন্ড থেকে আসা ডাইনামিক মেসেজ টোস্টে দেখান
                const flashMsg = page.props.flash.success;
                showToast(
                    flashMsg || "Coupon applied successfully!",
                    "success"
                );
            },
            onError: (errors) => {
                // ইনপুটের নিচে অটোমেটিক এরর দেখাবে (couponForm.errors.code)
                // যদি কোনো কারণে code ফিল্ডে এরর না আসে, তখন ম্যানুয়ালি সেট করা হবে
                if (!errors.code) {
                    couponForm.setError("code", "Invalid coupon or expired");
                }
            },
        });
};

// ✅ Handle Coupon Remove
const removeCoupon = () => {
    couponForm.delete(route("coupon.remove"), {
        preserveScroll: true,
        onSuccess: () => {
            showToast("Coupon removed.", "info");
        },
    });
};

// ✅ Remove Item Logic
const removeItem = (id) => {
    cartStore.removeFromCart(id);
    showToast("Item removed from cart.", "error");
};

// ✅ Decrease Quantity Logic
const decreaseQuantity = (item) => {
    if (item.quantity > 1) {
        item.quantity--;
    } else {
        cartStore.removeFromCart(item.cartId);
        showToast("Item removed from cart.", "error");
    }
};

const shippingCost = 120;
const grandTotal = computed(() => {
    let total = cartStore.totalPrice + shippingCost;
    if (appliedCoupon.value) {
        total -= parseFloat(appliedCoupon.value.discount);
    }
    return total > 0 ? total : 0;
});

// JSON Name Helper
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

    <transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-10 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div
            v-if="toastMessage"
            class="fixed bottom-6 right-6 z-[100] max-w-md w-full border shadow-[0_10px_40px_-10px_rgba(0,0,0,0.5)] rounded-2xl p-4 flex items-center gap-4 backdrop-blur-xl border-l-4 animate-pulse-slow"
            :class="{
                'bg-[#1A1F2E] border-green-500/20 border-l-green-500':
                    toastType === 'success',
                'bg-[#1A1F2E] border-blue-500/20 border-l-blue-500':
                    toastType === 'info',
                'bg-[#1A1F2E] border-red-500/20 border-l-red-500':
                    toastType === 'error',
            }"
        >
            <div
                class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 shadow-inner ring-1"
                :class="{
                    'bg-green-500/10 ring-green-500/20':
                        toastType === 'success',
                    'bg-blue-500/10 ring-blue-500/20': toastType === 'info',
                    'bg-red-500/10 ring-red-500/20': toastType === 'error',
                }"
            >
                <CheckCircleIcon
                    v-if="toastType === 'success'"
                    class="w-6 h-6 text-green-400"
                />
                <InformationCircleIcon
                    v-else-if="toastType === 'info'"
                    class="w-6 h-6 text-blue-400"
                />
                <XCircleIcon v-else class="w-6 h-6 text-red-400" />
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-sm tracking-wide text-white">
                    {{
                        toastType === "success"
                            ? "Success"
                            : toastType === "info"
                            ? "Updated"
                            : "Removed"
                    }}
                </h4>
                <p
                    class="text-xs mt-0.5 font-medium"
                    :class="{
                        'text-green-200/70': toastType === 'success',
                        'text-blue-200/70': toastType === 'info',
                        'text-red-200/70': toastType === 'error',
                    }"
                >
                    {{ toastMessage }}
                </p>
            </div>
            <button
                @click="toastMessage = null"
                class="p-2 hover:bg-white/5 rounded-full text-gray-400 hover:text-white transition"
            >
                <XMarkIcon class="w-4 h-4" />
            </button>
        </div>
    </transition>

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
                                "Looks like you haven't added anything to your cart yet."
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
                                            {{
                                                getLocalizedName(
                                                    item.category
                                                ) || "Product"
                                            }}
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
                                            @click="decreaseQuantity(item)"
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
                                        @click="removeItem(item.cartId)"
                                        class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-rose-500 transition-colors"
                                    >
                                        <TrashIcon class="w-4 h-4" />
                                        {{ __("Remove") }}
                                    </button>
                                </div>
                            </div>
                        </div>
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

                            <div class="mb-6">
                                <div
                                    v-if="appliedCoupon"
                                    class="bg-green-500/10 border border-green-500/20 rounded-xl p-3 mb-2 animate-fade-in-up"
                                >
                                    <div
                                        class="flex justify-between items-center mb-1"
                                    >
                                        <span
                                            class="text-xs text-green-400 font-bold flex items-center gap-1"
                                        >
                                            <CheckCircleIcon class="w-4 h-4" />
                                            COUPON APPLIED
                                        </span>
                                        <button
                                            @click="removeCoupon"
                                            class="p-1 hover:bg-white/10 rounded-full text-gray-400 hover:text-white transition"
                                            title="Remove"
                                            :disabled="couponForm.processing"
                                        >
                                            <XMarkIcon class="w-4 h-4" />
                                        </button>
                                    </div>
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <div class="flex flex-col">
                                            <span
                                                class="text-white font-mono font-bold tracking-wider"
                                                >{{ appliedCoupon.code }}</span
                                            >
                                            <span
                                                v-if="
                                                    appliedCoupon.type ===
                                                    'percentage'
                                                "
                                                class="text-[10px] text-green-300 font-bold bg-green-500/20 px-1.5 py-0.5 rounded w-fit mt-1"
                                            >
                                                {{
                                                    parseInt(
                                                        appliedCoupon.value
                                                    )
                                                }}% OFF
                                            </span>
                                        </div>
                                        <span
                                            class="text-green-400 font-bold text-lg"
                                            >-৳{{
                                                appliedCoupon.discount
                                            }}</span
                                        >
                                    </div>
                                </div>

                                <div v-else>
                                    <div class="flex gap-2">
                                        <input
                                            v-model="couponForm.code"
                                            type="text"
                                            :placeholder="__('Promo Code')"
                                            class="w-full bg-black/30 border rounded-xl px-4 py-3 text-white text-sm focus:ring-1 focus:ring-indigo-500 transition outline-none placeholder-gray-600 uppercase"
                                            :class="
                                                couponForm.errors.code
                                                    ? 'border-rose-500/50 focus:border-rose-500'
                                                    : 'border-white/10 focus:border-indigo-500'
                                            "
                                            @keydown.enter.prevent="applyCoupon"
                                        />

                                        <button
                                            @click="applyCoupon"
                                            :disabled="
                                                couponForm.processing ||
                                                !couponForm.code
                                            "
                                            class="bg-white text-gray-900 px-5 rounded-xl text-sm font-bold hover:bg-indigo-500 hover:text-white transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[80px]"
                                        >
                                            <span
                                                v-if="couponForm.processing"
                                                class="animate-pulse"
                                                >...</span
                                            >
                                            <span v-else>{{
                                                __("Apply")
                                            }}</span>
                                        </button>
                                    </div>

                                    <p
                                        v-if="couponForm.errors.code"
                                        class="text-rose-400 text-xs mt-2 flex items-center gap-1 animate-pulse"
                                    >
                                        <ExclamationCircleIcon
                                            class="w-3 h-3"
                                        />
                                        {{ couponForm.errors.code }}
                                    </p>
                                </div>
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
                                        >৳{{ shippingCost }}</span
                                    >
                                </div>
                                <div
                                    v-if="appliedCoupon"
                                    class="flex justify-between text-green-400 animate-pulse"
                                >
                                    <span>{{ __("Discount") }}</span>
                                    <span class="font-bold"
                                        >-৳{{ appliedCoupon.discount }}</span
                                    >
                                </div>
                            </div>

                            <div class="flex justify-between items-end py-6">
                                <span class="text-gray-400 font-medium">{{
                                    __("Total Amount")
                                }}</span>
                                <span class="text-3xl font-black text-white"
                                    >৳{{ grandTotal }}</span
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
                                    <ShieldCheckIcon class="w-4 h-4" /> 100%
                                    {{ __("Secure Payment") }}
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
