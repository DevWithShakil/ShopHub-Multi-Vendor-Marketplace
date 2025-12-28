<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/MainLayout.vue";
import ProductCard from "@/Components/ProductCard.vue";
import {
    StarIcon,
    MapPinIcon,
    TicketIcon,
    ClipboardDocumentIcon,
    CheckIcon,
    CalendarDaysIcon,
    CheckBadgeIcon,
    ShoppingBagIcon,
    ArrowRightIcon,
    UserCircleIcon,
    ChatBubbleBottomCenterTextIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    vendor: Object,
    products: Object,
    vouchers: Array,
    reviews: Array,
});

const copiedId = ref(null);

const copyCode = (code, id) => {
    navigator.clipboard.writeText(code);
    copiedId.value = id;
    setTimeout(() => (copiedId.value = null), 2000);
};

const gradients = [
    "bg-gradient-to-br from-violet-600 via-purple-600 to-indigo-600",
    "bg-gradient-to-br from-pink-500 via-rose-500 to-red-500",
    "bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-600",
    "bg-gradient-to-br from-orange-400 via-amber-500 to-yellow-500",
];
</script>

<template>
    <AppLayout>
        <Head :title="vendor.name" />

        <div
            class="bg-[#05070a] min-h-screen pb-20 font-sans text-gray-300 selection:bg-orange-500 selection:text-white"
        >
            <div class="relative h-[320px] md:h-[420px] group">
                <div class="absolute inset-0 overflow-hidden">
                    <img
                        v-if="vendor.banner"
                        :src="vendor.banner"
                        class="w-full h-full object-cover opacity-60 transition-transform duration-1000 group-hover:scale-105"
                    />
                    <div
                        v-else
                        class="w-full h-full bg-gradient-to-r from-slate-900 via-purple-900 to-slate-900 opacity-80"
                    ></div>
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-[#05070a] via-[#05070a]/40 to-transparent"
                    ></div>
                </div>

                <div
                    class="container mx-auto px-4 relative h-full flex flex-col justify-end pb-10 z-10"
                >
                    <div
                        class="flex flex-col md:flex-row items-center md:items-end gap-6 md:gap-8"
                    >
                        <div class="relative">
                            <div
                                class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-[#05070a] bg-white p-1 overflow-hidden shadow-2xl"
                            >
                                <img
                                    :src="vendor.logo"
                                    class="w-full h-full object-cover rounded-full"
                                />
                            </div>
                            <div
                                class="absolute bottom-2 right-2 bg-blue-500 text-white p-1.5 rounded-full border-4 border-[#05070a]"
                                title="Verified Store"
                            >
                                <CheckBadgeIcon class="w-6 h-6" />
                            </div>
                        </div>

                        <div
                            class="text-center md:text-left space-y-3 pb-2 flex-1"
                        >
                            <h1
                                class="text-4xl md:text-5xl font-black text-white tracking-tight drop-shadow-lg"
                            >
                                {{ vendor.name }}
                            </h1>
                            <div
                                class="flex flex-wrap justify-center md:justify-start gap-3 text-sm md:text-base"
                            >
                                <div
                                    class="flex items-center gap-1 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-md border border-white/5 text-yellow-400 font-bold"
                                >
                                    <StarIcon class="w-5 h-5" />
                                    {{ vendor.rating }}
                                    <span class="text-gray-300 font-normal ml-1"
                                        >({{
                                            vendor.review_count
                                        }}
                                        Reviews)</span
                                    >
                                </div>
                                <div
                                    class="flex items-center gap-1 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-md border border-white/5 text-gray-300"
                                >
                                    <ShoppingBagIcon
                                        class="w-5 h-5 text-indigo-400"
                                    />
                                    {{ products?.total || 0 }} Products
                                </div>
                                <div
                                    class="flex items-center gap-1 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-md border border-white/5 text-gray-300"
                                >
                                    <CalendarDaysIcon
                                        class="w-5 h-5 text-emerald-400"
                                    />
                                    Since {{ vendor.joined }}
                                </div>
                            </div>
                            <p
                                v-if="vendor.address"
                                class="text-gray-400 flex items-center justify-center md:justify-start gap-1 text-sm"
                            >
                                <MapPinIcon class="w-4 h-4" />
                                {{ vendor.address }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mx-auto px-4 mt-8 lg:mt-12">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
                    <aside class="lg:col-span-1 space-y-6 lg:sticky lg:top-24">
                        <div
                            class="bg-[#12141c] p-4 lg:p-6 rounded-3xl border border-white/10 shadow-xl relative overflow-hidden"
                        >
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-orange-500/10 rounded-full blur-2xl"
                            ></div>
                            <h3
                                class="text-xl font-bold text-white mb-5 flex items-center gap-2 relative z-10"
                            >
                                <TicketIcon class="w-6 h-6 text-orange-500" />
                                Store Vouchers
                            </h3>

                            <div
                                v-if="vouchers && vouchers.length > 0"
                                class="space-y-6 relative z-10"
                            >
                                <div
                                    v-for="(voucher, index) in vouchers"
                                    :key="voucher.id"
                                    class="group relative w-full filter drop-shadow-lg hover:drop-shadow-2xl transition-all duration-300 hover:-translate-y-1"
                                >
                                    <div
                                        class="relative w-full h-36 flex rounded-xl overflow-hidden bg-white"
                                    >
                                        <div
                                            class="relative w-[35%] h-full flex flex-col items-center justify-center p-2 text-white z-10"
                                            :class="
                                                gradients[
                                                    index % gradients.length
                                                ]
                                            "
                                        >
                                            <div
                                                class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"
                                            ></div>
                                            <div
                                                class="relative z-10 flex flex-col items-center text-center"
                                            >
                                                <h3
                                                    class="text-2xl lg:text-3xl font-black leading-none drop-shadow-md"
                                                >
                                                    {{ parseInt(voucher.value)
                                                    }}<span
                                                        class="text-sm align-top"
                                                        >{{
                                                            voucher.type ===
                                                            "percentage"
                                                                ? "%"
                                                                : "৳"
                                                        }}</span
                                                    >
                                                </h3>
                                                <span
                                                    class="text-[10px] font-bold opacity-90 tracking-widest mt-0.5"
                                                    >OFF</span
                                                >
                                            </div>
                                            <div
                                                class="absolute -top-3 left-1/2 -translate-x-1/2 w-3 h-3 rounded-full bg-[#12141c] blur-[0.5px]"
                                            ></div>
                                            <div
                                                class="absolute -bottom-3 left-1/2 -translate-x-1/2 w-3 h-3 rounded-full bg-[#12141c] blur-[0.5px]"
                                            ></div>
                                        </div>
                                        <div
                                            class="relative w-[65%] h-full bg-white flex flex-col justify-between p-3 pl-5"
                                        >
                                            <div
                                                class="absolute top-2 bottom-2 left-0 w-[2px] border-l-2 border-dashed border-gray-300 z-20"
                                            ></div>
                                            <div
                                                class="absolute -top-3 -left-3 w-6 h-6 rounded-full bg-[#12141c] z-30"
                                            ></div>
                                            <div
                                                class="absolute -bottom-3 -left-3 w-6 h-6 rounded-full bg-[#12141c] z-30"
                                            ></div>
                                            <div>
                                                <div
                                                    class="flex justify-between items-start mb-1"
                                                >
                                                    <span
                                                        class="bg-red-50 text-red-600 text-[9px] font-bold px-1.5 py-0.5 rounded border border-red-100 uppercase"
                                                        >Active</span
                                                    >
                                                </div>
                                                <p
                                                    class="text-[10px] text-gray-500 font-medium"
                                                >
                                                    Exp:
                                                    <span
                                                        class="text-gray-800 font-bold"
                                                        >{{
                                                            new Date(
                                                                voucher.end_date
                                                            ).toLocaleDateString()
                                                        }}</span
                                                    >
                                                </p>
                                            </div>
                                            <div class="mt-auto z-20">
                                                <button
                                                    @click="
                                                        copyCode(
                                                            voucher.code,
                                                            voucher.id
                                                        )
                                                    "
                                                    class="w-full group/btn relative flex items-center justify-between bg-gray-50 hover:bg-gray-900 border border-gray-200 hover:border-gray-900 text-gray-900 hover:text-white rounded-lg px-2 py-1.5 transition-all duration-300"
                                                >
                                                    <div
                                                        class="flex flex-col items-start overflow-hidden"
                                                    >
                                                        <span
                                                            class="text-[8px] font-bold uppercase opacity-50 tracking-wider"
                                                            >Code</span
                                                        >
                                                        <span
                                                            class="font-mono font-bold text-sm tracking-wider truncate"
                                                            >{{
                                                                voucher.code
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div
                                                        class="w-6 h-6 flex-shrink-0 flex items-center justify-center rounded bg-white text-gray-900 shadow-sm group-hover/btn:bg-white/20 group-hover/btn:text-white"
                                                    >
                                                        <CheckIcon
                                                            v-if="
                                                                copiedId ===
                                                                voucher.id
                                                            "
                                                            class="w-3 h-3"
                                                        />
                                                        <ClipboardDocumentIcon
                                                            v-else
                                                            class="w-3 h-3"
                                                        />
                                                    </div>
                                                    <span
                                                        v-if="
                                                            copiedId ===
                                                            voucher.id
                                                        "
                                                        class="absolute inset-0 flex items-center justify-center bg-green-500 text-white font-bold rounded-lg text-[10px] tracking-widest z-30 animate-fade-in"
                                                        >COPIED!</span
                                                    >
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-8">
                                <div
                                    class="bg-white/5 p-4 rounded-full inline-block mb-3"
                                >
                                    <TicketIcon class="w-8 h-8 text-gray-600" />
                                </div>
                                <p class="text-gray-500 text-sm">
                                    No vouchers available at the moment.
                                </p>
                            </div>
                        </div>
                    </aside>

                    <main class="lg:col-span-3">
                        <div class="flex items-center justify-between mb-6">
                            <h2
                                class="text-2xl font-bold text-white flex items-center gap-2"
                            >
                                <ShoppingBagIcon
                                    class="w-6 h-6 text-indigo-500"
                                />
                                All Products
                            </h2>
                            <Link
                                :href="
                                    route('shop.index', {
                                        vendor_id: vendor.id,
                                    })
                                "
                                class="flex items-center gap-2 text-sm text-orange-500 font-bold hover:text-white transition-colors bg-white/5 px-4 py-2 rounded-lg border border-white/10 hover:bg-orange-500"
                            >
                                Explore in Shop
                                <ArrowRightIcon class="w-4 h-4" />
                            </Link>
                        </div>

                        <div
                            v-if="
                                products &&
                                products.data &&
                                products.data.length > 0
                            "
                            class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6"
                        >
                            <ProductCard
                                v-for="product in products.data"
                                :key="product.id"
                                :product="product"
                            />
                        </div>

                        <div
                            v-else
                            class="flex flex-col items-center justify-center py-32 bg-[#12141c] rounded-3xl border border-white/5 text-center"
                        >
                            <ShoppingBagIcon
                                class="w-16 h-16 text-gray-600 mb-4 opacity-50"
                            />
                            <h3 class="text-xl font-bold text-white mb-2">
                                No Products Found
                            </h3>
                            <p class="text-gray-500">
                                This vendor hasn't added any products yet.
                            </p>
                        </div>

                        <div
                            v-if="
                                products &&
                                products.links &&
                                products.links.length > 3
                            "
                            class="mt-10 flex justify-center"
                        >
                            <div
                                class="flex flex-wrap gap-2 bg-[#12141c] p-2 rounded-xl border border-white/5"
                            >
                                <Link
                                    v-for="(link, k) in products.links"
                                    :key="k"
                                    :href="link.url || '#'"
                                    v-html="link.label"
                                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all duration-300"
                                    :class="
                                        link.active
                                            ? 'bg-gradient-to-r from-orange-600 to-red-600 text-white shadow-lg'
                                            : 'text-gray-400 hover:text-white hover:bg-white/5'
                                    "
                                />
                            </div>
                        </div>

                        <div class="mt-20 pt-10 border-t border-white/10">
                            <h2
                                class="text-2xl font-bold text-white mb-8 flex items-center gap-2"
                            >
                                <ChatBubbleBottomCenterTextIcon
                                    class="w-6 h-6 text-yellow-500"
                                />
                                Store Reviews
                                <span class="text-gray-500 text-lg font-normal"
                                    >({{ vendor.review_count }})</span
                                >
                            </h2>

                            <div
                                v-if="reviews && reviews.length > 0"
                                class="grid grid-cols-1 md:grid-cols-2 gap-6"
                            >
                                <div
                                    v-for="review in reviews"
                                    :key="review.id"
                                    class="bg-[#12141c] p-5 rounded-2xl border border-white/5 hover:border-white/10 transition-colors"
                                >
                                    <div
                                        class="flex items-start justify-between mb-4"
                                    >
                                        <div class="flex items-center gap-3">
                                            <img
                                                v-if="review.avatar"
                                                :src="review.avatar"
                                                class="w-10 h-10 rounded-full border border-white/10"
                                            />
                                            <div
                                                v-else
                                                class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center"
                                            >
                                                <UserCircleIcon
                                                    class="w-6 h-6 text-gray-400"
                                                />
                                            </div>
                                            <div>
                                                <h4
                                                    class="font-bold text-white text-sm"
                                                >
                                                    {{ review.user }}
                                                </h4>
                                                <span
                                                    class="text-xs text-gray-500"
                                                    >{{ review.date }}</span
                                                >
                                            </div>
                                        </div>
                                        <div class="flex text-yellow-400">
                                            <StarIcon
                                                v-for="n in 5"
                                                :key="n"
                                                class="w-4 h-4"
                                                :class="
                                                    n <= review.rating
                                                        ? 'text-yellow-400'
                                                        : 'text-gray-700'
                                                "
                                            />
                                        </div>
                                    </div>
                                    <p
                                        class="text-gray-300 text-sm leading-relaxed mb-4"
                                    >
                                        "{{ review.comment }}"
                                    </p>

                                    <div
                                        class="flex items-center gap-3 bg-[#05070a] p-2 rounded-lg border border-white/5"
                                    >
                                        <img
                                            v-if="review.product_image"
                                            :src="review.product_image"
                                            class="w-8 h-8 rounded object-cover"
                                        />
                                        <span
                                            class="text-xs text-gray-400 truncate w-40"
                                            >{{ review.product_name }}</span
                                        >
                                    </div>
                                </div>
                            </div>

                            <div
                                v-else
                                class="text-center py-12 bg-[#12141c] rounded-2xl border border-white/5"
                            >
                                <StarIcon
                                    class="w-12 h-12 text-gray-700 mx-auto mb-3"
                                />
                                <p class="text-gray-400">
                                    No reviews yet for this store.
                                </p>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
@keyframes fade-in {
    0% {
        opacity: 0;
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}
.animate-fade-in {
    animation: fade-in 0.2s ease-out forwards;
}
</style>
