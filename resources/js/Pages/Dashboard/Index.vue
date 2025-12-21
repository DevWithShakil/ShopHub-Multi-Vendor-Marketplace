<script setup>
import { Head, Link } from "@inertiajs/vue3";
import UserDashboardLayout from "@/Layouts/UserDashboardLayout.vue";
import {
    ShoppingBagIcon,
    ClockIcon,
    CheckBadgeIcon,
    BanknotesIcon,
    ArrowRightIcon,
    HeartIcon, // ✅ Added Heart Icon
} from "@heroicons/vue/24/outline";

defineProps({
    stats: Object,
    recent_orders: Array,
});
</script>

<template>
    <Head title="Dashboard" />

    <UserDashboardLayout>
        <div class="mb-8 animate-fade-in-up">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">
                {{ __("Welcome Back") }},
            </h1>
            <p class="text-gray-500 mt-1">
                {{ __("Here is what’s happening with your account today.") }}
            </p>
        </div>

        <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-8"
        >
            <div
                class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 group"
            >
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            {{ __("Total Orders") }}
                        </p>
                        <h3
                            class="text-2xl font-bold text-gray-800 mt-1 group-hover:text-indigo-600 transition-colors"
                        >
                            {{ stats.total_orders }}
                        </h3>
                    </div>
                    <div
                        class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:scale-110 transition-transform duration-300"
                    >
                        <ShoppingBagIcon class="w-6 h-6" />
                    </div>
                </div>
            </div>

            <div
                class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 group"
            >
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            {{ __("Pending") }}
                        </p>
                        <h3
                            class="text-2xl font-bold text-gray-800 mt-1 group-hover:text-yellow-600 transition-colors"
                        >
                            {{ stats.pending_orders }}
                        </h3>
                    </div>
                    <div
                        class="p-3 bg-yellow-50 text-yellow-600 rounded-xl group-hover:scale-110 transition-transform duration-300"
                    >
                        <ClockIcon class="w-6 h-6" />
                    </div>
                </div>
            </div>

            <div
                class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 group"
            >
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            {{ __("Completed") }}
                        </p>
                        <h3
                            class="text-2xl font-bold text-gray-800 mt-1 group-hover:text-green-600 transition-colors"
                        >
                            {{ stats.completed_orders }}
                        </h3>
                    </div>
                    <div
                        class="p-3 bg-green-50 text-green-600 rounded-xl group-hover:scale-110 transition-transform duration-300"
                    >
                        <CheckBadgeIcon class="w-6 h-6" />
                    </div>
                </div>
            </div>

            <div
                class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 group"
            >
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            {{ __("Wishlist") }}
                        </p>
                        <h3
                            class="text-2xl font-bold text-gray-800 mt-1 group-hover:text-red-500 transition-colors"
                        >
                            {{ stats.wishlist_count }}
                        </h3>
                    </div>
                    <div
                        class="p-3 bg-red-50 text-red-500 rounded-xl group-hover:scale-110 transition-transform duration-300"
                    >
                        <HeartIcon class="w-6 h-6" />
                    </div>
                </div>
            </div>

            <div
                class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 group sm:col-span-2 lg:col-span-1"
            >
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            {{ __("Total Spend") }}
                        </p>
                        <h3
                            class="text-2xl font-bold text-gray-800 mt-1 group-hover:text-purple-600 transition-colors"
                        >
                            ৳{{ stats.total_spend }}
                        </h3>
                    </div>
                    <div
                        class="p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:scale-110 transition-transform duration-300"
                    >
                        <BanknotesIcon class="w-6 h-6" />
                    </div>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
        >
            <div
                class="p-6 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
            >
                <h2 class="text-lg font-bold text-gray-800">
                    {{ __("Recent Orders") }}
                </h2>
                <Link
                    href="/dashboard/orders"
                    class="inline-flex items-center gap-2 text-sm text-indigo-600 font-semibold hover:text-indigo-800 transition"
                >
                    {{ __("View All Orders") }}
                    <ArrowRightIcon class="w-4 h-4" />
                </Link>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th
                                class="p-5 text-xs font-semibold text-gray-500 uppercase tracking-wider"
                            >
                                Order ID
                            </th>
                            <th
                                class="p-5 text-xs font-semibold text-gray-500 uppercase tracking-wider"
                            >
                                Date
                            </th>
                            <th
                                class="p-5 text-xs font-semibold text-gray-500 uppercase tracking-wider"
                            >
                                Status
                            </th>
                            <th
                                class="p-5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right"
                            >
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr
                            v-for="order in recent_orders"
                            :key="order.id"
                            class="group hover:bg-gray-50/50 transition-colors duration-200"
                        >
                            <td class="p-5">
                                <span
                                    class="font-mono font-medium text-indigo-600 bg-indigo-50 px-2 py-1 rounded text-xs"
                                >
                                    #{{ order.invoice_no }}
                                </span>
                            </td>
                            <td class="p-5 text-sm text-gray-600">
                                {{
                                    new Date(
                                        order.created_at
                                    ).toLocaleDateString()
                                }}
                            </td>
                            <td class="p-5">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide shadow-sm"
                                    :class="{
                                        'bg-yellow-100 text-yellow-700 border border-yellow-200':
                                            order.status === 'pending',
                                        'bg-blue-100 text-blue-700 border border-blue-200':
                                            order.status === 'processing',
                                        'bg-green-100 text-green-700 border border-green-200':
                                            order.status === 'completed',
                                        'bg-red-100 text-red-700 border border-red-200':
                                            order.status === 'cancelled',
                                    }"
                                >
                                    {{ order.status }}
                                </span>
                            </td>
                            <td
                                class="p-5 text-right font-bold text-gray-800 group-hover:text-indigo-600 transition-colors"
                            >
                                ৳{{ order.total_amount }}
                            </td>
                        </tr>

                        <tr v-if="recent_orders.length === 0">
                            <td
                                colspan="4"
                                class="p-10 text-center text-gray-500"
                            >
                                <div class="flex flex-col items-center">
                                    <ShoppingBagIcon
                                        class="w-12 h-12 text-gray-300 mb-2"
                                    />
                                    <p>{{ __("No orders found.") }}</p>
                                    <Link
                                        href="/"
                                        class="mt-4 px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition"
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
