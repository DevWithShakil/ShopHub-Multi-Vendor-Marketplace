<script setup>
import { onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import { useWishlistStore } from "@/Stores/wishlistStore";
import NavBar from "@/Components/NavBar.vue";
import Footer from "@/Components/Footer.vue";
import MobileBottomNav from "@/Components/MobileBottomNav.vue";
import FloatingChat from "@/Components/FloatingChat.vue";

const wishlistStore = useWishlistStore();
const page = usePage();

onMounted(() => {
    if (page.props.auth?.wishlist_ids) {
        wishlistStore.setWishlist(page.props.auth.wishlist_ids);
    }
});
</script>

<template>
    <div
        class="min-h-screen font-sans text-gray-200 bg-[#0B0F19] flex flex-col"
    >
        <NavBar />

        <main class="flex-grow">
            <slot />
        </main>

        <Footer />
        <MobileBottomNav />
        <FloatingChat />
    </div>
</template>
