<script setup>
import { Head, Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {
    ShoppingCartIcon,
    StarIcon,
    ArrowRightIcon,
} from "@heroicons/vue/24/solid";

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
                class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=2070&auto=format&fit=crop')] opacity-20 bg-cover bg-center"
            ></div>

            <div
                class="container mx-auto px-6 relative z-10 text-center md:text-left"
            >
                <span
                    class="text-indigo-300 font-bold tracking-widest uppercase text-sm mb-2 block animate-pulse"
                >
                    New Season Arrival
                </span>
                <h1
                    class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight drop-shadow-lg"
                >
                    Upgrade Your <br />
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500"
                    >
                        Lifestyle Today
                    </span>
                </h1>
                <div class="flex gap-4 justify-center md:justify-start">
                    <button
                        class="bg-white text-indigo-900 px-8 py-3 rounded-full font-bold hover:bg-indigo-50 hover:scale-105 transition transform shadow-xl"
                    >
                        Shop Now
                    </button>
                    <button
                        class="border-2 border-white text-white px-8 py-3 rounded-full font-bold hover:bg-white/10 transition transform"
                    >
                        View Collections
                    </button>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-16 bg-gray-50/50">
            <div
                class="flex justify-between items-end mb-10 border-b border-gray-200 pb-4"
            >
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">
                        Trending Products
                    </h2>
                    <p class="text-gray-500 mt-1">
                        Handpicked premium items just for you
                    </p>
                </div>
                <Link
                    href="#"
                    class="flex items-center gap-1 text-indigo-600 font-semibold hover:text-indigo-800 transition"
                >
                    View All <ArrowRightIcon class="w-4 h-4" />
                </Link>
            </div>

            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8"
            >
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="group bg-white rounded-2xl shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col h-full relative"
                >
                    <Link
                        :href="route('product.details', product.slug)"
                        class="block relative h-64 bg-gray-100 overflow-hidden cursor-pointer"
                    >
                        <img
                            v-if="product.thumb_image"
                            :src="'/storage/' + product.thumb_image"
                            :alt="product.name"
                            class="w-full h-full object-contain p-6 group-hover:scale-110 transition-transform duration-500 mix-blend-multiply"
                        />
                        <div
                            v-else
                            class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-100"
                        >
                            No Image
                        </div>

                        <span
                            v-if="product.discount_price"
                            class="absolute top-3 left-3 bg-rose-500 text-white text-[10px] uppercase font-bold px-2 py-1 rounded-sm shadow-sm"
                        >
                            Sale
                        </span>

                        <div
                            class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                        ></div>
                    </Link>

                    <button
                        class="absolute top-3 right-3 bg-white text-gray-800 p-2.5 rounded-full shadow-md translate-x-12 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300 hover:bg-indigo-600 hover:text-white z-10"
                    >
                        <ShoppingCartIcon class="w-5 h-5" />
                    </button>

                    <div class="p-5 flex-1 flex flex-col">
                        <p
                            class="text-xs text-indigo-500 font-semibold mb-1 uppercase tracking-wide"
                        >
                            {{ product.category?.name || "General" }}
                        </p>

                        <Link :href="route('product.details', product.slug)">
                            <h3
                                class="text-lg font-bold text-gray-800 mb-2 truncate group-hover:text-indigo-600 transition-colors"
                            >
                                {{ product.name }}
                            </h3>
                        </Link>

                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <StarIcon
                                    v-for="i in 5"
                                    :key="i"
                                    class="w-4 h-4"
                                />
                            </div>
                            <span class="text-xs text-gray-400 ml-2 font-medium"
                                >(4.8)</span
                            >
                        </div>

                        <div
                            class="mt-auto flex justify-between items-end border-t border-dashed border-gray-100 pt-4"
                        >
                            <div class="flex flex-col">
                                <span
                                    class="text-gray-400 line-through text-xs font-medium"
                                    v-if="product.discount_price"
                                >
                                    ৳{{ parseInt(product.base_price) + 500 }}
                                </span>
                                <span
                                    class="text-xl font-extrabold text-gray-900 leading-none"
                                >
                                    ৳{{ product.base_price }}
                                </span>
                            </div>
                            <Link
                                :href="route('product.details', product.slug)"
                                class="text-sm font-semibold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-600 hover:text-white transition-colors"
                            >
                                Buy Now
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
