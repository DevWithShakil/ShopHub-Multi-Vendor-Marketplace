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
            const cartId = variant ? `${product.id}-${variant.id}` : `${product.id}`;
            const existingItem = this.items.find(item => item.cartId === cartId);

            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                this.items.push({
                    cartId: cartId,
                    id: product.id,
                    name: product.name,
                    price: variant ? variant.price : product.base_price,
                    image: product.thumb_image,
                    variant: variant,
                    quantity: quantity
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
