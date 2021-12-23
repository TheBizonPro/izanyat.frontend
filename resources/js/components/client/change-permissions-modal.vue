<template>
  <b-modal ref="change-role" id="change-role">
    <template #modal-header>
      <b-button
        @click="$bvModal.hide('change-role')"
        class="ms-auto"
        variant="outline-light"
      >
        <b-icon class="text-dark" icon="x"></b-icon>
      </b-button>
    </template>
    <b-form-group label="Назание роли:">
      <b-form-input
        class="mb-2"
        v-model="name"
        placeholder="Название роли"
      ></b-form-input>
    </b-form-group>
    <b-form-group label="Права доступа:" label-for="input-2">
      <multiselect
        :multiple="true"
        label="display_name"
        track-by="name"
        select-label="Нажмите Enter, чтобы выбрать"
        selectedLabel="Выбрано"
        deselectLabel="Нажмите Enter, чтобы удалить"
        v-model="permissions"
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
    options: [],
    isLoading: false,
    roleID: null,
    permissions: [],
    name: "",
  }),
  props: {
    roleCurrent: null,
  },
  created() {
    this.$root.$refs.changepermissionmodal = this;
  },
  mounted() {
    this.$root.eventbus.on("openpermissionmodal", (payload) => {
      this.permissions = payload.permissions;
      this.roleID = payload.roleID;
      this.name = payload.name;
    });

    const request = HTTPV2.get("company/permissions").then((res) => {
      this.options = new PermissionTranslate(res.data.permissions).translate().filter(item=>typeof item !== 'undefined');
    });
  },
  methods: {
    hideModal() {
      this.$refs["change-role"].hide();
    },
    saveRole() {
      if (
        !this.permissions ||
        this.permissions.length == 0 ||
        this.name.length == 0
      ) {
        boottoast.info({
          message: "Заполните все поля",
          title: "Ошибка",
          imageSrc: "/images/logo-sm.svg",
        });
        return;
      }
      const permissions = this.permissions.map((i) => i.name);
      this.isLoading = true;

      HTTPV2.patch(`company/roles/${this.roleID}`, {
        name: this.name,
      }).then((res) => {
        const req = HTTPV2.post(`/company/roles/${this.roleID}/assign/role`, {
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
