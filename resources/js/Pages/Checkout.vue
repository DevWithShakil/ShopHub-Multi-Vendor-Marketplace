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
    TicketIcon, // Imported for discount display
} from "@heroicons/vue/24/outline";
import { watch, ref, computed } from "vue";
import { locations } from "@/Data/bangladesh";

const cartStore = useCartStore();
const page = usePage();

// ✅ Coupon Data from Backend (Session)
const appliedCoupon = computed(() => page.props.coupon || null);

// ✅ Calculate Totals
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

const submitOrder = () => {
    // ✅ 1. Set Items and Calculated Total Price
    form.items = cartStore.items;
    form.total_price = grandTotal.value; // Use the discounted total

    form.post(route("checkout.store"), {
        onSuccess: () => {
            // ✅ 2. Clear cart only for COD (SSLCommerz redirects)
            if (form.payment_method === "cod") {
                cartStore.clearCart();
            }
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
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

// 📍 Location Logic
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
                                    form.processing ||
                                    cartStore.items.length === 0
                                "
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-xl font-bold shadow-lg shadow-indigo-500/30 transform transition-all active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed flex justify-center items-center gap-2"
                            >
                                <LockClosedIcon
                                    v-if="!form.processing"
                                    class="w-5 h-5"
                                />
                                <span v-if="form.processing">{{
                                    __("Processing...")
                                }}</span>
                                <span v-else>{{ __("Place Order") }}</span>
                            </button>

                            <p
                                class="mt-4 text-center text-[10px] text-gray-500"
                            >
                                {{
                                    __(
                                        "By placing this order, you agree to our Terms of Service and Privacy Policy."
                                    )
                                }}
                            </p>
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
</style>
