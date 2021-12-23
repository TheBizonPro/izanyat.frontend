<template>
  <b-modal ref="assign-project" id="assign-project">
    <template #modal-header>
      <b-button
        @click="$bvModal.hide('assign-project')"
        class="ms-auto"
        variant="outline-light"
      >
        <b-icon class="text-dark" icon="x"></b-icon>
      </b-button>
    </template>
    <b-form-group label="Проекты:" label-for="input-2">
      <multiselect
        id="input-2"
        select-label="Нажмите Enter, чтобы выбрать"
        selectedLabel="Выбрано"
        deselectLabel="Нажмите Enter, чтобы удалить"
        v-model="project"
        label="name"
        track-by="id"
        placeholder="Выберите проект"
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
          @click="saveEmployee"
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
    project: null,
    options: [],
    isLoading: false,
  }),
  props: {
    employeeid: {
      type: String,
    },
  },
  mounted() {
    this.getProjects();
  },
  methods: {
    hideModal() {
      this.$refs["assign-project"].hide();
    },
    getProjects() {
      HTTPV2.get(`company/projects`).then((res) => {
        console.log(res);
        this.options = res.data.projects.map((item) => ({
          id: item.id,
          name: item.name,
        }));
      });
    },
    saveEmployee() {
      if (this.project == null) {
        boottoast.danger({
          message: " Выберите проект",
          title: "Ошибка",
          imageSrc: "/images/logo-sm.svg",
        });
        return;
      }
      HTTPV2.post(`company/projects/${this.project.id}/attachUsers`, {
        users: [this.employeeid],
      }).then((res) => {
        boottoast.success({
          message: "Проект закреплен за пользователем",
          title: "Успех",
          imageSrc: "/images/logo-sm.svg",
        });
        this.$root.eventbus.emit("attachUser");
      });
    },
  },
};
</script>

<style>
</style>
