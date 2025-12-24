<script setup>
import { Link, usePage, router } from "@inertiajs/vue3";
import {
    FireIcon,
    ChevronDownIcon,
    ChevronRightIcon,
} from "@heroicons/vue/24/outline";
import { ref, computed } from "vue";

const props = defineProps({
    categories: Array,
});

const page = usePage();
const activeCategoryId = ref(null);

const activeCategoryChildren = computed(() => {
    if (!activeCategoryId.value) return [];
    const cat = props.categories.find((c) => c.id === activeCategoryId.value);
    return cat ? cat.children : [];
});

const handleMouseEnter = (id) => {
    activeCategoryId.value = id;
};
const handleMouseLeave = () => {
    activeCategoryId.value = null;
};

const getLocalizedName = (nameField) => {
    try {
        if (typeof nameField === "string" && nameField.startsWith("{")) {
            const parsed = JSON.parse(nameField);
            return parsed[page.props.locale] || parsed["en"] || "Unknown";
        }
        if (typeof nameField === "object" && nameField !== null) {
            return nameField[page.props.locale] || nameField["en"] || "Unknown";
        }
        return nameField || "General";
    } catch (e) {
        return nameField || "General";
    }
};

const scrollToSection = (categoryName, categorySlug) => {
    const sectionId =
        "cat-section-" + categoryName.replace(/\s+/g, "-").toLowerCase();
    const element = document.getElementById(sectionId);
    if (element) {
        const offset = 100;
        const rect = element.getBoundingClientRect();
        const scrollTop =
            window.pageYOffset || document.documentElement.scrollTop;
        window.scrollTo({
            top: rect.top + scrollTop - offset,
            behavior: "smooth",
        });
    } else {
        router.visit(route("products.index", { category: categorySlug }));
    }
};

const scrollToTop = () => window.scrollTo({ top: 0, behavior: "smooth" });
</script>

<template>
    <div
        class="bg-[#0B0F19]/90 border-b border-white/10 py-4 sticky top-[80px] z-40 backdrop-blur-xl shadow-lg transition-all"
        @mouseleave="handleMouseLeave"
    >
        <div class="container mx-auto px-4 lg:px-8 relative">
            <div
                class="flex items-center gap-4 overflow-x-auto no-scrollbar relative z-20"
            >
                <button
                    @click="scrollToTop"
                    class="px-5 py-2.5 rounded-full bg-white text-gray-900 text-sm font-bold whitespace-nowrap shadow-lg shadow-white/10 hover:scale-105 transition-transform flex items-center gap-2 flex-shrink-0"
                >
                    <FireIcon class="w-4 h-4 text-orange-500" /> All Products
                </button>

                <button
                    v-for="category in categories"
                    :key="category.id"
                    @mouseenter="handleMouseEnter(category.id)"
                    @click="
                        scrollToSection(
                            getLocalizedName(category.name),
                            category.slug
                        )
                    "
                    class="px-5 py-2.5 rounded-full border border-white/10 text-sm font-semibold whitespace-nowrap transition-colors flex items-center gap-1 flex-shrink-0 cursor-pointer"
                    :class="
                        activeCategoryId === category.id
                            ? 'bg-white/20 text-white border-indigo-500/50'
                            : 'bg-white/5 text-gray-300 hover:bg-white/10 hover:text-white'
                    "
                >
                    {{ getLocalizedName(category.name) }}
                    <ChevronDownIcon
                        v-if="category.children && category.children.length > 0"
                        class="w-3 h-3 mt-0.5 transition-transform"
                        :class="
                            activeCategoryId === category.id ? 'rotate-180' : ''
                        "
                    />
                </button>
            </div>

            <transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 translate-y-2"
            >
                <div
                    v-if="activeCategoryId && activeCategoryChildren.length > 0"
                    class="absolute top-full left-0 mt-4 w-full bg-[#151925]/95 backdrop-blur-xl border-t border-b border-white/10 shadow-2xl z-10 min-h-[300px] max-h-[60vh] overflow-y-auto custom-scrollbar"
                    @mouseenter="handleMouseEnter(activeCategoryId)"
                >
                    <div class="container mx-auto px-8 py-8">
                        <div
                            class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-y-10 gap-x-8"
                        >
                            <div
                                v-for="child in activeCategoryChildren"
                                :key="child.id"
                                class="group/column"
                            >
                                <Link
                                    :href="
                                        route('products.index', {
                                            category: child.slug,
                                        })
                                    "
                                    class="flex items-center gap-2 font-bold text-white text-base mb-4 hover:text-indigo-400 transition-colors pb-2 border-b border-white/10"
                                >
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-indigo-500"
                                    ></span>
                                    {{ getLocalizedName(child.name) }}
                                </Link>
                                <ul
                                    v-if="
                                        child.children &&
                                        child.children.length > 0
                                    "
                                    class="space-y-2.5"
                                >
                                    <li
                                        v-for="grandChild in child.children"
                                        :key="grandChild.id"
                                    >
                                        <Link
                                            :href="
                                                route('products.index', {
                                                    category: grandChild.slug,
                                                })
                                            "
                                            class="text-sm text-gray-400 hover:text-white hover:translate-x-1 transition-all block flex items-center gap-1.5"
                                        >
                                            <ChevronRightIcon
                                                class="w-3 h-3 text-gray-600"
                                            />
                                            {{
                                                getLocalizedName(
                                                    grandChild.name
                                                )
                                            }}
                                        </Link>
                                        <ul
                                            v-if="
                                                grandChild.children &&
                                                grandChild.children.length > 0
                                            "
                                            class="pl-4 mt-1.5 space-y-1 border-l border-white/5 ml-1.5"
                                        >
                                            <li
                                                v-for="greatGrandChild in grandChild.children"
                                                :key="greatGrandChild.id"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'products.index',
                                                            {
                                                                category:
                                                                    greatGrandChild.slug,
                                                            }
                                                        )
                                                    "
                                                    class="text-[11px] text-gray-500 hover:text-indigo-300 transition-colors block py-0.5 hover:underline"
                                                >
                                                    {{
                                                        getLocalizedName(
                                                            greatGrandChild.name
                                                        )
                                                    }}
                                                </Link>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.2);
}
</style>
