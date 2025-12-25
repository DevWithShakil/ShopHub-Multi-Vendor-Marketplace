<script setup>
import { ref, onMounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { EyeIcon } from "@heroicons/vue/24/outline";

const recentProducts = ref([]);
const page = usePage();

// --- Helper for Localized Name ---
const getLocalizedName = (nameField) => {
    try {
        if (typeof nameField === "string" && nameField.startsWith("{")) {
            const parsed = JSON.parse(nameField);
            return parsed[page.props.locale] || parsed["en"] || "Unknown";
        }
        if (typeof nameField === "object" && nameField !== null) {
            return nameField[page.props.locale] || nameField["en"] || "Unknown";
        }
        return nameField || nameField; // Return as is if simple string
    } catch (e) {
        return nameField;
    }
};

onMounted(() => {
    // লোকাল স্টোরেজ থেকে ডাটা আনা
    const stored = localStorage.getItem("recently_viewed");
    if (stored) {
        recentProducts.value = JSON.parse(stored).slice(0, 8); // সর্বোচ্চ ৮টি দেখাবে
    }
});
</script>

<template>
    <section
        v-if="recentProducts.length > 0"
        class="py-16 border-t border-white/10"
    >
        <div class="flex items-center gap-3 mb-8">
            <div class="p-2 bg-indigo-500/10 rounded-lg text-indigo-400">
                <EyeIcon class="w-6 h-6" />
            </div>
            <h3 class="text-2xl font-black text-white tracking-tight">
                Recently Viewed
            </h3>
        </div>

        <div class="flex gap-5 overflow-x-auto no-scrollbar pb-4">
            <Link
                v-for="product in recentProducts"
                :key="product.id"
                :href="route('product.details', product.slug)"
                class="min-w-[180px] w-[180px] bg-[#151925] rounded-2xl p-3 border border-white/5 hover:border-indigo-500/30 transition-all duration-300 group hover:-translate-y-1"
            >
                <div
                    class="h-32 bg-white rounded-xl overflow-hidden mb-3 p-2 relative"
                >
                    <img
                        :src="'/storage/' + product.thumb_image"
                        class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500"
                        :alt="getLocalizedName(product.name)"
                    />
                </div>

                <h4
                    class="text-white text-sm font-bold truncate group-hover:text-indigo-400 transition"
                >
                    {{ getLocalizedName(product.name) }}
                </h4>
                <p class="text-gray-400 text-xs font-medium mt-1">
                    <span class="text-white font-bold text-sm"
                        >৳{{ product.base_price }}</span
                    >
                </p>
            </Link>
        </div>
    </section>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
