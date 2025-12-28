<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import {
    TicketIcon,
    ClipboardDocumentIcon,
    CheckIcon,
    SparklesIcon,
    TagIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    promos: {
        type: Array,
        default: () => [],
    },
});

const copiedId = ref(null);

// Copy Function
const copyCode = (code, id) => {
    navigator.clipboard.writeText(code);
    copiedId.value = id;
    setTimeout(() => (copiedId.value = null), 2000);
};

// Navigation Logic
const navigateToStore = (promo) => {
    if (promo.vendor_id) {
        // Navigate to shop index with vendor_id filter
        router.visit(route("shop.index", { vendor_id: promo.vendor_id }));
    } else {
        // Navigate to main shop index for global coupons
        router.visit(route("shop.index"));
    }
};

// Gradient Palettes for the "Stub" (Left Side)
const gradients = [
    "bg-gradient-to-br from-violet-600 via-purple-600 to-indigo-600",
    "bg-gradient-to-br from-pink-500 via-rose-500 to-red-500",
    "bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-600",
    "bg-gradient-to-br from-orange-400 via-amber-500 to-yellow-500",
];
</script>

<template>
    <section
        v-if="promos && promos.length > 0"
        class="relative py-20 bg-[#0B0F19] overflow-hidden"
    >
        <div
            class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none"
        >
            <div
                class="absolute top-[-10%] left-[-5%] w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[100px]"
            ></div>
            <div
                class="absolute bottom-[-10%] right-[-5%] w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[100px]"
            ></div>
        </div>

        <div class="container mx-auto px-4 lg:px-6 relative z-10">
            <div
                class="flex flex-col items-center justify-center text-center mb-12"
            >
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 mb-4 backdrop-blur-md"
                >
                    <SparklesIcon
                        class="w-4 h-4 text-yellow-400 animate-pulse"
                    />
                    <span
                        class="text-xs font-bold text-gray-300 uppercase tracking-widest"
                        >Grab Your Deal</span
                    >
                </div>
                <h2
                    class="text-3xl md:text-5xl font-black text-white tracking-tight mb-2"
                >
                    Active
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-500"
                        >Vouchers</span
                    >
                </h2>
                <p class="text-gray-400 text-sm">
                    Click on a voucher to visit the store.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    v-for="(promo, index) in promos"
                    :key="promo.id"
                    @click="navigateToStore(promo)"
                    class="group relative w-full filter drop-shadow-xl hover:drop-shadow-2xl transition-all duration-500 hover:-translate-y-2 cursor-pointer"
                >
                    <div
                        class="relative w-full h-44 flex rounded-2xl overflow-hidden bg-white"
                    >
                        <div
                            class="relative w-[35%] h-full flex flex-col items-center justify-center p-4 text-white z-10"
                            :class="gradients[index % gradients.length]"
                        >
                            <div
                                class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"
                            ></div>

                            <div
                                class="relative z-10 flex flex-col items-center text-center"
                            >
                                <h3
                                    class="text-4xl lg:text-5xl font-black leading-none drop-shadow-md"
                                >
                                    {{ parseInt(promo.value)
                                    }}<span
                                        class="text-xl lg:text-2xl align-top"
                                        >{{
                                            promo.type === "percentage"
                                                ? "%"
                                                : "৳"
                                        }}</span
                                    >
                                </h3>
                                <span
                                    class="text-sm font-bold opacity-90 tracking-widest mt-1"
                                    >OFF</span
                                >
                            </div>

                            <div
                                class="absolute -top-3 left-1/2 -translate-x-1/2 w-4 h-4 rounded-full bg-[#0B0F19]/30 blur-[1px]"
                            ></div>
                            <div
                                class="absolute -bottom-3 left-1/2 -translate-x-1/2 w-4 h-4 rounded-full bg-[#0B0F19]/30 blur-[1px]"
                            ></div>
                        </div>

                        <div
                            class="relative w-[65%] h-full bg-white flex flex-col justify-between p-4 pl-6 transition-colors group-hover:bg-gray-50"
                        >
                            <div
                                class="absolute top-2 bottom-2 left-0 w-[2px] border-l-2 border-dashed border-gray-300 z-20"
                            ></div>

                            <div
                                class="absolute -top-4 -left-4 w-8 h-8 rounded-full bg-[#0B0F19] z-30"
                            ></div>
                            <div
                                class="absolute -bottom-4 -left-4 w-8 h-8 rounded-full bg-[#0B0F19] z-30"
                            ></div>

                            <div>
                                <div
                                    class="flex justify-between items-start mb-2"
                                >
                                    <div
                                        class="flex items-center gap-2 overflow-hidden"
                                    >
                                        <div
                                            class="w-8 h-8 flex-shrink-0 rounded-full bg-gray-100 p-0.5 border border-gray-200 shadow-sm overflow-hidden"
                                        >
                                            <img
                                                v-if="promo.vendor_logo"
                                                :src="promo.vendor_logo"
                                                class="w-full h-full object-cover"
                                            />
                                            <TicketIcon
                                                v-else
                                                class="w-full h-full p-1.5 text-gray-400"
                                            />
                                        </div>
                                        <div
                                            class="flex flex-col overflow-hidden"
                                        >
                                            <span
                                                class="text-sm font-bold text-gray-800 truncate"
                                                :title="promo.vendor_name"
                                                >{{ promo.vendor_name }}</span
                                            >
                                        </div>
                                    </div>
                                    <div
                                        class="bg-red-50 text-red-600 text-[9px] font-bold px-2 py-0.5 rounded-full border border-red-100 whitespace-nowrap"
                                    >
                                        LIMITED
                                    </div>
                                </div>

                                <p class="text-xs text-gray-500 font-medium">
                                    Valid until
                                    <span class="text-gray-800 font-bold">{{
                                        new Date(
                                            promo.expires_at
                                        ).toLocaleDateString()
                                    }}</span>
                                </p>
                            </div>

                            <div class="mt-auto relative z-20">
                                <button
                                    @click.stop="copyCode(promo.code, promo.id)"
                                    class="w-full group/btn relative flex items-center justify-between bg-gray-50 hover:bg-gray-900 border border-gray-200 hover:border-gray-900 text-gray-900 hover:text-white rounded-lg px-3 py-2 transition-all duration-300"
                                >
                                    <div
                                        class="flex flex-col items-start overflow-hidden"
                                    >
                                        <span
                                            class="text-[9px] font-bold uppercase opacity-50 tracking-wider"
                                            >Coupon Code</span
                                        >
                                        <span
                                            class="font-mono font-bold text-base lg:text-lg tracking-wider truncate w-full text-left"
                                            >{{ promo.code }}</span
                                        >
                                    </div>

                                    <div
                                        class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-md bg-white text-gray-900 shadow-sm group-hover/btn:bg-white/20 group-hover/btn:text-white transition-all"
                                    >
                                        <CheckIcon
                                            v-if="copiedId === promo.id"
                                            class="w-5 h-5"
                                        />
                                        <ClipboardDocumentIcon
                                            v-else
                                            class="w-5 h-5"
                                        />
                                    </div>

                                    <span
                                        v-if="copiedId === promo.id"
                                        class="absolute inset-0 flex items-center justify-center bg-green-500 text-white font-bold rounded-lg animate-fade-in text-sm tracking-widest z-30"
                                    >
                                        COPIED!
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
@keyframes fade-in {
    0% {
        opacity: 0;
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}
.animate-fade-in {
    animation: fade-in 0.2s ease-out forwards;
}
</style>
