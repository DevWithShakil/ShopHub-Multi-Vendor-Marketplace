<script setup>
import { Head, Link } from "@inertiajs/vue3";
import UserDashboardLayout from "@/Layouts/UserDashboardLayout.vue";
import {
    ShoppingBagIcon,
    EyeIcon,
    HomeIcon,
    ArrowDownTrayIcon, // ✅ আইকন ইমপোর্ট করা হলো
} from "@heroicons/vue/24/outline";

defineProps({
    orders: Object, // Pagination Object
});
</script>

<template>
    <Head title="My Orders" />

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
                    class="flex justify-between items-end mb-8 animate-fade-in-up"
                >
                    <div>
                        <h1
                            class="text-3xl font-black text-white tracking-tight mb-2 flex items-center gap-3"
                        >
                            <ShoppingBagIcon class="w-8 h-8 text-indigo-400" />
                            {{ __("My Orders") }}
                        </h1>
                        <p class="text-gray-400 text-sm">
                            {{
                                __(
                                    "Check the status of recent orders, manage returns, and download invoices."
                                )
                            }}
                        </p>
                    </div>
                    <Link
                        href="/"
                        class="hidden md:flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 rounded-xl border border-white/10 text-sm font-bold text-gray-300 transition"
                    >
                        <HomeIcon class="w-4 h-4" /> Go Home
                    </Link>
                </div>

                <div
                    class="bg-white/5 backdrop-blur-md rounded-3xl border border-white/10 overflow-hidden shadow-2xl animate-fade-in-up"
                >
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-white/5 border-b border-white/10 text-gray-400 text-xs uppercase tracking-wider"
                                >
                                    <th class="p-6 font-bold">Invoice #</th>
                                    <th class="p-6 font-bold">Date</th>
                                    <th class="p-6 font-bold">Items</th>
                                    <th class="p-6 font-bold">Total Amount</th>
                                    <th class="p-6 font-bold">Status</th>
                                    <th class="p-6 font-bold text-right">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr
                                    v-for="order in orders.data"
                                    :key="order.id"
                                    class="group hover:bg-white/5 transition-colors duration-200"
                                >
                                    <td class="p-6">
                                        <span
                                            class="font-mono font-bold text-indigo-400 bg-indigo-500/10 px-3 py-1.5 rounded-lg border border-indigo-500/20"
                                        >
                                            #{{ order.invoice_no }}
                                        </span>
                                    </td>

                                    <td
                                        class="p-6 text-sm text-gray-300 font-medium"
                                    >
                                        {{
                                            new Date(
                                                order.created_at
                                            ).toLocaleDateString()
                                        }}
                                    </td>

                                    <td class="p-6 text-sm text-gray-400">
                                        {{ order.items_count }} Items
                                    </td>

                                    <td
                                        class="p-6 font-bold text-white text-lg"
                                    >
                                        ৳{{ order.total_amount }}
                                    </td>

                                    <td class="p-6">
                                        <span
                                            class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wide border shadow-sm flex w-fit items-center gap-1.5"
                                            :class="{
                                                'bg-yellow-500/10 text-yellow-400 border-yellow-500/20':
                                                    order.status === 'pending',
                                                'bg-blue-500/10 text-blue-400 border-blue-500/20':
                                                    order.status ===
                                                    'processing',
                                                'bg-purple-500/10 text-purple-400 border-purple-500/20':
                                                    order.status === 'shipped',
                                                'bg-green-500/10 text-green-400 border-green-500/20':
                                                    order.status ===
                                                        'delivered' ||
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
                                                    'bg-purple-400':
                                                        order.status ===
                                                        'shipped',
                                                    'bg-green-400':
                                                        order.status ===
                                                            'delivered' ||
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

                                    <td class="p-6 text-right">
                                        <div
                                            class="flex items-center justify-end gap-2"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'dashboard.order.details',
                                                        order.invoice_no
                                                    )
                                                "
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition shadow-lg shadow-indigo-500/20"
                                            >
                                                <EyeIcon class="w-4 h-4" /> View
                                            </Link>

                                            <a
                                                :href="
                                                    route(
                                                        'invoice.download',
                                                        order.id
                                                    )
                                                "
                                                target="_blank"
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/10 text-gray-300 hover:text-white text-xs font-bold rounded-lg transition shadow-lg"
                                            >
                                                <ArrowDownTrayIcon
                                                    class="w-4 h-4"
                                                />
                                                Invoice
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="orders.data.length === 0">
                                    <td colspan="6" class="p-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-6 border border-white/10"
                                            >
                                                <ShoppingBagIcon
                                                    class="w-10 h-10 text-gray-500"
                                                />
                                            </div>
                                            <h3
                                                class="text-xl font-bold text-white mb-2"
                                            >
                                                {{ __("No orders found") }}
                                            </h3>
                                            <p class="text-gray-400 mb-6">
                                                {{
                                                    __(
                                                        "Looks like you haven't placed any orders yet."
                                                    )
                                                }}
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

                    <div
                        v-if="orders.links.length > 3"
                        class="p-6 border-t border-white/10 flex justify-center gap-2 bg-black/20"
                    >
                        <Link
                            v-for="(link, k) in orders.links"
                            :key="k"
                            :href="link.url"
                            v-html="link.label"
                            class="px-4 py-2 rounded-lg text-sm font-bold transition-all border"
                            :class="
                                link.active
                                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-500/30'
                                    : 'bg-white/5 text-gray-400 border-white/10 hover:bg-white/10 hover:text-white'
                            "
                            :preserve-scroll="true"
                        />
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
