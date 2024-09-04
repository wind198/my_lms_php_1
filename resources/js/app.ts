import "./bootstrap";
import "../css/app.scss";

import { createApp, h, DefineComponent } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import SimpleLayoutForLogin from "./Layouts/SimpleLayoutForLogin.vue";
import ColumnsLayoutForDashboard from "./Layouts/ColumnsLayoutForDashboard.vue";
import vuetify from "./vuetify";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const pages = import.meta.glob<DefineComponent>("./Pages/**/*.vue", {
            eager: true,
        });
        let page: any = pages[`./Pages/${name}.vue`];
        page.default.layout =
            page.default.layout ??
            (name.startsWith("Auth/")
                ? SimpleLayoutForLogin
                : ColumnsLayoutForDashboard);
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(vuetify)
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
