<template>
  <b-modal ref="edit-user" id="edit-user">
    <template #modal-header>
      <b-button
        @click="$bvModal.hide('edit-user')"
        class="ms-auto"
        variant="outline-light"
      >
        <b-icon class="text-dark" icon="x"></b-icon>
      </b-button>
    </template>
    <b-form-group label="Выберите роли:">
      <multiselect
        :multiple="true"
        select-label="Нажмите Enter, чтобы выбрать"
        selectedLabel="Выбрано"
        deselectLabel="Нажмите Enter, чтобы удалить"
        v-model="roles"
        label="name"
        track-by="id"
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
    users: [],
    roles: [],
    options: [],
    isLoading: false,
  }),
  mounted() {
    this.$root.eventbus.on("openpemployeemodal", (payload) => {
      this.users = payload.users;
      this.roles = payload.roles;
    });

    const request = HTTPV2.get("company/roles").then((res) => {
      this.options = res.data.roles;
    });
  },
  created() {
    this.$root.$refs.editusermodal = this;
  },
  methods: {
    hideModal() {
      this.$refs["edit-user"].hide();
    },
    showModal() {
      this.$refs["edit-user"].show();
    },
    async saveRole() {
      if (!this.users) {
        boottoast.info({
          message: "Заполните все поля",
          title: "Ошибка",
          imageSrc: "/images/logo-sm.svg",
        });
        return;
      }
      let roles = this.roles.map((item) => item.id);
      let users = this.users;
      this.isLoading = true;
      for (let item of users) {
        await HTTPV2.post(`company/roles/assign/user/${item}`, {
          roles: roles.length > 0 ? roles : [],
        });
      }
      this.isLoading = false;
      this.$root.$refs.employeetable.getUsers();
      this.hideModal();
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
