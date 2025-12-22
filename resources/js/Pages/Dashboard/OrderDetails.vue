<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import {
    CheckCircleIcon,
    TruckIcon,
    CubeIcon,
    HomeIcon,
    StarIcon,
    ArrowLeftIcon,
    ShoppingBagIcon,
    MapPinIcon,
    CreditCardIcon,
    HandThumbUpIcon,
    ChatBubbleLeftEllipsisIcon,
} from "@heroicons/vue/24/solid";
import { ref } from "vue";

const props = defineProps({
    order: Object,
});

// --- 🚚 Tracking Status Logic ---
const statuses = ["pending", "processing", "shipped", "delivered"];
const currentStatusIndex = statuses.indexOf(props.order.status);

const getStepClass = (stepStatus) => {
    const stepIndex = statuses.indexOf(stepStatus);
    if (stepIndex < currentStatusIndex)
        return "bg-indigo-600 text-white shadow-lg shadow-indigo-500/50";
    if (stepIndex === currentStatusIndex)
        return "bg-indigo-600 ring-4 ring-indigo-500/30 text-white animate-pulse shadow-lg shadow-indigo-500/50";
    return "bg-white/5 text-gray-500 border border-white/10";
};

// --- ⭐ Review Logic ---
const showReviewModal = ref(false);
const showSuccessMessage = ref(false);
const selectedProduct = ref(null);

const form = useForm({
    product_id: null,
    order_id: props.order.id,
    rating: 5,
    comment: "",
});

const openReviewModal = (item) => {
    selectedProduct.value = item.product;
    form.product_id = item.product.id;
    showReviewModal.value = true;
};

const submitReview = () => {
    form.post(route("reviews.store"), {
        preserveScroll: true, // এটি রিভিউ দেওয়ার পর পেজকে একই জায়গায় রাখবে
        onSuccess: () => {
            showReviewModal.value = false;
            showSuccessMessage.value = true;
            form.reset();
            setTimeout(() => {
                showSuccessMessage.value = false;
            }, 4000);
        },
    });
};
</script>

<template>
    <Head title="Order Details" />
    <MainLayout>
        <div
            class="py-12 bg-[#0B0F19] min-h-screen text-gray-200 relative overflow-hidden font-sans"
        >
            <div
                class="absolute top-0 left-0 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[150px] pointer-events-none"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[150px] pointer-events-none"
            ></div>

            <div class="container mx-auto px-4 lg:px-8 max-w-5xl relative z-10">
                <Link
                    :href="route('dashboard.orders')"
                    class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-8 transition-colors group"
                >
                    <div
                        class="p-2 rounded-full bg-white/5 group-hover:bg-white/10 transition-colors"
                    >
                        <ArrowLeftIcon class="w-4 h-4" />
                    </div>
                    Back to Orders
                </Link>

                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4"
                >
                    <div>
                        <h1
                            class="text-3xl md:text-4xl font-black text-white tracking-tight flex items-center gap-3"
                        >
                            Order
                            <span class="text-indigo-400"
                                >#{{ order.invoice_no }}</span
                            >
                        </h1>
                        <p class="text-sm text-gray-400 mt-2">
                            Placed on
                            <span class="font-bold text-gray-300">{{
                                new Date(order.created_at).toDateString()
                            }}</span>
                        </p>
                    </div>
                    <span
                        class="px-5 py-2 rounded-full text-sm font-bold uppercase tracking-wider border backdrop-blur-md shadow-lg"
                        :class="
                            order.status === 'delivered'
                                ? 'bg-green-500/10 text-green-400 border-green-500/20'
                                : 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20'
                        "
                    >
                        {{ order.status }}
                    </span>
                </div>

                <div
                    class="bg-white/5 border border-white/10 rounded-3xl p-8 mb-8 relative overflow-hidden backdrop-blur-xl shadow-2xl"
                    v-if="order.status !== 'cancelled'"
                >
                    <div
                        class="absolute top-1/2 left-8 right-8 h-0.5 bg-white/5 -translate-y-1/2 rounded-full"
                    ></div>
                    <div
                        class="absolute top-1/2 left-8 h-0.5 bg-indigo-500 -translate-y-1/2 transition-all duration-1000 rounded-full shadow-[0_0_15px_rgba(99,102,241,0.6)]"
                        :style="{
                            width: `calc(${
                                (currentStatusIndex / (statuses.length - 1)) *
                                100
                            }% - 4rem)`,
                        }"
                    ></div>

                    <div
                        class="flex justify-between items-center relative z-10"
                    >
                        <div
                            v-for="(step, index) in statuses"
                            :key="step"
                            class="flex flex-col items-center gap-4"
                        >
                            <div
                                class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-500"
                                :class="getStepClass(step)"
                            >
                                <CheckCircleIcon
                                    v-if="
                                        statuses.indexOf(step) <
                                        currentStatusIndex
                                    "
                                    class="w-6 h-6"
                                />
                                <CubeIcon
                                    v-else-if="step === 'processing'"
                                    class="w-6 h-6"
                                />
                                <TruckIcon
                                    v-else-if="step === 'shipped'"
                                    class="w-6 h-6"
                                />
                                <HomeIcon
                                    v-else-if="step === 'delivered'"
                                    class="w-6 h-6"
                                />
                                <span
                                    v-else
                                    class="w-3 h-3 bg-gray-600 rounded-full"
                                ></span>
                            </div>
                            <span
                                class="text-[10px] font-black uppercase tracking-widest"
                                :class="
                                    index <= currentStatusIndex
                                        ? 'text-indigo-400'
                                        : 'text-gray-600'
                                "
                                >{{ step }}</span
                            >
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-6">
                        <div
                            class="bg-white/5 border border-white/10 rounded-3xl p-6 backdrop-blur-md shadow-xl"
                        >
                            <h3
                                class="text-lg font-bold text-white mb-6 flex items-center gap-2 uppercase tracking-tighter"
                            >
                                <ShoppingBagIcon
                                    class="w-5 h-5 text-indigo-400"
                                />
                                Your Ordered Items
                            </h3>
                            <div class="space-y-4">
                                <div
                                    v-for="item in order.items"
                                    :key="item.id"
                                    class="flex flex-col sm:flex-row items-center gap-6 p-5 rounded-2xl bg-white/5 border border-white/5 transition-all"
                                >
                                    <div
                                        class="w-24 h-24 rounded-2xl bg-white p-2 flex-shrink-0 shadow-2xl overflow-hidden"
                                    >
                                        <img
                                            :src="
                                                '/storage/' +
                                                item.product.thumb_image
                                            "
                                            class="w-full h-full object-contain"
                                        />
                                    </div>
                                    <div
                                        class="flex-1 text-center sm:text-left"
                                    >
                                        <h4
                                            class="text-white font-bold text-lg leading-snug mb-1"
                                        >
                                            {{
                                                item.product.name.en ||
                                                item.product.name
                                            }}
                                        </h4>
                                        <p
                                            class="text-sm text-gray-400 font-medium"
                                        >
                                            Quantity: {{ item.quantity }}
                                            <span class="mx-2">|</span> ৳{{
                                                item.price
                                            }}
                                        </p>
                                    </div>

                                    <div v-if="order.status === 'delivered'">
                                        <div
                                            v-if="item.review"
                                            class="flex flex-col items-center sm:items-end gap-2 bg-indigo-500/5 p-4 rounded-2xl border border-indigo-500/20 min-w-[200px]"
                                        >
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <div class="flex gap-0.5">
                                                    <StarIcon
                                                        v-for="i in 5"
                                                        :key="i"
                                                        class="w-4 h-4"
                                                        :class="
                                                            i <=
                                                            item.review.rating
                                                                ? 'text-yellow-400'
                                                                : 'text-gray-700'
                                                        "
                                                    />
                                                </div>
                                                <span
                                                    class="text-[10px] font-black text-indigo-400 uppercase tracking-tighter"
                                                    >Verified Review</span
                                                >
                                            </div>
                                            <p
                                                v-if="item.review.comment"
                                                class="text-xs text-gray-300 italic text-center sm:text-right line-clamp-2 mt-1"
                                            >
                                                "{{ item.review.comment }}"
                                            </p>
                                        </div>

                                        <button
                                            v-else
                                            @click="openReviewModal(item)"
                                            class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2 active:scale-95 group uppercase tracking-widest"
                                        >
                                            <StarIcon
                                                class="w-4 h-4 group-hover:rotate-12 transition-transform"
                                            />
                                            Review Product
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div
                            class="bg-white/5 border border-white/10 rounded-3xl p-6 shadow-xl"
                        >
                            <h3
                                class="text-lg font-bold text-white mb-6 flex items-center gap-2 uppercase tracking-tighter"
                            >
                                <MapPinIcon class="w-5 h-5 text-purple-400" />
                                Shipping
                            </h3>
                            <div class="space-y-4 text-sm">
                                <div
                                    class="bg-black/20 p-4 rounded-2xl border border-white/5"
                                >
                                    <p
                                        class="text-[10px] text-gray-500 uppercase font-bold mb-1"
                                    >
                                        Customer
                                    </p>
                                    <p class="text-white font-bold text-lg">
                                        {{ order.name }}
                                    </p>
                                    <p class="text-gray-400">
                                        {{ order.phone }}
                                    </p>
                                </div>
                                <div
                                    class="bg-black/20 p-4 rounded-2xl border border-white/5"
                                >
                                    <p
                                        class="text-[10px] text-gray-500 uppercase font-bold mb-1"
                                    >
                                        Address
                                    </p>
                                    <p
                                        class="text-gray-300 leading-relaxed font-medium"
                                    >
                                        {{ order.address }}, {{ order.city }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white/5 border border-white/10 rounded-3xl p-6 shadow-xl text-sm"
                        >
                            <h3
                                class="text-lg font-bold text-white mb-6 flex items-center gap-2 uppercase tracking-tighter"
                            >
                                <CreditCardIcon
                                    class="w-5 h-5 text-emerald-400"
                                />
                                Billing
                            </h3>
                            <div class="space-y-3">
                                <div
                                    class="flex justify-between text-gray-400 font-medium"
                                >
                                    <span>Subtotal</span
                                    ><span>৳{{ order.total_amount }}</span>
                                </div>
                                <div
                                    class="flex justify-between text-gray-400 font-medium"
                                >
                                    <span>Shipping</span
                                    ><span class="text-emerald-400">Free</span>
                                </div>
                                <div
                                    class="border-t border-white/10 pt-4 flex justify-between items-center text-white font-black"
                                >
                                    <span
                                        class="text-lg uppercase tracking-tighter"
                                        >Paid Total</span
                                    ><span
                                        class="text-2xl text-indigo-400 tracking-tighter"
                                        >৳{{ order.total_amount }}</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="showReviewModal"
            class="fixed inset-0 z-[60] flex items-center justify-center p-4"
        >
            <div
                class="absolute inset-0 bg-black/90 backdrop-blur-md"
                @click="showReviewModal = false"
            ></div>
            <div
                class="bg-[#0B0F19] border border-white/10 w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl relative z-10 animate-fade-in-up"
            >
                <button
                    @click="showReviewModal = false"
                    class="absolute top-6 right-6 p-2 text-gray-500 hover:text-white transition"
                >
                    ✕
                </button>
                <div class="text-center mb-8">
                    <h3
                        class="text-3xl font-black text-white mb-2 tracking-tighter uppercase"
                    >
                        Rate It
                    </h3>
                    <p class="text-sm text-gray-400">
                        Share your experience with <br /><span
                            class="text-indigo-400 font-black"
                            >{{ selectedProduct?.name?.en }}</span
                        >
                    </p>
                </div>

                <form @submit.prevent="submitReview">
                    <div class="mb-10 flex justify-center gap-3">
                        <button
                            type="button"
                            v-for="i in 5"
                            :key="i"
                            @click="form.rating = i"
                            class="group focus:outline-none transition-transform hover:scale-125 active:scale-90"
                        >
                            <StarIcon
                                class="w-12 h-12 transition-colors duration-300"
                                :class="
                                    i <= form.rating
                                        ? 'text-yellow-400 drop-shadow-[0_0_15px_rgba(250,204,21,0.6)]'
                                        : 'text-gray-800 group-hover:text-gray-600'
                                "
                            />
                        </button>
                    </div>
                    <div class="mb-8">
                        <label
                            class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3"
                            >Your Comment</label
                        >
                        <textarea
                            v-model="form.comment"
                            rows="4"
                            class="w-full bg-black/40 border border-white/10 rounded-2xl p-5 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none resize-none transition-all"
                            placeholder="Tell us what you liked..."
                        ></textarea>
                    </div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-br from-indigo-600 to-indigo-800 text-white font-black py-5 rounded-2xl transition shadow-2xl shadow-indigo-600/30 active:scale-95 flex items-center justify-center uppercase tracking-widest text-xs"
                    >
                        <span v-if="form.processing">Submitting...</span>
                        <span v-else>Submit Verified Review</span>
                    </button>
                </form>
            </div>
        </div>

        <transition name="fade">
            <div
                v-if="showSuccessMessage"
                class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[70] flex items-center gap-4 bg-green-500 text-white px-8 py-5 rounded-[2rem] shadow-[0_20px_50px_rgba(34,197,94,0.4)] animate-bounce-in"
            >
                <div class="bg-white/20 p-2.5 rounded-full">
                    <HandThumbUpIcon class="w-7 h-7" />
                </div>
                <div>
                    <p
                        class="font-black text-base uppercase tracking-tighter leading-none"
                    >
                        Review Captured!
                    </p>
                    <p class="text-xs font-bold opacity-80 mt-1">
                        Thank you for your valuable feedback.
                    </p>
                </div>
            </div>
        </transition>
    </MainLayout>
</template>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
.animate-bounce-in {
    animation: bounceIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(40px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
@keyframes bounceIn {
    0% {
        transform: translate(-50%, 100px) scale(0.5);
        opacity: 0;
    }
    100% {
        transform: translate(-50%, 0) scale(1);
        opacity: 1;
    }
}

.fade-leave-active {
    transition: all 0.5s ease;
}
.fade-leave-to {
    opacity: 0;
    transform: translate(-50%, 20px);
}
</style>
