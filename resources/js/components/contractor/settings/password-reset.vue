<template>
  <b-container class="px-5 mt-4" style="min-height: 50vh">
    <b-row>
      <router-link to="/my/settings" class="element-custom">
        <div><b-icon icon="chevron-left"></b-icon></div>
        <div class="ms-4">Смена пароля</div>
      </router-link>
    </b-row>
    <b-row>
      <b-col cols="6">
        <b-form-group class="mb-3" label="Пароль">
          <b-form-input
            v-model="password"
            style="height: 50px; width: 75%"
            :type="isPassword ? 'password' : 'text'"
            :state="state"
          ></b-form-input>
        </b-form-group>
        <b-form-group
          class="mb-3"
          :invalid-feedback="checkPasswords"
          label="Повторите пароль"
          :state="state"
        >
          <b-form-input
            v-model="passwordRepeat"
            style="height: 50px; width: 75%"
            :type="isPassword ? 'password' : 'text'"
            :state="state"
          ></b-form-input>
          <b-button
            class="px-0"
            @click="isPassword = !isPassword"
            variant="link"
            >Показать пароль</b-button
          >
        </b-form-group>
        <b-button :disabled="isLoading" @click="savePassword" variant="primary">
          <span v-show="isLoading" class="wait"
            ><b class="fad fa-spinner fa-pulse"></b> Пожалуйста, подождите</span
          >
          <span v-show="!isLoading"> Сохранить</span>
        </b-button>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import HTTP from "../../../common/http-client";

export default {
  data: () => ({
    isPassword: true,
    password: "",
    passwordRepeat: "",
    isLoading: false
  }),
  computed: {
    state() {
      if (this.password.length === 0 && this.passwordRepeat.length === 0) {
        return null;
      }
      return this.password === this.passwordRepeat;
    },
    checkPasswords() {
      if (this.password.length < 6 && this.passwordRepeat.length < 6) {
        return "Пароль должен быть длиннее 5 символов";
      }
      return this.password !== this.passwordRepeat ? "Пароли не совпадают" : "";
    },
  },
  methods: {
    savePassword() {
      if (this.password.length === 0 && this.passwordRepeat.length === 0) {
        boottoast.danger({
          message: "Введите пароль",
          title: "Ошибка",
          imageSrc: "/images/logo-sm.svg",
        });
        return;
      }
      if (this.password.length !== this.passwordRepeat.length) {
        boottoast.danger({
          message: "Пароли не совпадают",
          title: "Ошибка",
          imageSrc: "/images/logo-sm.svg",
        });
        return;
      }
      this.isLoading = true;
      HTTP.post("change_password", {
        password: this.password,
      })
        .then((res) => {
          if (res.status === 200) {
            const data = res.data;
            boottoast.success({
              message: data?.message || "",
              title: data?.title || "Успех",
              imageSrc: "/images/logo-sm.svg",
            });
            this.isLoading = false;
          }
        })
        .catch((error) => {
          const data = error.response.data;
          boottoast.danger({
            message: data?.message || "",
            title: data?.title || "Ошибка",
            imageSrc: "/images/logo-sm.svg",
          });
          this.isLoading = false;
        });
    },
  },
};
</script>

<style scoped>
.custom-input {
  display: flex;
  height: 50px;
  width: 75%;
  border: 0;
  border-bottom: 1px solid #dbdbdb;
  padding: 20px 10px;
  font-size: 18px;
  font-weight: 600;

  text-decoration: none;
  margin-bottom: 10px;
}
.element-custom {
  display: flex;
  width: 50%;
  color: black;
  padding: 20px 10px;
  font-size: 18px;
  font-weight: 600;
  /* border-bottom: 1px solid #dbdbdb; */
  text-decoration: none;
  margin-bottom: 10px;
}
.element-custom:hover {
  text-decoration: none;
  cursor: pointer;
  background-color: gainsboro;
}
</style>

