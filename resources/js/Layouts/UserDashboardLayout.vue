<script setup>
import { ref } from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
    Squares2X2Icon,
    ShoppingBagIcon,
    UserIcon,
    ArrowRightOnRectangleIcon,
    Bars3Icon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";

const page = usePage();
const showMobileMenu = ref(false);

const isActive = (route) => page.url.startsWith(route);

// Menu Links Configuration
const menuItems = [
    { name: "Dashboard", route: "/dashboard", icon: Squares2X2Icon },
    { name: "My Orders", route: "/dashboard/orders", icon: ShoppingBagIcon },
    { name: "Profile Settings", route: "/dashboard/profile", icon: UserIcon },
];
</script>

<template>
    <MainLayout>
        <div
            class="min-h-screen bg-[#0B0F19] relative overflow-hidden font-sans text-gray-200"
        >
            <div
                class="absolute top-0 left-0 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[150px] pointer-events-none"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[150px] pointer-events-none"
            ></div>

            <div class="container mx-auto px-4 py-8 lg:py-12 relative z-10">
                <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
                    <div
                        class="lg:hidden flex justify-between items-center bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 mb-4"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-lg"
                            >
                                {{ $page.props.auth.user.name.charAt(0) }}
                            </div>
                            <span class="font-bold text-white">My Account</span>
                        </div>
                        <button
                            @click="showMobileMenu = !showMobileMenu"
                            class="text-gray-300 hover:text-white transition p-2 hover:bg-white/10 rounded-lg"
                        >
                            <Bars3Icon v-if="!showMobileMenu" class="w-6 h-6" />
                            <XMarkIcon v-else class="w-6 h-6" />
                        </button>
                    </div>

                    <aside
                        :class="[
                            'lg:w-72 flex-shrink-0 transition-all duration-300 ease-in-out z-40',
                            showMobileMenu
                                ? 'fixed inset-0 bg-[#0B0F19]/95 backdrop-blur-xl p-6 z-50 overflow-y-auto border-r border-white/10'
                                : 'hidden lg:block',
                        ]"
                    >
                        <div
                            v-if="showMobileMenu"
                            class="flex justify-end mb-6 lg:hidden"
                        >
                            <button
                                @click="showMobileMenu = false"
                                class="p-2 bg-white/10 rounded-full hover:bg-white/20 transition text-white"
                            >
                                <XMarkIcon class="w-6 h-6" />
                            </button>
                        </div>

                        <div
                            class="bg-white/5 backdrop-blur-md rounded-3xl border border-white/10 overflow-hidden sticky top-24 shadow-xl"
                        >
                            <div
                                class="p-8 border-b border-white/10 bg-gradient-to-br from-white/5 to-transparent"
                            >
                                <div
                                    class="flex flex-col items-center text-center"
                                >
                                    <div
                                        class="w-24 h-24 p-1 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 shadow-lg shadow-indigo-500/20 mb-4"
                                    >
                                        <div
                                            class="w-full h-full bg-[#151925] rounded-full flex items-center justify-center text-white text-3xl font-bold border-2 border-transparent bg-clip-padding"
                                        >
                                            {{
                                                $page.props.auth.user.name.charAt(
                                                    0
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <h3 class="font-bold text-white text-xl">
                                        {{ $page.props.auth.user.name }}
                                    </h3>
                                    <p class="text-sm text-gray-400 mt-1">
                                        {{ $page.props.auth.user.email }}
                                    </p>
                                    <span
                                        class="mt-3 px-3 py-1 bg-green-500/10 text-green-400 text-xs font-bold rounded-full border border-green-500/20 uppercase tracking-wide"
                                    >
                                        {{
                                            $page.props.auth.user.role ||
                                            "Customer"
                                        }}
                                    </span>
                                </div>
                            </div>

                            <nav class="p-4 space-y-2">
                                <Link
                                    v-for="item in menuItems"
                                    :key="item.name"
                                    :href="item.route"
                                    @click="showMobileMenu = false"
                                    class="group flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm font-medium transition-all duration-300"
                                    :class="
                                        isActive(item.route)
                                            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 translate-x-1'
                                            : 'text-gray-400 hover:bg-white/5 hover:text-white hover:translate-x-1'
                                    "
                                >
                                    <component
                                        :is="item.icon"
                                        class="w-5 h-5 transition-transform group-hover:scale-110"
                                        :class="
                                            isActive(item.route)
                                                ? 'text-white'
                                                : 'text-gray-500 group-hover:text-indigo-400'
                                        "
                                    />
                                    {{ item.name }}
                                </Link>

                                <div
                                    class="my-4 border-t border-white/10"
                                ></div>

                                <Link
                                    href="/logout"
                                    method="post"
                                    as="button"
                                    class="w-full group flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm font-medium text-rose-500 hover:bg-rose-500/10 hover:text-rose-400 transition-all duration-300 hover:translate-x-1"
                                >
                                    <ArrowRightOnRectangleIcon
                                        class="w-5 h-5 transition-transform group-hover:-translate-x-1"
                                    />
                                    Logout
                                </Link>
                            </nav>
                        </div>
                    </aside>

                    <main class="flex-1 w-full min-w-0">
                        <slot />
                    </main>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
