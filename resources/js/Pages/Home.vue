<script setup>
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

// --- Layout & Components ---
import MainLayout from "@/Layouts/MainLayout.vue";
import HeroSlider from "@/Components/HeroSlider.vue";
import CategoryMenu from "@/Components/CategoryMenu.vue";
import FlashSaleSection from "@/Components/FlashSaleSection.vue";
import NewArrivals from "@/Components/NewArrivals.vue";
import BestSellers from "@/Components/BestSellers.vue";
import TopRated from "@/Components/TopRated.vue";
import ServiceFeatures from "@/Components/Sections/ServiceFeatures.vue";
import PromoBanner from "@/Components/Sections/PromoBanner.vue";
import BrandMarquee from "@/Components/Sections/BrandMarquee.vue";
import Testimonials from "@/Components/Sections/Testimonials.vue";
import FAQSection from "@/Components/Sections/FAQSection.vue";
import ActivePromos from "@/Components/Sections/ActivePromos.vue";
import PromoPopup from "@/Components/Popups/PromoPopup.vue";
import RecentlyViewed from "@/Components/Sections/RecentlyViewed.vue";

// --- Icons ---
import {
    CheckCircleIcon,
    XMarkIcon,
    HeartIcon,
} from "@heroicons/vue/24/outline";

// --- Props ---
const props = defineProps({
    slides: Array,
    categories: Array,
    newArrivals: Array,
    topRated: Array,
    bestSellers: Array,
    flashSale: Object,
    brands: Array,
    testimonials: Array,
    activePromos: Array, // ✅ ১. এই লাইনটি যোগ করতে হবে
});

// --- 🔔 Toast Logic ---
const toastMessage = ref(null);
const toastType = ref("success");

const showToast = (message, type = "success") => {
    toastMessage.value = message;
    toastType.value = type;
    setTimeout(() => {
        toastMessage.value = null;
    }, 3000);
};
</script>

<template>
    <Head title="Home - ShopHub" />

    <PromoPopup />

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
            class="fixed bottom-6 right-6 z-[100] max-w-sm w-full border shadow-2xl rounded-2xl p-4 flex items-center gap-4 backdrop-blur-xl border-l-4"
            :class="
                toastType === 'success'
                    ? 'bg-[#1A1F2E] border-green-500/20 border-l-green-500'
                    : 'bg-[#1A1F2E] border-blue-500/20 border-l-blue-500'
            "
        >
            <div
                class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                :class="
                    toastType === 'success'
                        ? 'bg-green-500/10'
                        : 'bg-blue-500/10'
                "
            >
                <CheckCircleIcon
                    v-if="toastType === 'success'"
                    class="w-6 h-6 text-green-400"
                />
                <HeartIcon v-else class="w-6 h-6 text-blue-400" />
            </div>
            <div class="flex-1 text-white">
                <h4 class="font-bold text-sm">
                    {{ toastType === "success" ? "Success!" : "Updated" }}
                </h4>
                <p class="text-xs text-gray-400">{{ toastMessage }}</p>
            </div>
            <button
                @click="toastMessage = null"
                class="p-2 text-gray-400 hover:text-white"
            >
                <XMarkIcon class="w-4 h-4" />
            </button>
        </div>
    </transition>

    <MainLayout>
        <CategoryMenu :categories="categories" />
        <HeroSlider :slides="slides" />

        <FlashSaleSection v-if="flashSale" :saleData="flashSale" />
        <ActivePromos :promos="activePromos" />

        <div class="bg-[#0B0F19] pt-10 pb-4">
            <div class="container mx-auto px-4 lg:px-8 space-y-24">
                <NewArrivals :products="newArrivals" />

                <BestSellers :products="bestSellers" />

                <TopRated :products="topRated" />
                <PromoBanner />
                <ServiceFeatures />

                <BrandMarquee :brands="brands" />

                <Testimonials :testimonials="testimonials" />

                <FAQSection />
                <RecentlyViewed />
            </div>
        </div>
    </MainLayout>
</template>
