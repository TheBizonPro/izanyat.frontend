<template>
  <div class="card">
    <div class="card-header">
      <h2 class="card-title">
        <b class="fad fa-user me-2"></b>Профиль компании
      </h2>
    </div>
    <div class="card-body">
      <span class="text-danger"
        ><sup>*</sup> Для изменения ваших данных напишите в службу технической
        поддержки -
        <a href="mailto:info@izanyat.ru" target="_blank"
          >info@izanyat.ru</a
        ></span
      >
    </div>
    <div class="card-body">
      <div class="row mt-2">
        <div class="col-6">
          <div class="form-group mt-2">
            <label class="text-muted">Название</label>
            <div class="font-weight-bold">{{ company.name }}</div>
          </div>
          <div class="form-group mt-2">
            <label class="text-muted">Полное название</label>
            <div class="font-weight-bold">{{ company.full_name }}</div>
          </div>
          <div class="form-group mt-2">
            <label class="text-muted">ИНН</label>
            <div class="font-weight-bold">{{ company.inn }}</div>
          </div>
          <div class="form-group mt-2">
            <label class="text-muted">ОГРН</label>
            <div class="font-weight-bold">{{ company.ogrn }}</div>
          </div>
          <div class="form-group mt-2">
            <label class="text-muted">ОКПО</label>
            <div class="font-weight-bold">{{ company.okpo }}</div>
          </div>
        </div>
        <div class="col-6">
          <div class="form-group mt-2">
            <label class="text-muted">Email</label>
            <div class="font-weight-bold">{{ company.email }}</div>
          </div>
          <div class="form-group mt-2">
            <label class="text-muted">Телефон</label>
            <div>
              +<span class="font-weight-bold">{{ company.phone }}</span>
            </div>
          </div>
          <div class="form-group mt-2">
            <label class="text-muted">Регион</label>
            <div class="font-weight-bold">{{ company.address_region }}</div>
          </div>
          <div class="form-group mt-2">
            <label class="text-muted">Город</label>
            <div class="font-weight-bold">{{ company.address_city }}</div>
          </div>
          <div class="form-group mt-2">
            <label class="text-muted">Юридический адрес</label>
            <div class="font-weight-bold">{{ company.legal_address }}</div>
          </div>
          <div class="form-group mt-2">
            <label class="text-muted">Фактический адрес</label>
            <div class="font-weight-bold">{{ company.fact_address }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body" id="company-about-text" hidden>
      <div class="form-group mt-3">
        <label class="text-muted">О компании</label>
        <textarea v-model="about" class="form-control"></textarea>
      </div>
    </div>
    <div @click="saveAbout" class="card-body text-end" id="company-about-save" hidden>
      <button class="btn btn-primary">
        <span class="text" v-show="!isLoading">Сохранить данные профиля</span>
        <span class="wait" v-show="isLoading"
          ><b class="fa fa-spinner fa-pulse me-2"></b>Пожалуйста,
          подождите</span
        >
      </button>
    </div>
  </div>
</template>

<script>
import HTTP from "../../common/http-client";

export default {
  data: () => ({
    about: "",
    company: {},
    isLoading: false,
  }),
  mounted() {
    const request = HTTP.get("me")
      .then((response) => {
        this.company = response.data.company;
        this.about = this.company?.about || "";
      })
      .catch((reject) => {
        console.log(reject);
      });
  },
  methods: {
    saveAbout() {
      const about = this.about;
      const data = {
        about: about,
      };
      this.isLoading = true;
      const request = HTTP.post("company", data).then((response) => {
        this.isLoading = false;
        if (response.data.message) {
          boottoast.success({
            message: response.data.message,
            title: response.data.title ?? "Успешно",
            imageSrc: "/images/logo-sm.svg",
          });
        }
      });
    },
  },
  beforeDestroy() {
    this.company = {};
  },
};
</script>

<style>
</style>
