<template>
  <div class="card">
    <div class="card-header" style="display: block">
      <div class="row mt-2">
        <div class="d-flex col-6">
          <b-button @click="goToEmployees" class="border-0" variant="light">
            <b-icon icon="chevron-compact-left"></b-icon
          ></b-button>
          <home-info class="mt-2 ms-4" :value="name"></home-info>
        </div>
      </div>
    </div>
    <b-tabs :no-fade="false" content-class="mt-3" fill>
      <b-tab>
        <template #title>
          <div class="px-2 py-2">Персональные данные</div>
        </template>
        <employee-personal-data
          :name="fullname"
          :phone="employee.phone.toString()"
          :email="email"
          :passport="passport"
          :passportissuer="passportIssuer"
          :passportdate="passportDate"
          :passportcode="passportCode"
          :inn="inn"
          :snils="snils"
        ></employee-personal-data>
      </b-tab>
      <b-tab>
        <template #title>
          <div class="px-2 py-2">Проекты</div>
        </template>
        <employee-projects :employeeid="employeeid"></employee-projects>
      </b-tab>
      <b-tab>
        <template #title>
          <div class="px-2 py-2">Настройки</div>
        </template>
        <employee-settings :employeeid="employeeid"></employee-settings>
      </b-tab>
    </b-tabs>
  </div>
</template>

<script>
import HTTPV2 from "../../common/httpv2-client";
import EmployeePersonalData from "./employee-personal-data.vue";
import EmployeeProjects from "./employee-projects.vue";
import EmployeeSettings from "./employee-settings.vue";
import HomeInfo from "../contractor/elements/home-info.vue";

export default {
  components: {
    EmployeePersonalData,
    HomeInfo,
    EmployeeProjects,
    EmployeeSettings,
  },
  data: () => ({
    employee: null,
  }),
  props: {
    employeeid: {
      type: Number,
    },
  },
  computed: {
    email() {
      return this.employee?.email || "Не указан";
    },
    name() {
      const string = `${this.employee?.lastname || ""} ${
        this.employee?.firstname[0] || ""
      }. ${this.employee?.patronymic[0] || ""}.`
        .trim()
        .replace(".", "");
      if (string.length > 0) {
        return `${this.employee?.lastname} ${this.employee?.firstname[0]}. ${this.employee?.patronymic[0]}.`;
      }
      return "";
    },
    fullname() {
      const string = `${this.employee?.lastname || ""} ${
        this.employee?.firstname || ""
      } ${this.employee?.patronymic || ""}`
        .trim()
        .replace(".", "");
      if (string.length > 0) {
        return `${this.employee?.lastname} ${this.employee?.firstname} ${this.employee?.patronymic}`;
      }
      return "";
    },
    passport() {
      const string = `${this.employee?.passport_series || ""} ${
        this.employee?.passport_number || ""
      }`;
      if (string.trim().length > 0) {
        return `${this.employee?.passport_series} ${this.employee?.passport_number}`;
      }
      return "Не указаны";
    },
    passportIssuer() {
      const string = `${this.employee?.passport_issuer || ""}`;
      if (string.trim().length > 0) {
        return `${this.employee?.passport_issuer}`;
      }
      return "Не указан";
    },
    passportDate() {
      const string = `${this.employee?.passport_issue_date || ""}`;
      if (string.trim().length > 0) {
        return `${this.employee?.passport_issue_date}`;
      }
      return "Не указан";
    },
    passportCode() {
      const string = `${this.employee?.passport_code || ""}`;
      if (string.trim().length > 0) {
        return `${this.employee?.passport_code}`;
      }
      return "Не указан";
    },
    inn() {
      return this.employee?.inn || "Не указан";
    },
    snils() {
      return this.employee?.snils || "Не указан";
    },
  },
  mounted() {
    this.getEmployee();
  },
  methods: {
    getEmployee() {
      HTTPV2.get(`company/employees/${this.employeeid}`)
        .then((res) => {
          this.employee = res.data;
          console.log(this.employee);
        })
        .catch((err) => {
          window.location = "/employees";
        });
    },
    goToEmployees() {
      window.location = "/employees";
    },
  },
};
</script>

<style>
</style>
