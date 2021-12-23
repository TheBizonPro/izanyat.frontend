<template>
  <b-modal ref="add-role" id="add-role">
    <template #modal-header>
      <b-button
        @click="$bvModal.hide('add-role')"
        class="ms-auto"
        variant="outline-light"
      >
        <b-icon class="text-dark" icon="x"></b-icon>
      </b-button>
    </template>
    <b-form-group label="Назание роли:" label-for="input-1">
      <b-form-input id="input-1" v-model="form.name" required></b-form-input>
    </b-form-group>
    <b-form-group label="Права доступа:" label-for="input-2">
      <multiselect
        :multiple="true"
        id="input-2"
        select-label="Нажмите Enter, чтобы выбрать"
        selectedLabel="Выбрано"
        deselectLabel="Нажмите Enter, чтобы удалить"
        v-model="form.permissions"
        label="display_name"
        track-by="name"
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
import PermissionTranslate from "../../common/permission-translate";
export default {
  data: () => ({
    form: {},
    options: [],
    isLoading: false,
  }),
  mounted() {
    const request = HTTPV2.get("company/permissions").then((res) => {
      this.options = new PermissionTranslate(res.data.permissions).translate().filter(item=>typeof item !== 'undefined');
      console.log(this.options);
    });
  },
  methods: {
    hideModal() {
      this.$refs["add-role"].hide();
    },
    saveRole() {
      const form = this.form;
      if (!form.name && (!form.permissions || form.permissions.length == 0)) {
        boottoast.info({
          message: "Заполните все поля",
          title: "Ошибка",
          imageSrc: "/images/logo-sm.svg",
        });
        return;
      }
      this.isLoading = true;
      let permissions = form.permissions.map((item) => item.name);
      const request = HTTPV2.post("company/roles", {
        name: form.name,
      }).then((res) => {
        const role = res.data.role;
        const req = HTTPV2.post(`/company/roles/${role.id}/assign/role`, {
          permissions: permissions,
        }).then((r) => {
          this.isLoading = false;
          boottoast.info({
            message: "Роль создана",
            title: "Успешно",
            imageSrc: "/images/logo-sm.svg",
          });
          this.$root.$refs.companypermissiontable.getRoles();
          this.hideModal();
        });
      });
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
</style>
