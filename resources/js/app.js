import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// Pinia Imports
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {

        // ১. পিনিয়া ইনস্ট্যান্স তৈরি এবং পার্সিস্টেন্স প্লাগিন যুক্ত করা
        const pinia = createPinia();
        pinia.use(piniaPluginPersistedstate);

        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(pinia) // পিনিয়া ব্যবহার
            // ✅ গ্লোবাল ট্রান্সলেশন মিক্সিন (একটু সেফটি সহ)
            .mixin({
                methods: {
                    __(key) {
                        // যদি translations না থাকে, তাহলে ফাঁকা অবজেক্ট ধরবে (এরর এড়াতে)
                        const translations = this.$page.props.translations || {};
                        return translations[key] || key;
                    }
                }
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
