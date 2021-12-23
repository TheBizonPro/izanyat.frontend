<template>
  <div class="card-body">
    <div class="row mt-2">
      <div class="col-6">Смена пароля</div>
    </div>
    <div class="row mt-2">
      <div class="col-6">
        <b-form-group class="mb-3" label="Пароль">
          <b-form-input
            v-model="password"
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
      </div>
    </div>
  </div>
</template>

<script>
import HTTPV2 from '../../common/httpv2-client';
export default {
  data: () => ({
    isPassword: true,
    password: "",
    passwordRepeat: "",
    isLoading: false,
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
      HTTPV2.post("company/me/change_password", {
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

<style>
</style>
