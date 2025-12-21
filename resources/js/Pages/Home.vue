<script setup>
import { Head, Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { ShoppingCartIcon, StarIcon } from "@heroicons/vue/24/solid";

defineProps({
    products: Array,
});
</script>

<template>
    <Head title="Home" />

    <MainLayout>
        <div
            class="relative bg-gradient-to-r from-indigo-900 to-purple-800 h-[400px] flex items-center overflow-hidden"
        >
            <div
                class="absolute inset-0 bg-[url('https://cdn.pixabay.com/photo/2017/03/13/17/26/ecommerce-2140603_1280.jpg')] opacity-20 bg-cover bg-center"
            ></div>
            <div
                class="container mx-auto px-6 relative z-10 text-center md:text-left"
            >
                <span
                    class="text-indigo-300 font-bold tracking-widest uppercase text-sm mb-2 block animate-pulse"
                    >New Season</span
                >
                <h1
                    class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight"
                >
                    Upgrade Your <br />
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500"
                        >Digital Life</span
                    >
                </h1>
                <button
                    class="bg-white text-indigo-900 px-8 py-3 rounded-full font-bold hover:bg-indigo-50 hover:scale-105 transition transform shadow-xl"
                >
                    Shop Now
                </button>
            </div>
        </div>

        <div class="container mx-auto px-4 py-16">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">
                        Trending Products
                    </h2>
                    <p class="text-gray-500 mt-1">
                        Handpicked items just for you
                    </p>
                </div>
                <Link
                    href="#"
                    class="text-indigo-600 font-semibold hover:underline"
                    >View All &rarr;</Link
                >
            </div>

            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8"
            >
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col h-full"
                >
                    <div class="relative h-60 bg-gray-50 overflow-hidden">
                        <img
                            v-if="product.thumb_image"
                            :src="'/storage/' + product.thumb_image"
                            alt="Product"
                            class="w-full h-full object-contain p-4 group-hover:scale-110 transition-transform duration-500"
                        />
                        <div
                            v-else
                            class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-100"
                        >
                            No Image
                        </div>

                        <span
                            class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-md"
                        >
                            Sale
                        </span>

                        <button
                            class="absolute bottom-3 right-3 bg-white text-gray-900 p-3 rounded-full shadow-lg translate-y-12 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 hover:bg-indigo-600 hover:text-white"
                        >
                            <ShoppingCartIcon class="w-5 h-5" />
                        </button>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <p
                            class="text-xs text-indigo-500 font-semibold mb-1 uppercase tracking-wide"
                        >
                            {{ product.category?.name || "General" }}
                        </p>
                        <h3
                            class="text-lg font-bold text-gray-800 mb-2 truncate group-hover:text-indigo-600 transition-colors"
                        >
                            {{ product.name }}
                        </h3>

                        <div class="flex items-center mb-3">
                            <StarIcon
                                v-for="i in 5"
                                :key="i"
                                class="w-4 h-4 text-yellow-400"
                            />
                            <span class="text-xs text-gray-400 ml-1">(45)</span>
                        </div>

                        <div
                            class="mt-auto flex justify-between items-center border-t pt-3 border-dashed border-gray-200"
                        >
                            <div>
                                <span
                                    class="text-gray-400 line-through text-sm block"
                                    v-if="product.discount_price"
                                >
                                    ৳{{ product.base_price * 1.2 }}
                                </span>
                                <span
                                    class="text-xl font-extrabold text-gray-900"
                                >
                                    ৳{{ product.base_price }}
                                </span>
                            </div>
                            <button
                                class="text-sm font-medium text-indigo-600 hover:bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors"
                            >
                                Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
