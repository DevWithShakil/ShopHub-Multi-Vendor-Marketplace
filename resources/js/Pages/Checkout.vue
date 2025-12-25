<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { useCartStore } from "@/Stores/cartStore";
import {
    LockClosedIcon,
    BanknotesIcon,
    CheckBadgeIcon,
    ShieldCheckIcon,
    TruckIcon,
    HomeIcon,
    ChevronDownIcon,
    CreditCardIcon,
    TicketIcon,
    CheckCircleIcon,
    XCircleIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { watch, ref, computed } from "vue";
import { locations } from "@/Data/bangladesh";
import axios from "axios";

const cartStore = useCartStore();
const page = usePage();

const toastMessage = ref(null);
const toastType = ref("success");
const isSubmitting = ref(false);

const showToast = (message, type = "success") => {
    toastMessage.value = message;
    toastType.value = type;
    setTimeout(() => {
        toastMessage.value = null;
    }, 4000);
};

const appliedCoupon = computed(() => page.props.coupon || null);
const shippingCost = 120;

const discountAmount = computed(() => {
    return appliedCoupon.value ? parseFloat(appliedCoupon.value.discount) : 0;
});

const grandTotal = computed(() => {
    let total = cartStore.totalPrice + shippingCost - discountAmount.value;
    return total > 0 ? total : 0;
});

const form = useForm({
    name: page.props.auth.user?.name || "",
    phone: "",
    address: "",
    city: "",
    thana: "",
    payment_method: "cod",
    items: [],
    total_price: 0,
});

// ✅ Updated Submit Logic
const submitOrder = async () => {
    isSubmitting.value = true;
    form.clearErrors();

    // Prepare payload manually since we use axios
    const payload = {
        ...form.data(),
        items: cartStore.items,
        total_price: grandTotal.value,
    };

    try {
        const response = await axios.post(route("checkout.store"), payload);

        if (form.payment_method === "cod") {
            cartStore.clearCart();
            showToast("Order placed successfully! Redirecting...", "success");

            // ✅ Redirect using invoice_no from JSON response
            setTimeout(() => {
                if (response.data.invoice_no) {
                    window.location.href = route(
                        "order.success",
                        response.data.invoice_no
                    );
                } else {
                    // Fallback just in case
                    window.location.href = "/";
                }
            }, 2000);
        } else if (response.data.url) {
            window.location.href = response.data.url;
        }
    } catch (error) {
        isSubmitting.value = false;

        if (error.response && error.response.data.errors) {
            // Set errors to form object
            Object.keys(error.response.data.errors).forEach((key) => {
                form.setError(key, error.response.data.errors[key][0]);
            });
            showToast("Please fix the errors in the form.", "error");
        } else {
            console.error(error);
            showToast("Something went wrong. Please try again.", "error");
        }
    }
};

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

const districts = Object.keys(locations).sort();
const availableThanas = ref([]);

watch(
    () => form.city,
    (newCity) => {
        if (newCity && locations[newCity]) {
            availableThanas.value = locations[newCity].sort();
            form.thana = "";
        } else {
            availableThanas.value = [];
            form.thana = "";
        }
    }
);
</script>

<template>
    <Head title="Secure Checkout" />

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
            class="fixed bottom-6 right-6 z-[9999] max-w-sm w-full border shadow-[0_10px_40px_-10px_rgba(0,0,0,0.5)] rounded-2xl p-4 flex items-center gap-4 backdrop-blur-xl border-l-4 animate-pulse-slow"
            :class="
                toastType === 'success'
                    ? 'bg-[#1A1F2E] border-green-500/20 border-l-green-500'
                    : 'bg-[#1A1F2E] border-red-500/20 border-l-red-500'
            "
        >
            <div
                class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 shadow-inner ring-1"
                :class="
                    toastType === 'success'
                        ? 'bg-green-500/10 ring-green-500/20'
                        : 'bg-red-500/10 ring-red-500/20'
                "
            >
                <CheckCircleIcon
                    v-if="toastType === 'success'"
                    class="w-6 h-6 text-green-400"
                />
                <XCircleIcon v-else class="w-6 h-6 text-red-400" />
            </div>

            <div class="flex-1">
                <h4 class="font-bold text-sm tracking-wide text-white">
                    {{ toastType === "success" ? "Success!" : "Error!" }}
                </h4>
                <p
                    class="text-xs mt-0.5 font-medium"
                    :class="
                        toastType === 'success'
                            ? 'text-green-200/70'
                            : 'text-red-200/70'
                    "
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
                    class="flex flex-col md:flex-row items-center justify-between mb-10 gap-4"
                >
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <Link href="/" class="hover:text-white transition"
                            ><HomeIcon class="w-4 h-4"
                        /></Link>
                        <span>/</span>
                        <Link
                            href="/cart"
                            class="hover:text-white transition"
                            >{{ __("Cart") }}</Link
                        >
                        <span>/</span>
                        <span class="text-white font-bold">{{
                            __("Checkout")
                        }}</span>
                    </div>
                    <div
                        class="flex items-center gap-2 bg-green-500/10 px-4 py-2 rounded-full border border-green-500/20"
                    >
                        <ShieldCheckIcon class="w-5 h-5 text-green-400" />
                        <span class="text-sm font-bold text-green-400">{{
                            __("100% Secure Checkout")
                        }}</span>
                    </div>
                </div>

                <form
                    @submit.prevent="submitOrder"
                    class="grid grid-cols-1 lg:grid-cols-12 gap-8"
                >
                    <div class="lg:col-span-8 space-y-8">
                        <div
                            class="bg-white/5 backdrop-blur-md p-6 md:p-8 rounded-3xl border border-white/10 shadow-xl"
                        >
                            <h2
                                class="text-xl font-bold text-white mb-6 flex items-center gap-3"
                            >
                                <span
                                    class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold shadow-lg shadow-indigo-500/30"
                                    >1</span
                                >
                                {{ __("Shipping Information") }}
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2 md:col-span-1">
                                    <label
                                        class="block text-sm font-semibold text-gray-400 mb-2"
                                        >{{ __("Full Name") }}</label
                                    >
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none placeholder-gray-600"
                                        required
                                        placeholder="John Doe"
                                    />
                                    <p
                                        v-if="form.errors.name"
                                        class="text-rose-500 text-xs mt-1"
                                    >
                                        {{ form.errors.name }}
                                    </p>
                                </div>

                                <div class="col-span-2 md:col-span-1">
                                    <label
                                        class="block text-sm font-semibold text-gray-400 mb-2"
                                        >{{ __("Phone Number") }}</label
                                    >
                                    <input
                                        v-model="form.phone"
                                        type="tel"
                                        class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none placeholder-gray-600"
                                        required
                                        placeholder="017xxxxxxxx"
                                    />
                                    <p
                                        v-if="form.errors.phone"
                                        class="text-rose-500 text-xs mt-1"
                                    >
                                        {{ form.errors.phone }}
                                    </p>
                                </div>

                                <div class="col-span-2">
                                    <label
                                        class="block text-sm font-semibold text-gray-400 mb-2"
                                        >{{ __("Full Address") }}</label
                                    >
                                    <textarea
                                        v-model="form.address"
                                        rows="2"
                                        class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none placeholder-gray-600 resize-none"
                                        required
                                        placeholder="House #12, Road #5, Block #A..."
                                    ></textarea>
                                    <p
                                        v-if="form.errors.address"
                                        class="text-rose-500 text-xs mt-1"
                                    >
                                        {{ form.errors.address }}
                                    </p>
                                </div>

                                <div class="col-span-2 md:col-span-1">
                                    <label
                                        class="block text-sm font-semibold text-gray-400 mb-2"
                                        >{{ __("District") }}</label
                                    >
                                    <div class="relative">
                                        <select
                                            v-model="form.city"
                                            class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none appearance-none cursor-pointer custom-select"
                                            required
                                        >
                                            <option
                                                value=""
                                                disabled
                                                selected
                                                class="bg-[#0B0F19] text-gray-500"
                                            >
                                                {{ __("Select District") }}
                                            </option>
                                            <option
                                                v-for="dist in districts"
                                                :key="dist"
                                                :value="dist"
                                                class="bg-[#0B0F19] text-gray-200"
                                            >
                                                {{ dist }}
                                            </option>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400"
                                        >
                                            <ChevronDownIcon class="w-4 h-4" />
                                        </div>
                                    </div>
                                    <p
                                        v-if="form.errors.city"
                                        class="text-rose-500 text-xs mt-1"
                                    >
                                        {{ form.errors.city }}
                                    </p>
                                </div>

                                <div class="col-span-2 md:col-span-1">
                                    <label
                                        class="block text-sm font-semibold text-gray-400 mb-2"
                                        >{{ __("Thana / Upazila") }}</label
                                    >
                                    <div class="relative">
                                        <select
                                            v-model="form.thana"
                                            class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none appearance-none cursor-pointer custom-select disabled:opacity-50 disabled:cursor-not-allowed"
                                            :disabled="!form.city"
                                            required
                                        >
                                            <option
                                                value=""
                                                disabled
                                                selected
                                                class="bg-[#0B0F19] text-gray-500"
                                            >
                                                {{
                                                    form.city
                                                        ? __("Select Thana")
                                                        : __(
                                                              "Select District First"
                                                          )
                                                }}
                                            </option>
                                            <option
                                                v-for="thana in availableThanas"
                                                :key="thana"
                                                :value="thana"
                                                class="bg-[#0B0F19] text-gray-200"
                                            >
                                                {{ thana }}
                                            </option>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400"
                                        >
                                            <ChevronDownIcon class="w-4 h-4" />
                                        </div>
                                    </div>
                                    <p
                                        v-if="form.errors.thana"
                                        class="text-rose-500 text-xs mt-1"
                                    >
                                        {{ form.errors.thana }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white/5 backdrop-blur-md p-6 md:p-8 rounded-3xl border border-white/10 shadow-xl"
                        >
                            <h2
                                class="text-xl font-bold text-white mb-6 flex items-center gap-3"
                            >
                                <span
                                    class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold shadow-lg shadow-indigo-500/30"
                                    >2</span
                                >
                                {{ __("Payment Method") }}
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="cursor-pointer relative group">
                                    <input
                                        type="radio"
                                        v-model="form.payment_method"
                                        value="cod"
                                        class="peer sr-only"
                                    />
                                    <div
                                        class="p-5 border border-white/10 bg-white/5 rounded-2xl transition-all duration-300 peer-checked:border-green-500 peer-checked:bg-green-500/10 flex items-center gap-4 group-hover:border-white/30"
                                    >
                                        <div
                                            class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center text-green-400"
                                        >
                                            <BanknotesIcon class="w-6 h-6" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-white">
                                                Cash on Delivery
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                Pay when you receive
                                            </p>
                                        </div>
                                        <CheckBadgeIcon
                                            class="w-6 h-6 text-green-500 ml-auto opacity-0 peer-checked:opacity-100 transition-opacity"
                                        />
                                    </div>
                                </label>

                                <label class="cursor-pointer relative group">
                                    <input
                                        type="radio"
                                        v-model="form.payment_method"
                                        value="sslcommerz"
                                        class="peer sr-only"
                                    />
                                    <div
                                        class="p-5 border border-white/10 bg-white/5 rounded-2xl transition-all duration-300 peer-checked:border-blue-500 peer-checked:bg-blue-500/10 flex items-center gap-4 group-hover:border-white/30"
                                    >
                                        <div
                                            class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center text-blue-400"
                                        >
                                            <CreditCardIcon class="w-6 h-6" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-white">
                                                Pay Online
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                Cards, Mobile Banking
                                            </p>
                                        </div>
                                        <CheckBadgeIcon
                                            class="w-6 h-6 text-blue-500 ml-auto opacity-0 peer-checked:opacity-100 transition-opacity"
                                        />
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-4">
                        <div
                            class="bg-white/5 backdrop-blur-md p-6 rounded-3xl border border-white/10 shadow-xl sticky top-24"
                        >
                            <h3
                                class="text-lg font-bold text-white mb-6 pb-4 border-b border-white/10"
                            >
                                {{ __("Order Summary") }}
                            </h3>

                            <div
                                class="space-y-4 max-h-80 overflow-y-auto custom-scrollbar mb-6 pr-2"
                            >
                                <div
                                    v-for="item in cartStore.items"
                                    :key="item.cartId"
                                    class="flex gap-4"
                                >
                                    <div
                                        class="w-16 h-16 bg-white rounded-xl border border-white/10 flex-shrink-0 p-1"
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
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-sm font-bold text-gray-200 truncate"
                                        >
                                            {{ getLocalizedName(item.name) }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Qty: {{ item.quantity }}
                                        </p>
                                    </div>
                                    <div class="text-sm font-bold text-white">
                                        ৳{{ item.price * item.quantity }}
                                    </div>
                                </div>
                            </div>

                            <div
                                class="space-y-3 py-6 border-t border-b border-white/10 text-sm"
                            >
                                <div class="flex justify-between text-gray-400">
                                    <span>{{ __("Subtotal") }}</span>
                                    <span class="font-bold text-white"
                                        >৳{{ cartStore.totalPrice }}</span
                                    >
                                </div>
                                <div class="flex justify-between text-gray-400">
                                    <span class="flex items-center gap-1"
                                        ><TruckIcon class="w-4 h-4" />
                                        {{ __("Delivery Charge") }}</span
                                    >
                                    <span class="font-bold text-white"
                                        >৳{{ shippingCost }}</span
                                    >
                                </div>
                                <div
                                    v-if="appliedCoupon"
                                    class="flex justify-between text-green-400 animate-pulse"
                                >
                                    <span class="flex items-center gap-1"
                                        ><TicketIcon class="w-4 h-4" />
                                        {{ __("Discount") }} ({{
                                            appliedCoupon.code
                                        }})</span
                                    >
                                    <span class="font-bold"
                                        >-৳{{ discountAmount }}</span
                                    >
                                </div>
                            </div>

                            <div class="flex justify-between items-center py-6">
                                <span class="text-lg font-bold text-white">{{
                                    __("Total to Pay")
                                }}</span>
                                <span
                                    class="text-2xl font-black text-indigo-400"
                                    >৳{{ grandTotal }}</span
                                >
                            </div>

                            <button
                                type="submit"
                                :disabled="
                                    isSubmitting || cartStore.items.length === 0
                                "
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-xl font-bold shadow-lg shadow-indigo-500/30 transform transition-all active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed flex justify-center items-center gap-2"
                            >
                                <LockClosedIcon
                                    v-if="!isSubmitting"
                                    class="w-5 h-5"
                                />
                                <span v-if="isSubmitting">{{
                                    __("Processing...")
                                }}</span>
                                <span v-else>{{ __("Place Order") }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}
option {
    background-color: #0b0f19;
    color: #e5e7eb;
}
/* Autofill Fix */
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus,
textarea:-webkit-autofill,
textarea:-webkit-autofill:hover,
textarea:-webkit-autofill:focus,
select:-webkit-autofill,
select:-webkit-autofill:hover,
select:-webkit-autofill:focus {
    -webkit-text-fill-color: #e5e7eb;
    -webkit-box-shadow: 0 0 0px 1000px #131722 inset;
    transition: background-color 5000s ease-in-out 0s;
}
</style>
