import Vue from "vue";
import Vuex from "vuex";
import HTTP from "../../common/http-client";
import HTTPV2 from "../../common/httpv2-client";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        userId: null,
        me: null,
        taxpayer_binded_to_platform: null,
        taxpayer_bind_id: null,
        job_categories: [],
        signme: null,
    },
    mutations: {
        setSignmeState(state, data) {
            state.signme = data;
        },
        setUser(state, data) {
            state.userId = data.me.id;
            state.me = data.me;
            state.taxpayer_binded_to_platform =
                data.me.taxpayer_binded_to_platform;
            state.taxpayer_bind_id = data.me.taxpayer_bind_id;
        },
        setCategories(state, data) {
            state.job_categories = data;
        },
    },
    actions: {
        getUser(context, data) {
            console.log("test");
            HTTP.get("me?withPermissions=1")
                .then((res) => {
                    if (res.status == 200) {
                        const data = res.data;
                        this.commit("setUser", data);
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        bindTaxpayer(context, data) {
            data.component.$data.isLoading = true;
            HTTPV2.post("contractor/npd/bind_to_partner")
                .then((res) => {
                    if (res.status == 200) {
                        bootbox.dialog({
                            title: "Результат привязки к партнеру",
                            closeButton: false,
                            message: res.data.message,
                            buttons: {
                                cancel: {
                                    label: "Закрыть",
                                    className: "btn-light",
                                },
                            },
                        });
                        this.dispatch("getUser");
                        data.component.$data.isLoading = false;
                    }
                })
                .catch((error) => {
                    bootbox.dialog({
                        title: error.response.data.title ?? "Ошибка",
                        message:
                            error.response.data.message ??
                            error.response.statusText,
                        closeButton: false,
                        buttons: {
                            cancel: {
                                label: "Закрыть",
                                className: "btn-dark",
                            },
                        },
                    });
                    data.component.$data.isLoading = false;
                });
        },
        cancelBind(context, data) {
            data.component.$data.isLoading = true;
            HTTPV2.post("contractor/npd/cancel_bind_to_partner")
                .then((res) => {
                    if (res.status == 200) {
                        this.dispatch("getUser");
                        data.component.$data.isLoading = false;
                    }
                })
                .catch((error) => {
                    bootbox.dialog({
                        title: error.response.data.title ?? "Ошибка",
                        message:
                            error.response.data.message ??
                            error.response.statusText,
                        closeButton: false,
                        buttons: {
                            cancel: {
                                label: "Закрыть",
                                className: "btn-dark",
                            },
                        },
                    });
                    data.component.$data.isLoading = false;
                });
        },
        unbindTaxpayer(context, data) {
            data.component.$data.isLoading = true;
            HTTPV2.post("contractor/npd/unbind_from_partner")
                .then((res) => {
                    if (res.status == 200) {
                        this.dispatch("getUser");

                        document.querySelector("#not_npd_alert").hidden = false;
                        data.component.$data.isLoading = false;
                    }
                })
                .catch((error) => {
                    bootbox.dialog({
                        title: error.response.data.title ?? "Ошибка",
                        message:
                            error.response.data.message ??
                            error.response.statusText,
                        closeButton: false,
                        buttons: {
                            cancel: {
                                label: "Закрыть",
                                className: "btn-dark",
                            },
                        },
                    });
                    data.component.$data.isLoading = false;
                });
        },
        checkBind(context, data) {
            data.component.$data.isLoading = true;
            HTTPV2.post("contractor/npd/check_binding")
                .then((res) => {
                    if (res.status == 200) {
                        const data = res.data.user;
                        if (
                            data.taxpayer_binded_to_platform == false &&
                            data.taxpayer_bind_id != null
                        ) {
                            bootbox.dialog({
                                title: "Ошибка",
                                message: `Вы не подтвердили привязку в "Мой налог"`,
                                closeButton: false,
                                buttons: {
                                    cancel: {
                                        label: "Закрыть",
                                        className: "btn-dark",
                                    },
                                },
                            });
                        }
                        if (res.data?.status === "FAILED") {
                            this.dispatch("getUser");
                        }
                        document.querySelector("#not_npd_alert").hidden = true;
                        this.dispatch("getUser");
                        data.component.$data.isLoading = false;
                    }
                })
                .catch((error) => {
                    data.component.$data.isLoading = false;
                    bootbox.dialog({
                        title: error.response.data.title ?? "Ошибка",
                        message:
                            error.response.data.message ??
                            error.response.statusText,
                        closeButton: false,
                        buttons: {
                            cancel: {
                                label: "Закрыть",
                                className: "btn-dark",
                            },
                        },
                    });
                });
        },
        signmeState(context, data) {
            HTTPV2.get("contractor/signme/state")
                .then((res) => {
                    this.commit("setSignmeState", res.data.signme_state);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
    },
});
