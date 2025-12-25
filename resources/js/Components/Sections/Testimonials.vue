<script setup>
import { StarIcon } from "@heroicons/vue/24/solid";
import { ChatBubbleBottomCenterTextIcon } from "@heroicons/vue/24/outline";

const reviews = [
    {
        name: "Rahim Ahmed",
        role: "Verified Buyer",
        comment:
            "Amazing quality! The delivery was super fast, and the packaging was premium. Highly recommended.",
        avatar: "https://i.pravatar.cc/150?u=a042581f4e29026024d",
        rating: 5,
    },
    {
        name: "Sadia Islam",
        role: "Gadget Lover",
        comment:
            "Best shopping experience ever. The product matches the description perfectly. Customer support is very helpful.",
        avatar: "https://i.pravatar.cc/150?u=a042581f4e29026704d",
        rating: 5,
    },
    {
        name: "Tanvir Hasan",
        role: "Tech Enthusiast",
        comment:
            "ShopHub never disappoints. I bought a mechanical keyboard and it works like a charm. 10/10 service.",
        avatar: "https://i.pravatar.cc/150?u=a04258114e29026302d",
        rating: 4,
    },
];
</script>

<template>
    <section class="py-20 bg-[#0B0F19] relative overflow-hidden">
        <div
            class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/5 rounded-full blur-[100px] pointer-events-none"
        ></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-indigo-400 text-xs font-bold uppercase tracking-widest mb-4"
                >
                    <ChatBubbleBottomCenterTextIcon class="w-4 h-4" /> User
                    Feedback
                </div>
                <h2
                    class="text-3xl md:text-5xl font-black text-white tracking-tight"
                >
                    Trusted by <span class="text-indigo-500">Thousands</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    v-for="(review, index) in reviews"
                    :key="index"
                    class="bg-[#151925] p-8 rounded-[2rem] border border-white/5 hover:border-indigo-500/30 transition-all duration-300 hover:-translate-y-2 group"
                >
                    <div class="flex text-yellow-400 mb-6">
                        <StarIcon
                            v-for="i in 5"
                            :key="i"
                            class="w-5 h-5"
                            :class="
                                i <= review.rating
                                    ? 'text-yellow-400'
                                    : 'text-gray-700'
                            "
                        />
                    </div>

                    <p
                        class="text-gray-300 text-lg leading-relaxed mb-8 italic"
                    >
                        "{{ review.comment }}"
                    </p>

                    <div class="flex items-center gap-4">
                        <img
                            :src="review.avatar"
                            class="w-12 h-12 rounded-full border-2 border-indigo-500/50"
                        />
                        <div>
                            <h4 class="text-white font-bold">
                                {{ review.name }}
                            </h4>
                            <p
                                class="text-xs text-gray-500 uppercase tracking-wider font-bold"
                            >
                                {{ review.role }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
