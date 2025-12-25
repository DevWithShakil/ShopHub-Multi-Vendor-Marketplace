<script setup>
import { Link } from "@inertiajs/vue3";
import {
    ShoppingBagIcon,
    PhoneIcon,
    EnvelopeIcon,
    MapPinIcon,
    ArrowRightIcon,
    ChatBubbleLeftRightIcon,
    BuildingStorefrontIcon,
    UserPlusIcon,
} from "@heroicons/vue/24/outline";
</script>

<template>
    <footer
        class="bg-black text-white pt-24 pb-10 border-t border-white/10 mt-auto relative z-10 overflow-hidden"
    >
        <div
            class="absolute -top-24 left-1/2 -translate-x-1/2 w-[1000px] h-[500px] bg-indigo-600/10 rounded-full blur-[150px] pointer-events-none"
        ></div>
        <div
            class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-purple-600/5 rounded-full blur-[120px] pointer-events-none"
        ></div>

        <div class="container mx-auto px-6 lg:px-12 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-20">
                <div class="lg:col-span-4 space-y-8">
                    <Link href="/" class="flex items-center gap-3 group w-fit">
                        <div
                            class="w-14 h-14 bg-white text-black rounded-[1.25rem] flex items-center justify-center shadow-[0_0_30px_rgba(255,255,255,0.2)] group-hover:rotate-6 transition-transform duration-500"
                        >
                            <ShoppingBagIcon class="w-7 h-7" />
                        </div>
                        <span class="text-4xl font-black tracking-tighter"
                            >ShopHub<span class="text-indigo-500">.</span></span
                        >
                    </Link>
                    <p class="text-gray-400 text-base leading-relaxed max-w-sm">
                        Redefining the digital marketplace. We bring premium
                        brands and independent vendors together under one
                        futuristic roof.
                    </p>
                    <div class="flex gap-4">
                        <a
                            v-for="social in ['FB', 'IG', 'TW', 'LI']"
                            :key="social"
                            href="#"
                            class="w-12 h-12 rounded-2xl border border-white/10 flex items-center justify-center text-xs font-bold hover:bg-white hover:text-black hover:-translate-y-1 transition-all duration-300"
                        >
                            {{ social }}
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div
                        class="h-full bg-gradient-to-br from-white/5 to-transparent border border-white/10 p-8 rounded-[2.5rem] group relative overflow-hidden"
                    >
                        <div class="relative z-10">
                            <div
                                class="w-12 h-12 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-400 mb-6 group-hover:scale-110 transition-transform"
                            >
                                <BuildingStorefrontIcon class="w-6 h-6" />
                            </div>
                            <h4 class="text-xl font-bold mb-2 text-white">
                                Sell on ShopHub
                            </h4>
                            <p class="text-gray-400 text-sm mb-6">
                                Want to grow your business? Join our vendor
                                network and reach millions.
                            </p>

                            <Link
                                :href="route('vendor.register')"
                                class="inline-flex items-center gap-2 text-white font-bold text-sm group/btn hover:text-indigo-400 transition-colors"
                            >
                                Become a Vendor
                                <ArrowRightIcon
                                    class="w-4 h-4 group-hover/btn:translate-x-2 transition-transform"
                                />
                            </Link>
                        </div>
                        <div
                            class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"
                        ></div>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div
                        class="h-full bg-gradient-to-br from-white/5 to-transparent border border-white/10 p-8 rounded-[2.5rem] group relative overflow-hidden"
                    >
                        <div class="relative z-10">
                            <div
                                class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center text-purple-400 mb-6 group-hover:scale-110 transition-transform"
                            >
                                <ChatBubbleLeftRightIcon class="w-6 h-6" />
                            </div>
                            <h4 class="text-xl font-bold mb-2 text-white">
                                Need Assistance?
                            </h4>
                            <p class="text-gray-400 text-sm mb-6">
                                Our support team is active 24/7 to help you with
                                your inquiries.
                            </p>

                            <Link
                                :href="route('contact.index')"
                                class="inline-flex items-center gap-2 text-white font-bold text-sm group/btn hover:text-purple-400 transition-colors"
                            >
                                Contact Our Team
                                <ArrowRightIcon
                                    class="w-4 h-4 group-hover/btn:translate-x-2 transition-transform"
                                />
                            </Link>
                        </div>
                        <div
                            class="absolute -right-6 -bottom-6 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all"
                        ></div>
                    </div>
                </div>
            </div>

            <div
                class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-16 border-t border-white/5 pt-16"
            >
                <div>
                    <h5
                        class="text-white font-bold text-sm uppercase tracking-widest mb-6"
                    >
                        Shop Features
                    </h5>
                    <ul class="space-y-4 text-sm text-gray-500">
                        <li
                            v-for="l in [
                                'New Arrivals',
                                'Best Sellers',
                                'Discounted Items',
                                'Flash Sales',
                            ]"
                            :key="l"
                        >
                            <Link
                                href="#"
                                class="hover:text-white transition-colors"
                                >{{ l }}</Link
                            >
                        </li>
                    </ul>
                </div>
                <div>
                    <h5
                        class="text-white font-bold text-sm uppercase tracking-widest mb-6"
                    >
                        Customer Care
                    </h5>
                    <ul class="space-y-4 text-sm text-gray-500">
                        <li
                            v-for="l in [
                                'Help Center',
                                'Track Your Order',
                                'Return Policy',
                                'Shipping Info',
                            ]"
                            :key="l"
                        >
                            <Link
                                href="#"
                                class="hover:text-white transition-colors"
                                >{{ l }}</Link
                            >
                        </li>
                    </ul>
                </div>
                <div>
                    <h5
                        class="text-white font-bold text-sm uppercase tracking-widest mb-6"
                    >
                        Legal
                    </h5>
                    <ul class="space-y-4 text-sm text-gray-500">
                        <li
                            v-for="l in [
                                'Privacy Policy',
                                'Terms of Service',
                                'Cookie Policy',
                                'Payment Security',
                            ]"
                            :key="l"
                        >
                            <Link
                                href="#"
                                class="hover:text-white transition-colors"
                                >{{ l }}</Link
                            >
                        </li>
                    </ul>
                </div>
                <div class="space-y-4">
                    <h5
                        class="text-white font-bold text-sm uppercase tracking-widest mb-6"
                    >
                        Office
                    </h5>
                    <p class="text-sm text-gray-500 flex items-start gap-3">
                        <MapPinIcon class="w-5 h-5 text-indigo-500 shrink-0" />
                        Banani, Dhaka-1213, Bangladesh
                    </p>
                    <p class="text-sm text-gray-500 flex items-center gap-3">
                        <PhoneIcon class="w-5 h-5 text-indigo-500 shrink-0" />
                        +880 1568-013150
                    </p>
                </div>
            </div>

            <div
                class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6"
            >
                <p
                    class="text-gray-600 text-[10px] font-black uppercase tracking-[0.3em]"
                >
                    &copy; 2025 ShopHub Marketplace. All Rights Reserved.
                </p>

                <div
                    class="flex items-center gap-4 grayscale opacity-40 hover:opacity-100 hover:grayscale-0 transition-all duration-700"
                >
                    <img
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/2560px-Visa_Inc._logo.svg.png"
                        class="h-4"
                    />
                    <img
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png"
                        class="h-6"
                    />
                    <img
                        src="https://freepnglogo.com/images/all_img/1701670291bKash-App-Logo-PNG.png"
                        class="h-6"
                    />
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped>
/* Custom subtle noise background */
footer {
    background-image: radial-gradient(circle at 50% 0%, #111 0%, #000 100%);
}
</style>
