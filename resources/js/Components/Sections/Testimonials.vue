<script setup>
import { StarIcon } from "@heroicons/vue/24/solid";
import { UserCircleIcon } from "@heroicons/vue/24/outline";

// ✅ Props রিসিভ
const props = defineProps({
    testimonials: {
        type: Array,
        default: () => [],
    },
});

// ইমেজ পাথ ফিক্সার
const getAvatar = (path) => {
    if (!path) return null;
    if (path.startsWith("http")) return path;
    return `/storage/${path}`;
};
</script>

<template>
    <section
        v-if="testimonials && testimonials.length > 0"
        class="py-16 bg-[#0B0F19] relative overflow-hidden"
    >
        <div
            class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"
        ></div>
        <div
            class="absolute bottom-0 right-0 w-[300px] h-[300px] bg-indigo-600/5 rounded-full blur-[100px] pointer-events-none"
        ></div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-12">
                <span
                    class="text-indigo-400 font-bold text-xs uppercase tracking-widest mb-2 block"
                    >Customer Stories</span
                >
                <h2
                    class="text-3xl md:text-4xl font-black text-white tracking-tight"
                >
                    What Our Clients
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400"
                        >Say</span
                    >
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="review in testimonials"
                    :key="review.id"
                    class="bg-[#151925] border border-white/5 p-6 rounded-2xl hover:border-indigo-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group"
                >
                    <div class="flex gap-1 mb-4">
                        <StarIcon
                            v-for="i in 5"
                            :key="i"
                            class="w-4 h-4"
                            :class="
                                i <= review.rating
                                    ? 'text-yellow-400'
                                    : 'text-gray-700'
                            "
                        />
                    </div>

                    <p
                        class="text-gray-300 text-sm leading-relaxed mb-6 italic opacity-80 group-hover:opacity-100 transition-opacity"
                    >
                        "{{ review.message }}"
                    </p>

                    <div
                        class="flex items-center gap-4 border-t border-white/5 pt-4"
                    >
                        <div
                            class="w-12 h-12 rounded-full overflow-hidden border-2 border-white/10 group-hover:border-indigo-500/50 transition-colors"
                        >
                            <img
                                v-if="review.avatar"
                                :src="getAvatar(review.avatar)"
                                :alt="review.name"
                                class="w-full h-full object-cover"
                            />
                            <UserCircleIcon
                                v-else
                                class="w-full h-full text-gray-500 bg-[#0B0F19]"
                            />
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-sm">
                                {{ review.name }}
                            </h4>
                            <p
                                class="text-xs text-indigo-400 font-medium"
                                v-if="review.designation"
                            >
                                {{ review.designation }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
