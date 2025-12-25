<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {
    BuildingStorefrontIcon,
    IdentificationIcon,
    CloudArrowUpIcon,
    RocketLaunchIcon,
} from "@heroicons/vue/24/outline";

const form = useForm({
    shop_name: "",
    owner_name: "",
    email: "",
    phone: "",
    business_type: "",
    description: "",
});

const submit = () => {
    form.post(route("vendor.store"), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Become a Vendor" />
    <MainLayout>
        <div class="min-h-screen bg-[#06080F] py-24 relative overflow-hidden">
            <div
                class="absolute top-0 left-0 w-[500px] h-[500px] bg-indigo-600/5 rounded-full blur-[120px]"
            ></div>

            <div class="container mx-auto px-6 relative z-10">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-16">
                        <div
                            class="inline-flex p-4 bg-indigo-500/10 rounded-3xl text-indigo-500 mb-6"
                        >
                            <BuildingStorefrontIcon class="w-10 h-10" />
                        </div>
                        <h1
                            class="text-5xl md:text-6xl font-black text-white mb-6 tracking-tight"
                        >
                            Start Selling on
                            <span class="text-indigo-500">ShopHub.</span>
                        </h1>
                        <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                            Join our community of 500+ successful vendors. Fill
                            out the form below and our team will review your
                            shop.
                        </p>
                    </div>

                    <div
                        class="bg-[#0D111A] border border-white/10 p-8 md:p-12 rounded-[3.5rem] shadow-3xl"
                    >
                        <form
                            @submit.prevent="submit"
                            class="grid grid-cols-1 md:grid-cols-2 gap-8"
                        >
                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1"
                                    >Shop Name</label
                                >
                                <input
                                    v-model="form.shop_name"
                                    type="text"
                                    placeholder="e.g. Dream Fashion"
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:ring-2 focus:ring-indigo-500 outline-none transition"
                                />
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1"
                                    >Owner Name</label
                                >
                                <input
                                    v-model="form.owner_name"
                                    type="text"
                                    placeholder="e.g. Rahim Uddin"
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:ring-2 focus:ring-indigo-500 outline-none transition"
                                />
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1"
                                    >Business Email</label
                                >
                                <input
                                    v-model="form.email"
                                    type="email"
                                    placeholder="vendor@example.com"
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:ring-2 focus:ring-indigo-500 outline-none transition"
                                />
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1"
                                    >Phone Number</label
                                >
                                <input
                                    v-model="form.phone"
                                    type="text"
                                    placeholder="+880 1XXX-XXXXXX"
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:ring-2 focus:ring-indigo-500 outline-none transition"
                                />
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label
                                    class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1"
                                    >Business Category</label
                                >
                                <select
                                    v-model="form.business_type"
                                    class="w-full bg-[#151925] border border-white/10 rounded-2xl px-6 py-4 text-white focus:ring-2 focus:ring-indigo-500 outline-none transition appearance-none"
                                >
                                    <option value="" disabled selected>
                                        Select Category
                                    </option>
                                    <option value="electronics">
                                        Electronics
                                    </option>
                                    <option value="fashion">
                                        Fashion & Apparel
                                    </option>
                                    <option value="home">
                                        Home & Lifestyle
                                    </option>
                                    <option value="beauty">
                                        Beauty & Personal Care
                                    </option>
                                </select>
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label
                                    class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1"
                                    >Short Business Description</label
                                >
                                <textarea
                                    v-model="form.description"
                                    rows="4"
                                    placeholder="Tell us about your products..."
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:ring-2 focus:ring-indigo-500 outline-none transition resize-none"
                                ></textarea>
                            </div>

                            <div class="md:col-span-2 pt-4">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-5 rounded-2xl transition-all flex items-center justify-center gap-3 group shadow-xl shadow-indigo-900/20"
                                >
                                    <RocketLaunchIcon
                                        class="w-6 h-6 group-hover:-rotate-12 transition-transform"
                                    />
                                    Submit Partnership Request
                                </button>
                                <p
                                    class="text-center text-gray-500 text-xs mt-6 uppercase tracking-widest"
                                >
                                    Our team will contact you within 24-48
                                    business hours.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
