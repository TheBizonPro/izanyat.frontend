<template>
  <form @submit.prevent="sendForm" method="post" class="px-3 py-3">
    <b-col>
      <b-row>
        <b-col cols="4">
          <b-form-group class="mb-3" label="Фамилия:">
            <b-form-input
              required
              name="lastname"
              type="text"
              v-model="lastname"
              placeholder="Введите фамилию"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="4">
          <b-form-group class="mb-3" label="Имя:">
            <b-form-input
              required
              name="firstname"
              type="text"
              v-model="firstname"
              placeholder="Введите имя"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="4">
          <b-form-group class="mb-3" label="Отчество:">
            <b-form-input
              required
              type="text"
              name="patronymic"
              v-model="patronymic"
              placeholder="Введите отчество"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="4">
          <b-form-group class="mb-3" label="Дата рождения:">
            <b-form-input
              type="text"
              required
              name="birth_date"
              v-mask="'##.##.####'"
              v-model="birth_date"
              placeholder="Дата рождения"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="4">
          <b-form-group class="mb-3" label="Пол:">
            <multiselect
              name="sex"
              required
              select-label="Нажмите Enter, чтобы выбрать"
              selectedLabel="Выбрано"
              deselectLabel="Нажмите Enter, чтобы удалить"
              v-model="sex"
              label="name"
              placeholder="Выберите пол"
              :options="sexList"
            ></multiselect>
          </b-form-group>
        </b-col>
        <b-col cols="4">
          <b-form-group class="mb-3" label="Место рождения:">
            <b-form-input
              type="text"
              required
              name="birth_place"
              v-model="birth_place"
              placeholder="Место рождения"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row class="mb-3">
        <b-col cols="12">
          <b-form-group
            v-b-tooltip.hover.bottom
            :title="
              emailIsEmpty
                ? 'Это поле вы можете заполнить только один раз, для изменения данных обратитесь в тех. поддержку'
                : 'Обратитесь в тех. поддержку для изменения этих данных'
            "
            class="mb-3"
            label="Электронная почта:"
          >
            <b-form-input
              :disabled="!emailIsEmpty"
              name="email"
              v-model="email"
              type="email"
              placeholder="Введите электронную почту"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="12">
          <b-form-group class="mb-3" label="Серия и номер паспорта:">
            <b-form-input
              type="text"
              required
              name="passport"
              v-mask="'#### ######'"
              v-model="passport"
              placeholder="Серия и номер паспорта"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group class="mb-3" label="Кем выдан:">
            <b-form-input
              type="text"
              required
              name="passport_issuer"
              v-model="passport_issuer"
              placeholder="Кем выдан"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="6">
          <b-form-group class="mb-3" label="Дата выдачи паспорта:">
            <b-form-input
              type="text"
              required
              v-mask="'##.##.####'"
              name="passport_issue_date"
              v-model="passport_issue_date"
              placeholder="Дата выдачи"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="6">
          <b-form-group class="mb-3" label="Код подразделения:">
            <b-form-input
              type="text"
              required
              name="passport_code"
              v-mask="'###-###'"
              v-model="passport_code"
              placeholder="Код подразделения"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row class="mb-3">
        <b-col cols="6">
          <b-form-group
            v-b-tooltip.hover.bottom
            :title="
              innIsEmpty
                ? 'Это поле вы можете заполнить только один раз, для изменения данных обратитесь в тех. поддержку'
                : 'Обратитесь в тех. поддержку для изменения этих данных'
            "
            class="mb-3"
            label="ИНН:"
          >
            <b-form-input
              :disabled="!innIsEmpty"
              v-model="inn"
              type="text"
              name="inn"
              placeholder="ИНН"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="6">
          <b-form-group class="mb-3" label="СНИЛС:">
            <b-form-input
              required
              type="text"
              name="snils"
              v-model="snils"
              placeholder="СНИЛС"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="12">
          <b-form-group class="mb-3" label="Регион:">
            <multiselect
              required
              name="address_region"
              select-label="Нажмите Enter, чтобы выбрать"
              selectedLabel="Выбрано"
              deselectLabel="Нажмите Enter, чтобы удалить"
              v-model="address_region"
              label="name"
              placeholder="Выберите регион"
              :options="regions"
            ></multiselect>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group class="mb-3" label="Город:">
            <b-form-input
              required
              name="address_city"
              type="text"
              v-model="address_city"
              placeholder="Город"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12">
          <b-form-group class="mb-3" label="Улица:">
            <b-form-input
              required
              type="text"
              name="address_street"
              v-model="address_street"
              placeholder="Улица"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="4">
          <b-form-group class="mb-3" label="Дом:">
            <b-form-input
              required
              type="text"
              name="address_house"
              v-model="address_house"
              placeholder="Дом"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="4">
          <b-form-group class="mb-3" label="Корпус:">
            <b-form-input
              type="text"
              v-model="address_building"
              name="address_building"
              placeholder="Корпус"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="4">
          <b-form-group class="mb-3" label="Квартира:">
            <b-form-input
              required
              type="text"
              v-model="address_flat"
              name="address_flat"
              placeholder="Квартира"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row class="justify-content-end mt-5">
        <b-col cols="4">
          <b-button
            class="d-block mx-0 w-100"
            :disabled="isLoading"
            type="submit"
            variant="dark"
          >
            <span v-show="isLoading" class="wait"
              ><b class="fad fa-spinner fa-pulse"></b> Пожалуйста,
              подождите</span
            >
            <span v-show="!isLoading">Сохранить</span>
          </b-button>
        </b-col>
      </b-row>
    </b-col>
  </form>
</template>

<script>
import HTTP from "../../common/http-client";
import HTTPV2 from "../../common/httpv2-client";

export default {
  props: {
    profile: { type: Object },
  },
  data: () => ({
    isLoading: false,
    lastname: "",
    firstname: "",
    patronymic: "",
    birth_date: "",
    sex: "",
    birth_place: "",
    email: "",
    passport: "",
    passport_issuer: "",
    passport_issue_date: "",
    passport_code: "",
    inn: "",
    snils: "",
    address_region: "",
    address_city: "",
    address_street: "",
    address_house: "",
    address_building: "",
    address_flat: "",
    regions: [],
    emailIsEmpty: true,
    innIsEmpty: true,
    sexList: [
      {
        value: "m",
        name: "Мужской",
      },
      {
        value: "f",
        name: "Женский",
      },
    ],
  }),
  mounted() {
    this.$root.eventbus.on("profilechange", (payload) => {
      const {
        lastname,
        firstname,
        patronymic,
        birth_date,
        sex,
        birth_place,
        email,
        passport_series,
        passport_number,
        passport_issuer,
        passport_issue_date,
        passport_code,
        inn,
        snils,
        address_region,
        address_city,
        address_street,
        address_house,
        address_building,
        address_flat,
      } = payload.profile;

      this.lastname = lastname || "";
      this.firstname = firstname || "";
      this.patronymic = patronymic || "";
      this.birth_date = birth_date || "";

      this.sex = this.sexList.filter((item) => item.value == sex)[0] || "";

      this.birth_place = birth_place || "";
      this.email = email || "";
      this.passport = `${passport_series} ${passport_number}` || "";
      this.passport_issuer = passport_issuer || "";
      this.passport_issue_date = passport_issue_date || "";
      this.passport_code = passport_code || "";
      this.inn = inn || "";
      this.snils = snils || "";
      this.emailIsEmpty = !Boolean(this.email);
      this.innIsEmpty = !Boolean(this.inn);
      this.address_city = address_city || "";
      this.address_street = address_street || "";
      this.address_house = address_house || "";
      this.address_building = address_building || "";
      this.address_flat = address_flat || "";
      console.log(this.inn);
      this.$forceUpdate();
      HTTP.get("regions").then((res) => {
        this.regions = res.data.regions;

        this.address_region =
          this.regions.filter((item) => item.id == address_region)[0] || "";
      });
    });
  },
  methods: {
    sendForm(form) {
      const dataSend = {};
      const formData = new FormData(form.target);
      for (let [key, val] of formData.entries()) {
        Object.assign(dataSend, { [key]: val });
      }

      const passport = this.passport.split(" ");

      dataSend.sex = this.sex.value;
      dataSend.passport_series = passport[0];
      dataSend.passport_number = passport[1];
      dataSend.address_region = this.address_region.id;

      delete dataSend["passport"];

      this.isLoading = true;
      HTTPV2.patch("company/me", dataSend)
        .then((res) => {
          this.isLoading = false;
          boottoast.success({
            message: "Данные успешно обновлены",
            title: "Успех",
            imageSrc: "/images/logo-sm.svg",
          });
        })
        .catch((err) => {
          this.isLoading = false;
          boottoast.danger({
            message: err.response.data.error,
            title: "Ошибка",
            imageSrc: "/images/logo-sm.svg",
          });
        });
    },
  },
};
</script>

<style>
</style>
