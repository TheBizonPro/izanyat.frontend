<template>
  <div class="card-body">
    <div class="d-flex mb-3 justify-content-end">
      <b-button @click="showModal" class="ms-a" variant="success">
        Назаначить на проект
      </b-button>
    </div>
    <b-table
      small
      bordered
      hover
      ref="selectableTable"
      :fields="fields"
      :items="items"
      ><template v-slot:cell(actions)="row">
        <div class="mx-auto" style="width: fit-content">
          <b-btn
            class="border-0"
            variant="default btn-xs icon-btn"
            @click="deleteFromProject(row.item.id)"
            v-b-tooltip.hover
          >
            <b-icon icon="trash-fill" aria-hidden="true"></b-icon
          ></b-btn>
        </div>
      </template>
    </b-table>
  </div>
</template>

<script>
import HTTPV2 from "../../common/httpv2-client";
export default {
  data: () => ({
    fields: [
      {
        label: "Проект",
        key: "name",
      },
      {
        label: "Действия",
        key: "actions",
      },
    ],
    items: [],
  }),
  mounted() {
    this.getProjects();
    this.$root.eventbus.on("attachUser", (payload) => {
      this.getProjects();
    });
  },
  props: {
    employeeid: {
      type: Number,
    },
  },
  methods: {
    getProjects() {
      HTTPV2.get(`company/projects?employee_id=${this.employeeid}`).then(
        (res) => {
          this.items = res.data.projects.map((item) => ({
            id: item.id,
            name: item.name,
          }));
        }
      );
    },
    showModal() {
      this.$bvModal.show("assign-project");
    },
    deleteFromProject(id) {
      HTTPV2.post(`company/projects/${id}/detachUser`, {
        employee_id: this.employeeid,
      }).then((res) => {
        console.log(res);
        this.getProjects();
      });
    },
  },
};
</script>

<style>
</style>
