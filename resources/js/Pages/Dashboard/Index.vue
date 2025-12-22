<script setup>
import { Head, Link } from "@inertiajs/vue3";
import UserDashboardLayout from "@/Layouts/UserDashboardLayout.vue";
import {
    ShoppingBagIcon,
    ClockIcon,
    CheckBadgeIcon,
    BanknotesIcon,
    ArrowRightIcon,
    HeartIcon,
    HomeIcon,
} from "@heroicons/vue/24/outline";

defineProps({
    stats: Object,
    recent_orders: Array,
});
</script>

<template>
    <Head title="Dashboard" />

    <UserDashboardLayout>
        <div
            class="min-h-screen bg-[#0B0F19] p-4 lg:p-8 font-sans text-gray-200 relative overflow-hidden"
        >
            <div
                class="absolute top-0 left-0 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[120px] pointer-events-none"
            ></div>

            <div class="relative z-10 max-w-7xl mx-auto">
                <div
                    class="mb-10 animate-fade-in-up flex justify-between items-end"
                >
                    <div>
                        <h1
                            class="text-3xl lg:text-4xl font-black text-white tracking-tight mb-2"
                        >
                            {{ __("Welcome Back") }},
                        </h1>
                        <p class="text-gray-400">
                            {{
                                __(
                                    "Here is what’s happening with your account today."
                                )
                            }}
                        </p>
                    </div>
                    <Link
                        href="/"
                        class="hidden md:flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 rounded-xl border border-white/10 text-sm font-bold text-gray-300 transition"
                    >
                        <HomeIcon class="w-4 h-4" /> Go to Home
                    </Link>
                </div>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-12"
                >
                    <div
                        class="bg-white/5 backdrop-blur-md p-6 rounded-3xl border border-white/10 hover:border-indigo-500/50 transition-all duration-300 group hover:-translate-y-1"
                    >
                        <div class="flex justify-between items-start">
                            <div>
                                <p
                                    class="text-sm font-bold text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __("Total Orders") }}
                                </p>
                                <h3
                                    class="text-3xl font-black text-white mt-2 group-hover:text-indigo-400 transition-colors"
                                >
                                    {{ stats.total_orders }}
                                </h3>
                            </div>
                            <div
                                class="p-3 bg-gradient-to-br from-indigo-600 to-purple-600 text-white rounded-2xl shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform"
                            >
                                <ShoppingBagIcon class="w-6 h-6" />
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white/5 backdrop-blur-md p-6 rounded-3xl border border-white/10 hover:border-yellow-500/50 transition-all duration-300 group hover:-translate-y-1"
                    >
                        <div class="flex justify-between items-start">
                            <div>
                                <p
                                    class="text-sm font-bold text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __("Pending") }}
                                </p>
                                <h3
                                    class="text-3xl font-black text-white mt-2 group-hover:text-yellow-400 transition-colors"
                                >
                                    {{ stats.pending_orders }}
                                </h3>
                            </div>
                            <div
                                class="p-3 bg-gradient-to-br from-yellow-500 to-orange-500 text-white rounded-2xl shadow-lg shadow-yellow-500/30 group-hover:scale-110 transition-transform"
                            >
                                <ClockIcon class="w-6 h-6" />
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white/5 backdrop-blur-md p-6 rounded-3xl border border-white/10 hover:border-green-500/50 transition-all duration-300 group hover:-translate-y-1"
                    >
                        <div class="flex justify-between items-start">
                            <div>
                                <p
                                    class="text-sm font-bold text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __("Completed") }}
                                </p>
                                <h3
                                    class="text-3xl font-black text-white mt-2 group-hover:text-green-400 transition-colors"
                                >
                                    {{ stats.completed_orders }}
                                </h3>
                            </div>
                            <div
                                class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-2xl shadow-lg shadow-green-500/30 group-hover:scale-110 transition-transform"
                            >
                                <CheckBadgeIcon class="w-6 h-6" />
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white/5 backdrop-blur-md p-6 rounded-3xl border border-white/10 hover:border-rose-500/50 transition-all duration-300 group hover:-translate-y-1"
                    >
                        <div class="flex justify-between items-start">
                            <div>
                                <p
                                    class="text-sm font-bold text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __("Wishlist") }}
                                </p>
                                <h3
                                    class="text-3xl font-black text-white mt-2 group-hover:text-rose-400 transition-colors"
                                >
                                    {{ stats.wishlist_count }}
                                </h3>
                            </div>
                            <div
                                class="p-3 bg-gradient-to-br from-rose-500 to-pink-600 text-white rounded-2xl shadow-lg shadow-rose-500/30 group-hover:scale-110 transition-transform"
                            >
                                <HeartIcon class="w-6 h-6" />
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white/5 backdrop-blur-md p-6 rounded-3xl border border-white/10 hover:border-purple-500/50 transition-all duration-300 group hover:-translate-y-1 sm:col-span-2 lg:col-span-1"
                    >
                        <div class="flex justify-between items-start">
                            <div>
                                <p
                                    class="text-sm font-bold text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __("Total Spend") }}
                                </p>
                                <h3
                                    class="text-3xl font-black text-white mt-2 group-hover:text-purple-400 transition-colors truncate"
                                >
                                    ৳{{ stats.total_spend }}
                                </h3>
                            </div>
                            <div
                                class="p-3 bg-gradient-to-br from-purple-600 to-indigo-600 text-white rounded-2xl shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform"
                            >
                                <BanknotesIcon class="w-6 h-6" />
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white/5 backdrop-blur-md rounded-3xl border border-white/10 overflow-hidden shadow-2xl"
                >
                    <div
                        class="p-6 border-b border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                    >
                        <h2
                            class="text-xl font-bold text-white flex items-center gap-2"
                        >
                            <ShoppingBagIcon class="w-5 h-5 text-indigo-400" />
                            {{ __("Recent Orders") }}
                        </h2>
                        <Link
                            href="/dashboard/orders"
                            class="inline-flex items-center gap-2 text-sm font-bold text-indigo-400 hover:text-white transition px-4 py-2 rounded-xl hover:bg-white/5"
                        >
                            {{ __("View All Orders") }}
                            <ArrowRightIcon class="w-4 h-4" />
                        </Link>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-white/5 bg-white/5">
                                    <th
                                        class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider"
                                    >
                                        Order ID
                                    </th>
                                    <th
                                        class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider"
                                    >
                                        Date
                                    </th>
                                    <th
                                        class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider text-right"
                                    >
                                        Amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr
                                    v-for="order in recent_orders"
                                    :key="order.id"
                                    class="group hover:bg-white/5 transition-colors duration-200"
                                >
                                    <td class="p-5">
                                        <span
                                            class="font-mono font-bold text-indigo-400 bg-indigo-500/10 px-3 py-1.5 rounded-lg border border-indigo-500/20"
                                        >
                                            #{{ order.invoice_no }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-5 text-sm text-gray-300 font-medium"
                                    >
                                        {{
                                            new Date(
                                                order.created_at
                                            ).toLocaleDateString()
                                        }}
                                    </td>
                                    <td class="p-5">
                                        <span
                                            class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wide border shadow-sm flex w-fit items-center gap-1.5"
                                            :class="{
                                                'bg-yellow-500/10 text-yellow-400 border-yellow-500/20':
                                                    order.status === 'pending',
                                                'bg-blue-500/10 text-blue-400 border-blue-500/20':
                                                    order.status ===
                                                    'processing',
                                                'bg-green-500/10 text-green-400 border-green-500/20':
                                                    order.status ===
                                                    'completed',
                                                'bg-rose-500/10 text-rose-400 border-rose-500/20':
                                                    order.status ===
                                                    'cancelled',
                                            }"
                                        >
                                            <span
                                                class="w-1.5 h-1.5 rounded-full"
                                                :class="{
                                                    'bg-yellow-400':
                                                        order.status ===
                                                        'pending',
                                                    'bg-blue-400':
                                                        order.status ===
                                                        'processing',
                                                    'bg-green-400':
                                                        order.status ===
                                                        'completed',
                                                    'bg-rose-400':
                                                        order.status ===
                                                        'cancelled',
                                                }"
                                            ></span>
                                            {{ order.status }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-5 text-right font-bold text-white group-hover:text-indigo-400 transition-colors text-lg"
                                    >
                                        ৳{{ order.total_amount }}
                                    </td>
                                </tr>

                                <tr v-if="recent_orders.length === 0">
                                    <td colspan="4" class="p-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mb-4"
                                            >
                                                <ShoppingBagIcon
                                                    class="w-8 h-8 text-gray-500"
                                                />
                                            </div>
                                            <p
                                                class="text-gray-400 text-lg mb-4"
                                            >
                                                {{ __("No orders found.") }}
                                            </p>
                                            <Link
                                                href="/"
                                                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-indigo-500/30"
                                            >
                                                {{ __("Start Shopping") }}
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </UserDashboardLayout>
</template>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out forwards;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
