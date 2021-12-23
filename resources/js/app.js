import Vue from "vue";
import { BootstrapVue, BootstrapVueIcons } from "bootstrap-vue";

import Multiselect from "vue-multiselect";
import VueMask from "v-mask";
//components
import profile from "./components/client/profile.vue";
import companyprofile from "./components/client/company-profile.vue";
import companypayouts from "./components/client/company-payouts.vue";
import companypermissiontable from "./components/client/company-permission-table.vue";
import addrolemodal from "./components/client/add-role-modal.vue";
import addemployeemodal from "./components/client/add-employee-modal.vue";
import editemployeemodal from "./components/client/edit-employee-modal.vue";
import changepermissionsmodal from "./components/client/change-permissions-modal.vue";
import companyemployee from "./components/client/company-employee.vue";
import registerpage from "./components/contractor/register-page";
import employeecard from "./components/client/employee-card.vue";

import employeeaddtoprojectmodal from "./components/client/employee-add-to-project-modal.vue";
import eventbus from "./common/event-bus";

import App from "./components/contractor/app.vue";
import router from "./components/contractor/router";
import store from "./components/contractor/store";

Vue.use(VueMask);
Vue.component("multiselect", Multiselect);
Vue.use(BootstrapVue);
Vue.use(BootstrapVueIcons);

const appClient = new Vue({
    components: {
        employeecard,
        profile,
        companyprofile,
        employeeaddtoprojectmodal,
        companypayouts,
        companypermissiontable,
        addrolemodal,
        changepermissionsmodal,
        companyemployee,
        addemployeemodal,
        editemployeemodal,
        registerpage,
        Multiselect,
    },
    data: () => ({
        eventbus,
    }),
}).$mount("#app");

const appContractor = new Vue({
    router,
    store,
    render: (h) => h(App),
    data: { loading: false },
}).$mount("#contractor-app");

router.beforeEach((to, from, next) => {
    appContractor.loading = true;
    next();
});

router.afterEach(() => {
    // appContractor.loading = false;
    setTimeout(() => (appContractor.loading = false), 500); // timeout for demo purposes
});
