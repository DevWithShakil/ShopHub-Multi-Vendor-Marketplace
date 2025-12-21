<script setup>
import { Head, Link } from "@inertiajs/vue3";
import UserDashboardLayout from "@/Layouts/UserDashboardLayout.vue";
import { ShoppingBagIcon } from "@heroicons/vue/24/outline";

defineProps({
    orders: Object, // Pagination Object
});
</script>

<template>
    <Head title="My Orders" />

    <UserDashboardLayout>
        <div class="flex items-center justify-between mb-6">
            <h1
                class="text-2xl font-bold text-gray-800 flex items-center gap-2"
            >
                <ShoppingBagIcon class="w-7 h-7 text-indigo-600" /> My Orders
            </h1>
        </div>

        <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
        >
            <table class="w-full text-left text-sm">
                <thead
                    class="bg-gray-50 text-gray-500 border-b border-gray-200"
                >
                    <tr>
                        <th class="p-4">Invoice #</th>
                        <th class="p-4">Date</th>
                        <th class="p-4">Items</th>
                        <th class="p-4">Total Amount</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="order in orders.data"
                        :key="order.id"
                        class="hover:bg-gray-50 transition"
                    >
                        <td class="p-4 font-mono font-bold text-indigo-600">
                            {{ order.invoice_no }}
                        </td>
                        <td class="p-4 text-gray-500">
                            {{ order.created_at }}
                        </td>
                        <td class="p-4 text-gray-600">
                            {{ order.items_count }} Items
                        </td>
                        <td class="p-4 font-bold text-gray-800">
                            ৳{{ order.total_amount }}
                        </td>
                        <td class="p-4">
                            <span
                                class="px-2 py-1 rounded text-xs font-bold uppercase"
                                :class="{
                                    'bg-yellow-100 text-yellow-700':
                                        order.status === 'pending',
                                    'bg-blue-100 text-blue-700':
                                        order.status === 'processing',
                                    'bg-green-100 text-green-700':
                                        order.status === 'completed',
                                    'bg-red-100 text-red-700':
                                        order.status === 'cancelled',
                                }"
                            >
                                {{ order.status }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <button
                                class="text-indigo-600 hover:text-indigo-900 font-medium border px-3 py-1 rounded hover:bg-indigo-50"
                            >
                                View
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div
                v-if="orders.data.length === 0"
                class="p-10 text-center text-gray-500"
            >
                You have no orders yet.
            </div>

            <div
                v-if="orders.links.length > 3"
                class="p-4 border-t border-gray-100 flex justify-center gap-2"
            >
                <Link
                    v-for="(link, k) in orders.links"
                    :key="k"
                    :href="link.url"
                    v-html="link.label"
                    class="px-3 py-1 border rounded text-sm"
                    :class="
                        link.active
                            ? 'bg-indigo-600 text-white'
                            : 'bg-white text-gray-600 hover:bg-gray-50'
                    "
                    :disabled="!link.url"
                />
            </div>
        </div>
    </UserDashboardLayout>
</template>
