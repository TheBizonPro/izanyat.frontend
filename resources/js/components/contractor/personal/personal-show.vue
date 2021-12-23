<template>
  <b-col>
    <b-row class="mb-3">
      <b-col cols="4">
        <home-info :label="'Имя'" :value="name"></home-info>
      </b-col>
      <b-col cols="4">
        <home-info :label="'Фамилия'" :value="lastname"></home-info>
      </b-col>
      <b-col cols="4">
        <home-info :label="'Отчество'" :value="patronymic"></home-info>
      </b-col>
    </b-row>
    <b-row class="mb-3">
      <b-col cols="4">
        <home-info :label="'Дата рождения'" :value="birthdate"></home-info>
      </b-col>
      <b-col cols="4">
        <home-info :label="'Пол'" :value="sex"></home-info>
      </b-col>
      <b-col cols="4">
        <home-info :label="'Место рождения'" :value="birthplace"></home-info>
      </b-col>
    </b-row>
    <b-row class="mb-3">
      <b-col cols="4">
        <home-info :label="'Электронная почта'" :value="email"></home-info>
      </b-col>
      <b-col cols="4">
        <home-info
          :label="'Серия и номер паспорта'"
          :value="passport"
        ></home-info>
      </b-col>
      <b-col cols="4">
        <home-info :label="'Кем выдан'" :value="passportIssuer"></home-info>
      </b-col>
    </b-row>
    <b-row class="mb-3">
      <b-col cols="4">
        <home-info :label="'Дата выдачи'" :value="passportDate"></home-info>
      </b-col>
      <b-col cols="4">
        <home-info
          :label="'Код подразделения'"
          :value="passportCode"
        ></home-info>
      </b-col>
      <b-col cols="4">
        <home-info :label="'ИНН'" :value="inn"></home-info>
      </b-col>
    </b-row>
    <b-row class="mb-3">
      <b-col cols="4">
        <home-info :label="'СНИЛС'" :value="snils"></home-info>
      </b-col>
      <b-col cols="4">
        <home-info :label="'Регион'" :value="region"></home-info>
      </b-col>
      <b-col cols="4">
        <home-info :label="'Город'" :value="city"></home-info>
      </b-col>
    </b-row>
    <b-row class="mb-3">
      <b-col cols="4">
        <home-info :label="'Улица'" :value="street"></home-info>
      </b-col>
      <b-col cols="4">
        <home-info :label="'Дом'" :value="house"></home-info>
      </b-col>
      <b-col cols="4">
        <home-info :label="'Корпус'" :value="building"></home-info>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols="4">
        <home-info :label="'Квартира'" :value="flat"></home-info>
      </b-col>
    </b-row>
  </b-col>
</template>

<script>
import HomeInfo from "../elements/home-info.vue";

export default {
  components: { HomeInfo },
  computed: {
    name() {
      return this.$store.state?.me?.firstname || "Нет данных";
    },
    lastname() {
      return this.$store.state?.me?.lastname || "Нет данных";
    },
    patronymic() {
      return this.$store.state?.me?.patronymic || "Нет данных";
    },
    birthdate() {
      return this.$store.state?.me?.birth_date || "Нет данных";
    },
    sex() {
      return (this.$store.state?.me?.sex == "m" ? "Мужской" : "Женский") || "";
    },
    birthplace() {
      return this.$store.state?.me?.birth_place || "Нет данных";
    },
    email() {
      return this.$store.state?.me?.email || "Нет данных";
    },
    passport() {
      const string = `${this.$store.state?.me?.passport_series || ""} ${
        this.$store.state?.me?.passport_number || ""
      }`;
      if (string.trim().length > 0) {
        return string;
      }
      return "Нет данных";
    },
    passportIssuer() {
      return `${this.$store.state?.me?.passport_issuer || "Нет данных"}`;
    },
    passportDate() {
      return `${this.$store.state?.me?.passport_issue_date || "Нет данных"}`;
    },
    passportCode() {
      return `${this.$store.state?.me?.passport_code || "Нет данных"}`;
    },
    inn() {
      return `${this.$store.state?.me?.inn || "Нет данных"}`;
    },
    snils() {
      return `${this.$store.state?.me?.snils || "Нет данных"}`;
    },
    region() {
      return `${this.$store.state?.me?.address_region || "Нет данных"}`;
    },
    city() {
      return `${this.$store.state?.me?.address_city || "Нет данных"}`;
    },
    street() {
      return `${this.$store.state?.me?.address_street || "Нет данных"}`;
    },
    house() {
      return `${this.$store.state?.me?.address_house || "Нет данных"}`;
    },
    building() {
      return `${this.$store.state?.me?.address_building || "Нет данных"}`;
    },
    flat() {
      return `${this.$store.state?.me?.address_flat || "Нет данных"}`;
    },
  },
};
</script>

<style>
</style>
