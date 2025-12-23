<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { computed } from "vue"; // ✅ computed ইমপোর্ট করা হলো
import {
    MagnifyingGlassIcon,
    CheckCircleIcon,
    TruckIcon,
    CubeIcon,
    HomeIcon,
    ExclamationCircleIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    order: Object,
    errors: Object,
});

const form = useForm({
    invoice_no: "",
});

const submitTrack = () => {
    form.post(route("track.order"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

// --- 🚚 Tracking Logic ---
const statuses = ["pending", "processing", "shipped", "delivered"];

// 🚀 ফিক্স: এটি এখন computed প্রপার্টি, তাই ডাটা আসার সাথে সাথে আপডেট হবে
const currentStatusIndex = computed(() => {
    if (!props.order) return -1;

    // স্ট্যাটাস ছোট হাতের করে চেক করা হচ্ছে
    const status = props.order.status.toLowerCase();

    // 'completed' এবং 'delivered' দুটোকেই শেষ ধাপ ধরা হবে
    if (status === "completed" || status === "delivered") {
        return 3; // delivered index
    }

    return statuses.indexOf(status);
});

const getStepClass = (stepStatus) => {
    const stepIndex = statuses.indexOf(stepStatus);

    // কমপ্লিট হয়ে যাওয়া ধাপগুলো
    if (stepIndex < currentStatusIndex.value)
        return "bg-indigo-600 text-white shadow-lg shadow-indigo-500/50 border-indigo-600";

    // বর্তমান ধাপ (বা ডেলিভারড হলে শেষ ধাপ)
    if (stepIndex === currentStatusIndex.value)
        return "bg-indigo-600 ring-4 ring-indigo-500/30 text-white shadow-lg shadow-indigo-500/50";

    // বাকি ধাপগুলো
    return "bg-[#0B0F19] text-gray-600 border-2 border-gray-700";
};
</script>

<template>
    <Head title="Track Order" />

    <MainLayout>
        <div
            class="min-h-screen bg-[#0B0F19] py-20 relative overflow-hidden font-sans text-gray-200 flex flex-col items-center justify-center"
        >
            <div
                class="absolute top-0 left-0 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[150px] pointer-events-none"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[150px] pointer-events-none"
            ></div>

            <div class="container mx-auto px-4 lg:px-8 relative z-10 max-w-3xl">
                <div class="text-center mb-12 animate-fade-in-up">
                    <h1
                        class="text-4xl md:text-5xl font-black text-white tracking-tight mb-4"
                    >
                        Track Your
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400"
                            >Order</span
                        >
                    </h1>
                    <p class="text-gray-400 text-lg">
                        Enter your Invoice ID to see the current status of your
                        package.
                    </p>
                </div>

                <div
                    class="bg-white/5 border border-white/10 rounded-3xl p-2 mb-12 shadow-2xl backdrop-blur-xl animate-fade-in-up delay-100"
                >
                    <form
                        @submit.prevent="submitTrack"
                        class="relative flex items-center"
                    >
                        <MagnifyingGlassIcon
                            class="absolute left-6 w-6 h-6 text-gray-500"
                        />
                        <input
                            v-model="form.invoice_no"
                            type="text"
                            placeholder="Enter Invoice ID (e.g. INV-KALB1G68)"
                            class="w-full bg-transparent border-none text-white text-lg font-bold placeholder-gray-600 pl-16 pr-4 py-4 focus:ring-0"
                        />
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-8 py-3 rounded-2xl transition-all shadow-lg shadow-indigo-500/30 m-2"
                        >
                            {{ form.processing ? "Tracking..." : "Track" }}
                        </button>
                    </form>
                </div>

                <div
                    v-if="errors.invoice_no"
                    class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-2xl flex items-center gap-3 mb-8 animate-shake"
                >
                    <ExclamationCircleIcon class="w-6 h-6" />
                    <span class="font-bold">{{ errors.invoice_no }}</span>
                </div>

                <div
                    v-if="order"
                    class="bg-[#151925] border border-white/10 rounded-[2.5rem] p-8 md:p-12 shadow-2xl relative overflow-hidden animate-scale-up"
                >
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 pb-8 border-b border-white/10 gap-4"
                    >
                        <div>
                            <p
                                class="text-xs text-indigo-400 font-bold uppercase tracking-widest mb-1"
                            >
                                Order Found
                            </p>
                            <h2 class="text-3xl font-black text-white">
                                {{ order.invoice_no }}
                            </h2>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-400">Total Amount</p>
                            <p class="text-2xl font-bold text-white">
                                ৳{{ order.total_amount }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="relative py-8 px-2"
                        v-if="order.status !== 'cancelled'"
                    >
                        <div
                            class="absolute top-1/2 left-0 w-full h-1.5 bg-gray-800 -translate-y-1/2 rounded-full"
                        ></div>

                        <div
                            class="absolute top-1/2 left-0 h-1.5 bg-gradient-to-r from-indigo-500 to-purple-500 -translate-y-1/2 transition-all duration-1000 rounded-full shadow-[0_0_15px_rgba(99,102,241,0.6)]"
                            :style="{
                                width: `calc(${
                                    (currentStatusIndex /
                                        (statuses.length - 1)) *
                                    100
                                }%)`,
                            }"
                        ></div>

                        <div
                            class="flex justify-between items-center relative z-10"
                        >
                            <div
                                v-for="(step, index) in statuses"
                                :key="step"
                                class="flex flex-col items-center gap-4 relative group"
                            >
                                <div
                                    class="w-14 h-14 rounded-full flex items-center justify-center transition-all duration-500 z-10"
                                    :class="getStepClass(step)"
                                >
                                    <CheckCircleIcon
                                        v-if="
                                            index < currentStatusIndex ||
                                            (currentStatusIndex === 3 &&
                                                index === 3)
                                        "
                                        class="w-7 h-7"
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
                                    class="text-[10px] md:text-xs font-bold uppercase tracking-wider absolute -bottom-8 whitespace-nowrap transition-colors duration-300"
                                    :class="
                                        index <= currentStatusIndex
                                            ? 'text-white'
                                            : 'text-gray-600'
                                    "
                                >
                                    {{ step }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div
                        v-else
                        class="text-center py-8 bg-red-500/5 rounded-2xl border border-red-500/10"
                    >
                        <p class="text-red-500 font-bold text-lg">
                            This order has been cancelled.
                        </p>
                    </div>

                    <div class="mt-16">
                        <h3
                            class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4"
                        >
                            Items in Order
                        </h3>
                        <div class="space-y-3">
                            <div
                                v-for="item in order.items"
                                :key="item.id"
                                class="flex items-center gap-4 bg-white/5 p-3 rounded-xl border border-white/5 hover:bg-white/10 transition"
                            >
                                <img
                                    :src="
                                        '/storage/' + item.product.thumb_image
                                    "
                                    class="w-14 h-14 object-contain bg-white rounded-lg p-2"
                                />
                                <div>
                                    <p
                                        class="text-white font-bold text-sm line-clamp-1"
                                    >
                                        {{
                                            item.product.name.en ||
                                            item.product.name
                                        }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Quantity:
                                        <span class="text-white font-bold">{{
                                            item.quantity
                                        }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
}
.animate-scale-up {
    animation: scaleUp 0.5s ease-out forwards;
}
.animate-shake {
    animation: shake 0.5s ease-in-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
@keyframes scaleUp {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
@keyframes shake {
    0%,
    100% {
        transform: translateX(0);
    }
    25% {
        transform: translateX(-5px);
    }
    75% {
        transform: translateX(5px);
    }
}
</style>
