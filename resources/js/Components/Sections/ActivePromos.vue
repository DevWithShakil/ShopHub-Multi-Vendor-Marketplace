<script setup>
import { ref } from "vue";
import {
    TicketIcon,
    ClipboardDocumentIcon,
    CheckIcon,
    SparklesIcon,
} from "@heroicons/vue/24/outline";

// ✅ Props Receive
const props = defineProps({
    promos: {
        type: Array,
        default: () => [],
    },
});

// Copy Logic
const copiedId = ref(null);

const copyCode = (code, id) => {
    navigator.clipboard.writeText(code);
    copiedId.value = id;
    setTimeout(() => (copiedId.value = null), 2000);
};
</script>

<template>
    <section
        v-if="promos && promos.length > 0"
        class="relative py-12 bg-[#0B0F19] overflow-hidden"
    >
        <div
            class="absolute top-0 left-1/4 w-96 h-96 bg-purple-600/20 rounded-full blur-[120px] pointer-events-none"
        ></div>
        <div
            class="absolute bottom-0 right-1/4 w-96 h-96 bg-pink-600/10 rounded-full blur-[100px] pointer-events-none"
        ></div>

        <div class="container mx-auto px-4 lg:px-6 relative z-10">
            <div
                class="flex flex-col items-center justify-center text-center mb-10"
            >
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-pink-500/10 to-purple-500/10 border border-pink-500/20 mb-4"
                >
                    <SparklesIcon class="w-4 h-4 text-pink-400 animate-pulse" />
                    <span
                        class="text-xs font-semibold text-pink-300 tracking-wider uppercase"
                        >Limited Time Offers</span
                    >
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">
                    Exclusive
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-purple-500"
                        >Promotions</span
                    >
                </h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <div
                    v-for="promo in promos"
                    :key="promo.id"
                    class="group relative"
                >
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-pink-500 to-purple-600 rounded-xl blur opacity-0 group-hover:opacity-75 transition duration-500"
                    ></div>

                    <div
                        class="relative h-full bg-[#12141c] rounded-lg border border-white/5 p-0 flex flex-col overflow-hidden"
                    >
                        <div
                            class="h-1 w-full bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500"
                        ></div>

                        <div
                            class="p-4 flex-1 flex flex-col items-center text-center relative"
                        >
                            <div
                                class="w-10 h-10 mb-3 rounded-lg bg-[#1E2330] p-0.5 border border-white/10 shadow-lg group-hover:scale-110 transition-transform duration-300"
                            >
                                <img
                                    v-if="promo.vendor_logo"
                                    :src="promo.vendor_logo"
                                    class="w-full h-full object-cover rounded-md"
                                    alt="Vendor"
                                />
                                <div
                                    v-else
                                    class="w-full h-full rounded-md bg-gradient-to-br from-gray-800 to-black flex items-center justify-center"
                                >
                                    <TicketIcon class="w-5 h-5 text-gray-500" />
                                </div>
                            </div>

                            <div class="mb-0.5">
                                <h3
                                    class="text-2xl font-black text-white tracking-tight leading-none"
                                >
                                    {{ parseInt(promo.value)
                                    }}<span class="text-base text-pink-500">{{
                                        promo.type === "percentage" ? "%" : "৳"
                                    }}</span>
                                </h3>
                            </div>
                            <span
                                class="text-[10px] font-medium text-gray-400 uppercase tracking-widest mb-3"
                                >Discount</span
                            >

                            <div
                                class="w-full relative flex items-center justify-center my-3"
                            >
                                <div
                                    class="absolute left-0 w-full border-t border-dashed border-gray-700"
                                ></div>
                                <div
                                    class="absolute -left-6 w-4 h-4 bg-[#0B0F19] rounded-full"
                                ></div>
                                <div
                                    class="absolute -right-6 w-4 h-4 bg-[#0B0F19] rounded-full"
                                ></div>
                            </div>

                            <div class="mt-1 mb-4 space-y-0.5">
                                <p
                                    class="text-xs text-gray-300 truncate w-full px-2"
                                    :title="promo.vendor_name"
                                >
                                    Valid at
                                    <span class="text-white font-semibold">{{
                                        promo.vendor_name
                                    }}</span>
                                </p>
                                <p class="text-[10px] text-gray-500">
                                    Exp:
                                    {{
                                        new Date(
                                            promo.expires_at
                                        ).toLocaleDateString()
                                    }}
                                </p>
                            </div>

                            <button
                                @click="copyCode(promo.code, promo.id)"
                                class="w-full relative overflow-hidden rounded bg-[#1E2330] hover:bg-[#252a3a] border border-white/10 group/btn transition-all duration-300 py-2"
                            >
                                <div
                                    class="flex items-center justify-center gap-1.5 relative z-10"
                                >
                                    <span
                                        class="font-mono font-bold text-sm tracking-wider"
                                        :class="
                                            copiedId === promo.id
                                                ? 'text-green-400'
                                                : 'text-pink-400 group-hover/btn:text-pink-300'
                                        "
                                    >
                                        {{
                                            copiedId === promo.id
                                                ? "COPIED!"
                                                : promo.code
                                        }}
                                    </span>
                                    <component
                                        :is="
                                            copiedId === promo.id
                                                ? CheckIcon
                                                : ClipboardDocumentIcon
                                        "
                                        class="w-3.5 h-3.5"
                                        :class="
                                            copiedId === promo.id
                                                ? 'text-green-400'
                                                : 'text-gray-500 group-hover/btn:text-white'
                                        "
                                    />
                                </div>
                                <div
                                    class="absolute inset-0 bg-white/5 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300"
                                ></div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
