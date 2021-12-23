<template>
  <div class="card">
    <div class="card-header">
      <h2 class="card-title">
        <b class="fad fa-user me-2"></b>Платежные системы
      </h2>
    </div>

    <form @submit.prevent="saveData">
      <div class="card-body">
        <div class="form-group mt-3">
          <label class="text-muted">Идентификатор партнера МОБИ Деньги</label>
          <input
            v-model="mobiID"
            name="mobi_partner_id"
            type="text"
            class="form-control w-50"
          />
        </div>

        <div class="form-group mt-3">
          <label class="text-muted">Секретный пароль МОБИ Деньги</label>
          <input
            v-model="mobiPassword"
            name="mobi_secret_pass"
            type="text"
            autocomplete="off"
            class="form-control w-50"
          />
        </div>
      </div>

      <div class="card-body text-end">
        <button
          type="submit"
          id="save_bank_account_btn"
          class="btn btn-primary"
        >
          <span class="text" v-show="!isLoading"
            >Сохранить данные банковского счёта</span
          >
          <span class="wait" v-show="isLoading"
            ><b class="fa fa-spinner fa-pulse me-2"></b
          ></span>
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import HTTP from "../../common/http-client";
import HTTPV2 from '../../common/httpv2-client';

export default {
  data: () => ({
    mobiID: "",
    mobiPassword: "",
    companyID: null,
    isLoading: false,
  }),
  methods: {
    saveData(event) {
      console.log(event.target);
      let data = {};
      const formData = new FormData(event.target);
      for (let [key, val] of formData.entries()) {
        Object.assign(data, { [key]: val });
      }
      HTTPV2.post("company/bank_account", data)
        .then((r) => {
          boottoast.success({
            message: "Данные для МОБИ Деньги успешно сохранены",
            title: "Успешно",
            imageSrc: "/images/logo-sm.svg",
          });
          this.mobiPassword = "";
        })
        .catch((err) => {
          bootbox.dialog({
            title: "Ошибка",
            message: "Введены неверные значения",
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
  },
  mounted() {
    const request = HTTP.get("me")
      .then((response) => {
        this.companyID = response.data.company.id;
        HTTPV2.get("company/bank_account")
          .then((r) => {
            this.mobiID = r.data.mobi_partner_id;
          })
          .catch((err) => {
            if (err.response.status === 404) {
              return;
            }
          });
      })
      .catch((reject) => {
        console.log(reject);
      });
  },
  beforeDestroy() {
    this.mobiID = {};
  },
};
</script>

<style>
</style>
