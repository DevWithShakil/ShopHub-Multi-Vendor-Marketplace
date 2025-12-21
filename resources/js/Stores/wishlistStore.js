import { defineStore } from 'pinia';
import axios from 'axios';
import { usePage } from '@inertiajs/vue3';

export const useWishlistStore = defineStore('wishlist', {
    state: () => ({
        wishlistIds: [],
    }),

    getters: {
        count: (state) => state.wishlistIds.length,
        isInWishlist: (state) => (productId) => state.wishlistIds.includes(productId),
    },

    actions: {
        async fetchWishlist() {
            const user = usePage().props.auth.user;
            if (user) {

            }
        },

        setWishlist(ids) {
            this.wishlistIds = ids;
        },

        async toggle(productId) {
            const user = usePage().props.auth.user;

            if (!user) {
                window.location.href = '/login';
                return;
            }

            try {
                const response = await axios.post(route('wishlist.toggle'), { product_id: productId });

                this.wishlistIds = response.data.wishlist;

                console.log(response.data.message);

            } catch (error) {
                console.error("Wishlist error:", error);
            }
        }
    },
    persist: true,
});
