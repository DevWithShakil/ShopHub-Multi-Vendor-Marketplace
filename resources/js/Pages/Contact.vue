<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {
    EnvelopeIcon,
    PhoneIcon,
    MapPinIcon,
    PaperAirplaneIcon,
    ChatBubbleLeftEllipsisIcon,
    SparklesIcon,
} from "@heroicons/vue/24/outline";

const form = useForm({
    name: "",
    email: "",
    subject: "",
    message: "",
});

const submit = () => {
    form.post(route("contact.send"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Contact Our Team" />

    <MainLayout>
        <div
            class="min-h-screen bg-[#06080F] relative flex items-center justify-center overflow-hidden py-20"
        >
            <div
                class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-indigo-600/20 rounded-full blur-[120px] pointer-events-none animate-pulse"
            ></div>
            <div
                class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-purple-600/10 rounded-full blur-[120px] pointer-events-none animate-pulse"
                style="animation-delay: 2s"
            ></div>
            <div
                class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 pointer-events-none"
            ></div>

            <div class="container mx-auto px-6 lg:px-12 relative z-10">
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-24 items-center"
                >
                    <div class="space-y-10">
                        <div class="space-y-6">
                            <div
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-bold uppercase tracking-[0.2em]"
                            >
                                <SparklesIcon class="w-4 h-4" /> Available 24/7
                            </div>
                            <h1
                                class="text-6xl md:text-8xl font-black text-white leading-[0.9] tracking-tighter"
                            >
                                Get In <br />
                                <span
                                    class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-500"
                                    >Touch.</span
                                >
                            </h1>
                            <p
                                class="text-gray-400 text-lg md:text-xl max-w-md leading-relaxed"
                            >
                                Have a question or just want to say hi? Our team
                                is always ready to assist you.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div
                                v-for="info in [
                                    {
                                        icon: PhoneIcon,
                                        label: 'Call Us',
                                        val: '+880 1568-013150',
                                        color: 'text-indigo-400',
                                    },
                                    {
                                        icon: EnvelopeIcon,
                                        label: 'Email Us',
                                        val: 'support@shophub.com',
                                        color: 'text-purple-400',
                                    },
                                ]"
                                :key="info.label"
                                class="space-y-3"
                            >
                                <component
                                    :is="info.icon"
                                    class="w-6 h-6"
                                    :class="info.color"
                                />
                                <h4 class="text-white font-bold text-lg">
                                    {{ info.label }}
                                </h4>
                                <p class="text-gray-400 text-sm font-medium">
                                    {{ info.val }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-4 p-5 bg-white/5 border border-white/10 rounded-3xl"
                        >
                            <div
                                class="w-12 h-12 bg-indigo-500/20 rounded-2xl flex items-center justify-center text-indigo-400"
                            >
                                <MapPinIcon class="w-6 h-6" />
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-sm">
                                    Location
                                </h4>
                                <p class="text-gray-400 text-sm">
                                    Banani, Dhaka - 1213, Bangladesh
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-[3rem] blur opacity-20"
                        ></div>

                        <div
                            class="relative bg-[#0D111A] border border-white/10 p-8 md:p-12 rounded-[3rem] shadow-2xl"
                        >
                            <div class="flex items-center gap-4 mb-10">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg"
                                >
                                    <ChatBubbleLeftEllipsisIcon
                                        class="w-6 h-6 text-white"
                                    />
                                </div>
                                <h3 class="text-2xl font-black text-white">
                                    Send a message
                                </h3>
                            </div>

                            <form @submit.prevent="submit" class="space-y-6">
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                >
                                    <div class="space-y-2">
                                        <label
                                            class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1"
                                            >Full Name</label
                                        >
                                        <input
                                            v-model="form.name"
                                            type="text"
                                            required
                                            placeholder="John Doe"
                                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:ring-2 focus:ring-indigo-500 focus:bg-white/10 outline-none transition-all placeholder:text-gray-700"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <label
                                            class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1"
                                            >Email Address</label
                                        >
                                        <input
                                            v-model="form.email"
                                            type="email"
                                            required
                                            placeholder="john@example.com"
                                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:ring-2 focus:ring-indigo-500 focus:bg-white/10 outline-none transition-all placeholder:text-gray-700"
                                        />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1"
                                        >Subject</label
                                    >
                                    <input
                                        v-model="form.subject"
                                        type="text"
                                        required
                                        placeholder="How can we help?"
                                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:ring-2 focus:ring-indigo-500 focus:bg-white/10 outline-none transition-all placeholder:text-gray-700"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1"
                                        >Your Message</label
                                    >
                                    <textarea
                                        v-model="form.message"
                                        rows="4"
                                        required
                                        placeholder="Tell us more about your inquiry..."
                                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white focus:ring-2 focus:ring-indigo-500 focus:bg-white/10 outline-none transition-all resize-none placeholder:text-gray-700"
                                    ></textarea>
                                </div>

                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full relative overflow-hidden group bg-white text-black font-black py-5 rounded-2xl transition-all duration-300 hover:text-white"
                                >
                                    <span
                                        class="absolute inset-0 bg-indigo-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300"
                                    ></span>
                                    <span
                                        class="relative z-10 flex items-center justify-center gap-3"
                                    >
                                        {{
                                            form.processing
                                                ? "Sending..."
                                                : "Send Message"
                                        }}
                                        <PaperAirplaneIcon
                                            class="w-5 h-5 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"
                                        />
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
