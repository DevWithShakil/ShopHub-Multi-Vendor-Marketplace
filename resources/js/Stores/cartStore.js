import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: [],
    }),

    getters: {
        itemCount: (state) => state.items.reduce((total, item) => total + item.quantity, 0),
        totalPrice: (state) => state.items.reduce((total, item) => total + (item.price * item.quantity), 0),
    },

    actions: {
        addToCart(product, quantity = 1, variant = null) {

            // console.log("Adding Product:", product);
            // console.log("Vendor ID is:", product.vendor_id);
            const cartId = variant ? `${product.id}-${variant.id}` : `${product.id}`;

            const existingItem = this.items.find(item => item.cartId === cartId);

            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                this.items.push({
                    cartId: cartId,
                    id: product.id,
                    name: product.name,
                    slug: product.slug,
                    category: product.category?.name || 'Product',
                    price: variant ? variant.price : product.base_price,
                    image: product.thumb_image,
                    variant: variant,
                    quantity: quantity,
                    vendor_id: product.vendor_id,
                });
            }
        },

        removeFromCart(cartId) {
            this.items = this.items.filter(item => item.cartId !== cartId);
        },

        clearCart() {
            this.items = [];
        }
    },

    persist: true
});
