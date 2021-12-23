import Vue from "vue";
import Router from "vue-router";
import Home from "./home/home.vue";
import Personal from "./personal/personal.vue";
import Sign from "./signme/sign.vue";
import Payouts from "./payouts/payouts.vue";
import Cards from "./payouts/cards.vue";
import Settings from "./settings/settings.vue";
import PasswordReset from "./settings/password-reset.vue";
import Integrations from "./integrations/integrations.vue";
import NPD from "./integrations/npd.vue";

Vue.use(Router);

export default new Router({
    mode: "history",
    base: process.env.APP_URL,
    routes: [
        {
            path: "/my",
            name: "home",
            component: Home,
        },
        {
            path: "/my/personal",
            name: "personal",
            component: Personal,
        },
        {
            path: "/my/sign",
            name: "sign",
            component: Sign,
        },
        {
            path: "/my/bank",
            name: "bank",
            component: Payouts,
        },
        {
            path: "/my/bank/cards",
            name: "cards",
            component: Cards,
        },
        {
            path: "/my/settings",
            name: "settings",
            component: Settings,
        },
        {
            path: "/my/settings/password",
            name: "password",
            component: PasswordReset,
        },
        {
            path: "/my/integrations",
            name: "integrations",
            component: Integrations,
        },
        {
            path: "/my/integrations/npd",
            name: "npd",
            component: NPD,
        },
    ],
});
