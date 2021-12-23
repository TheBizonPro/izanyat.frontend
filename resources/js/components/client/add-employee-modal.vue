<template>
  <b-modal ref="add-user" id="add-user">
    <template #modal-header>
      <b-button
        @click="$bvModal.hide('add-user')"
        class="ms-auto"
        variant="outline-light"
      >
        <b-icon class="text-dark" icon="x"></b-icon>
      </b-button>
    </template>
    <b-form-group label="Имя:">
      <b-form-input v-model="form.firstname" required></b-form-input>
    </b-form-group>
    <b-form-group label="Фамилия:">
      <b-form-input v-model="form.lastname" required></b-form-input>
    </b-form-group>
    <b-form-group label="Отчество:">
      <b-form-input v-model="form.patronymic" required></b-form-input>
    </b-form-group>
    <b-form-group label="Номер телефона:">
      <b-form-input
        v-mask="'+7 (###) ###-##-##'"
        v-model="form.phone"
        required
      ></b-form-input>
    </b-form-group>
    <b-form-group label="Выберите роли:">
      <multiselect
        :multiple="true"
        id="input-2"
        select-label="Нажмите Enter, чтобы выбрать"
        selectedLabel="Выбрано"
        deselectLabel="Нажмите Enter, чтобы удалить"
        v-model="form.roles"
        label="name"
        placeholder="Выберите права доступа"
        :options="options"
      ></multiselect>
    </b-form-group>
    <template #modal-footer>
      <div class="ms-a">
        <b-button variant="light" size="md" @click="hideModal">
          Закрыть
        </b-button>

        <b-button
          :disabled="isLoading"
          variant="primary"
          size="md"
          @click="saveRole"
        >
          Сохранить
          <b-spinner
            v-show="isLoading"
            class="ms-3"
            variant="light"
            label="Spinning"
          ></b-spinner>
        </b-button>
      </div>
    </template>
  </b-modal>
</template>

<script>
import HTTPV2 from "../../common/httpv2-client";
export default {
  data: () => ({
    form: {},
    options: [],
    isLoading: false,
  }),
  mounted() {
    const request = HTTPV2.get("company/roles").then((res) => {
      this.options = res.data.roles;
    });
  },
  methods: {
    hideModal() {
      this.$refs["add-user"].hide();
    },
    saveRole() {
      const form = this.form;
      if (
        !form.firstname ||
        !form.lastname ||
        !form.patronymic ||
        !form.phone
      ) {
        boottoast.info({
          message: "Заполните все поля",
          title: "Ошибка",
          imageSrc: "/images/logo-sm.svg",
        });
        return;
      }
      let roles = form.roles?.map((item) => item.id) || [];
      this.isLoading = true;
      const request = HTTPV2.post("company/employees", {
        firstname: form.firstname,
        lastname: form.lastname,
        patronymic: form.patronymic,
        phone: form.phone,
      })
        .then((res) => {
          const employee = res.data.employee;

          this.isLoading = false;
          boottoast.info({
            message: "Пользовать создан",
            title: "Успешно",
            imageSrc: "/images/logo-sm.svg",
          });
          if (roles.length == 0) {
            this.hideModal();
            this.$root.$refs.employeetable.getUsers();
          }
          if (roles.length > 0) {
            this.isLoading = true;
            const req = HTTPV2.post(
              `company/roles/assign/user/${employee.id}`,
              {
                roles: roles.length > 0 ? roles : [],
              }
            ).then((r) => {
              this.isLoading = false;
              boottoast.info({
                message: "Роль успешно привязана",
                title: "Успешно",
                imageSrc: "/images/logo-sm.svg",
              });
              this.$root.$refs.employeetable.getUsers();
              this.hideModal();
            });
          }
        })
        .catch((error) => {
          this.isLoading = false;
        });
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
</style>
