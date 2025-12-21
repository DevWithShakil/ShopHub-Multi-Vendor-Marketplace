<script setup>
import { ref, computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { StarIcon, ShieldCheckIcon, TruckIcon } from "@heroicons/vue/24/solid";
import { HeartIcon, ShareIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    product: Object,
});

const activeImage = ref(props.product.thumb_image);

const selectedVariant = ref(null);

const currentPrice = computed(() => {
    if (selectedVariant.value) {
        return selectedVariant.value.price;
    }
    return props.product.base_price;
});

const uniqueAttributes = computed(() => {
    if (!props.product.has_variants) return [];
    return props.product.variants;
});
</script>

<template>
    <Head :title="product.name" />

    <MainLayout>
        <div class="container mx-auto px-4 py-10">
            <nav class="text-sm text-gray-500 mb-6 flex gap-2">
                <Link href="/" class="hover:text-indigo-600">Home</Link>
                <span>/</span>
                <span class="text-gray-800 font-medium">{{
                    product.category?.name
                }}</span>
            </nav>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-4">
                    <div
                        class="bg-white border rounded-2xl p-4 h-[400px] flex items-center justify-center overflow-hidden relative"
                    >
                        <img
                            :src="'/storage/' + activeImage"
                            class="h-full object-contain hover:scale-110 transition duration-500"
                            alt="Product Image"
                        />
                        <span
                            v-if="product.discount_price"
                            class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full"
                            >Sale</span
                        >
                    </div>

                    <div class="flex gap-4 overflow-x-auto pb-2">
                        <button
                            @click="activeImage = product.thumb_image"
                            class="w-20 h-20 border-2 rounded-lg p-1 bg-white flex-shrink-0"
                            :class="
                                activeImage === product.thumb_image
                                    ? 'border-indigo-600'
                                    : 'border-gray-200'
                            "
                        >
                            <img
                                :src="'/storage/' + product.thumb_image"
                                class="w-full h-full object-contain"
                            />
                        </button>

                        <button
                            v-for="(img, index) in product.gallery_images"
                            :key="index"
                            @click="activeImage = img"
                            class="w-20 h-20 border-2 rounded-lg p-1 bg-white flex-shrink-0 hover:border-indigo-400"
                            :class="
                                activeImage === img
                                    ? 'border-indigo-600'
                                    : 'border-gray-200'
                            "
                        >
                            <img
                                :src="'/storage/' + img"
                                class="w-full h-full object-contain"
                            />
                        </button>
                    </div>
                </div>

                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        {{ product.name }}
                    </h1>

                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex text-yellow-400 text-sm">
                            <StarIcon v-for="i in 5" :key="i" class="w-5 h-5" />
                        </div>
                        <span class="text-gray-500 text-sm">(120 Reviews)</span>
                        <span
                            class="text-indigo-600 text-sm font-semibold border-l pl-4"
                            >In Stock</span
                        >
                    </div>

                    <div
                        class="text-3xl font-extrabold text-gray-900 mb-6 flex items-end gap-3"
                    >
                        ৳{{ currentPrice }}
                        <span
                            v-if="product.discount_price"
                            class="text-lg text-gray-400 line-through font-normal"
                        >
                            ৳{{ parseInt(product.base_price) + 200 }}
                        </span>
                    </div>

                    <div v-if="product.has_variants" class="mb-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">
                            Select Variant:
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            <button
                                v-for="variant in product.variants"
                                :key="variant.id"
                                @click="selectedVariant = variant"
                                class="px-4 py-2 border rounded-md text-sm font-medium transition-all"
                                :class="
                                    selectedVariant?.id === variant.id
                                        ? 'border-indigo-600 bg-indigo-50 text-indigo-700 ring-1 ring-indigo-500'
                                        : 'border-gray-300 text-gray-700 hover:border-gray-400'
                                "
                            >
                                <span
                                    v-for="(val, key) in variant.attributes"
                                    :key="key"
                                >
                                    {{ val }}
                                </span>
                                <span class="text-gray-400 ml-1 text-xs">
                                    - ৳{{ variant.price }}</span
                                >
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-4 mb-8">
                        <div
                            class="flex items-center border border-gray-300 rounded-lg"
                        >
                            <button class="px-4 py-2 hover:bg-gray-100">
                                -
                            </button>
                            <span class="px-4 font-medium">1</span>
                            <button class="px-4 py-2 hover:bg-gray-100">
                                +
                            </button>
                        </div>
                        <button
                            class="flex-1 bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition"
                        >
                            Add to Cart
                        </button>
                        <button
                            class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-red-500 transition"
                        >
                            <HeartIcon class="w-6 h-6" />
                        </button>
                    </div>

                    <div
                        class="grid grid-cols-2 gap-4 text-sm text-gray-600 border-t pt-6"
                    >
                        <div class="flex items-center gap-2">
                            <TruckIcon class="w-5 h-5 text-indigo-600" />
                            <span>Free Delivery over ৳5000</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <ShieldCheckIcon class="w-5 h-5 text-green-600" />
                            <span>1 Year Official Warranty</span>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-bold mb-3">Description</h3>
                        <div
                            class="prose text-gray-600 text-sm"
                            v-html="product.description"
                        ></div>
                    </div>

                    <div
                        class="mt-8 bg-gray-50 p-4 rounded-xl flex items-center justify-between border"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-xl"
                            >
                                {{ product.vendor?.shop_name.charAt(0) }}
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Sold by</p>
                                <p class="font-bold text-gray-900">
                                    {{ product.vendor?.shop_name }}
                                </p>
                            </div>
                        </div>
                        <button
                            class="text-sm text-indigo-600 font-semibold hover:underline"
                        >
                            Visit Store
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
