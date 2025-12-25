<script setup>
import { ref } from "vue";
import { PlusIcon, MinusIcon } from "@heroicons/vue/24/outline";

const openIndex = ref(0); // Default first one open

const faqs = [
    {
        question: "How long does delivery take?",
        answer: "Inside Dhaka, we deliver within 24-48 hours. For outside Dhaka, it usually takes 3-5 business days via courier services.",
    },
    {
        question: "What is your return policy?",
        answer: "You can return any product within 7 days of receiving it if there is a manufacturing defect or shipping damage. Please keep the packaging intact.",
    },
    {
        question: "Do you offer EMI facilities?",
        answer: "Yes! We offer EMI facilities for up to 12 months on select credit cards for orders above ৳5,000.",
    },
    {
        question: "How can I track my order?",
        answer: "Once your order is shipped, you will receive a tracking ID via SMS and Email. You can also track it from your account dashboard.",
    },
];

const toggle = (index) => {
    openIndex.value = openIndex.value === index ? null : index;
};
</script>

<template>
    <section class="py-20 bg-[#0B0F19] border-t border-white/5 relative">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-black text-white mb-3">
                    Frequently Asked
                    <span class="text-indigo-500">Questions</span>
                </h2>
                <p class="text-gray-400">
                    Everything you need to know about our products and services.
                </p>
            </div>

            <div class="space-y-4">
                <div
                    v-for="(faq, index) in faqs"
                    :key="index"
                    class="bg-white/5 border border-white/5 rounded-2xl overflow-hidden transition-all duration-300"
                    :class="
                        openIndex === index
                            ? 'bg-white/10 border-indigo-500/30'
                            : 'hover:bg-white/10'
                    "
                >
                    <button
                        @click="toggle(index)"
                        class="w-full flex items-center justify-between p-6 text-left"
                    >
                        <span class="text-lg font-bold text-white">{{
                            faq.question
                        }}</span>
                        <div
                            class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center transition-colors"
                            :class="
                                openIndex === index
                                    ? 'bg-indigo-500 text-white'
                                    : 'text-gray-400'
                            "
                        >
                            <MinusIcon
                                v-if="openIndex === index"
                                class="w-5 h-5"
                            />
                            <PlusIcon v-else class="w-5 h-5" />
                        </div>
                    </button>

                    <div
                        v-show="openIndex === index"
                        class="px-6 pb-6 text-gray-400 text-sm leading-relaxed animate-fade-in"
                    >
                        {{ faq.answer }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
