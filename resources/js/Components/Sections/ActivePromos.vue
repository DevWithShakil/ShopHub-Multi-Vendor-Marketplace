<script setup>
import { ref } from "vue";
import {
    TagIcon,
    ClipboardDocumentIcon,
    CheckIcon,
    BuildingStorefrontIcon,
} from "@heroicons/vue/24/outline";

// Mock Data (পরবর্তীতে ডাটাবেজ থেকে আসবে)
const promos = ref([
    {
        id: 1,
        store: "Tech Village",
        code: "TECH2025",
        discount: "20% OFF",
        desc: "On all gaming accessories",
        min_spend: "Min spend ৳2000",
        color: "from-violet-600 to-indigo-600",
        copied: false,
    },
    {
        id: 2,
        store: "Fashion House",
        code: "SUMMER500",
        discount: "৳500 OFF",
        desc: "Flat discount on new arrivals",
        min_spend: "Min spend ৳5000",
        color: "from-pink-500 to-rose-500",
        copied: false,
    },
    {
        id: 3,
        store: "Gadget Gang",
        code: "FREE20",
        discount: "Free Ship",
        desc: "Free delivery all over Dhaka",
        min_spend: "No min spend",
        color: "from-emerald-500 to-teal-500",
        copied: false,
    },
]);

// Copy Function
const copyCode = (promo) => {
    navigator.clipboard.writeText(promo.code);
    promo.copied = true;

    // Reset after 2 seconds
    setTimeout(() => {
        promo.copied = false;
    }, 2000);
};
</script>

<template>
    <section class="py-16 relative bg-[#0B0F19]">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex items-center gap-3 mb-10">
                <div class="p-3 rounded-xl bg-white/5 border border-white/10">
                    <TagIcon class="w-6 h-6 text-yellow-400" />
                </div>
                <div>
                    <h3
                        class="text-2xl font-black text-white uppercase tracking-tight"
                    >
                        Active <span class="text-yellow-400">Promos</span>
                    </h3>
                    <p class="text-gray-400 text-sm">
                        Grab these vouchers before they expire!
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    v-for="promo in promos"
                    :key="promo.id"
                    class="relative group overflow-hidden rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl"
                >
                    <div
                        class="absolute inset-0 bg-gradient-to-br opacity-90 group-hover:opacity-100 transition-opacity"
                        :class="promo.color"
                    ></div>

                    <div
                        class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-[#0B0F19] rounded-full"
                    ></div>
                    <div
                        class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-[#0B0F19] rounded-full"
                    ></div>

                    <div
                        class="relative z-10 flex flex-col h-full justify-between text-white"
                    >
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div
                                    class="flex items-center gap-1.5 text-xs font-bold bg-black/20 px-2 py-1 rounded-lg w-fit mb-2"
                                >
                                    <BuildingStorefrontIcon class="w-3 h-3" />
                                    {{ promo.store }}
                                </div>
                                <h4 class="text-3xl font-black">
                                    {{ promo.discount }}
                                </h4>
                                <p class="text-sm opacity-90 mt-1">
                                    {{ promo.desc }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="w-full border-t-2 border-dashed border-white/30 my-4"
                        ></div>

                        <div class="flex justify-between items-center">
                            <p class="text-xs font-medium opacity-80">
                                {{ promo.min_spend }}
                            </p>

                            <button
                                @click="copyCode(promo)"
                                class="flex items-center gap-2 bg-white text-gray-900 px-4 py-2 rounded-lg font-bold text-sm hover:bg-gray-100 active:scale-95 transition-all shadow-lg cursor-pointer"
                            >
                                <span
                                    v-if="promo.copied"
                                    class="flex items-center gap-1 text-green-600"
                                >
                                    <CheckIcon class="w-4 h-4" /> Copied
                                </span>
                                <span v-else class="flex items-center gap-1">
                                    {{ promo.code }}
                                    <ClipboardDocumentIcon
                                        class="w-4 h-4 text-gray-500"
                                    />
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
