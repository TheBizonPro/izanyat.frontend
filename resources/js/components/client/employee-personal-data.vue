<template>
  <div class="card-body">
    <div class="row mt-3 mb-4">
      <div class="col-6">
        <home-info class="mt-2 ms-4" :label="'ФИО'" :value="name"></home-info>
        <home-info
          class="mt-2 ms-4"
          :type="'phone'"
          :label="'Телефон'"
          :value="phone"
        ></home-info>
        <home-info
          class="mt-2 ms-4"
          :label="'Email'"
          :value="email"
        ></home-info>
        <employee-passport
          :passport="passport"
          :passportissuer="passportissuer"
          :passportdate="passportdate"
          :passportcode="passportcode"
        >
        </employee-passport>
        <employee-inn :inn="inn"></employee-inn>
        <employee-snils :snils="snils"></employee-snils>
      </div>
    </div>
  </div>
</template>

<script>
import HomeInfo from "../contractor/elements/home-info.vue";
import EmployeePassport from "./employee-passport.vue";
import EmployeeInn from "./employee-inn.vue";
import EmployeeSnils from "./employee-snils.vue";

export default {
  components: { HomeInfo, EmployeePassport, EmployeeInn, EmployeeSnils },
  props: {
    name: {
      type: String,
    },
    email: {
      type: String,
    },
    phone: {
      type: String,
    },
    passport: {
      type: String,
    },
    passportissuer: {
      type: String,
    },
    passportdate: { type: String },
    passportcode: { type: String },
    inn: { type: String },
    snils: { type: String },
  },
  methods: {},
};
</script>

<style>
</style>
