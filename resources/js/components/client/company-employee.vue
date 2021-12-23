<template>
  <div class="card">
    <div class="card-header">
      <h2 class="card-title"><b class="fad fa-user me-2"></b>Сотрудники</h2>
      <button v-b-modal.add-user class="ms-auto btn btn-primary">
        Добавить
      </button>
    </div>
    <div class="card-body">
      <div>
        Выбрано: {{ selected.length }}
        <button
          v-if="selected.length == 0"
          @click="selectAllRows"
          class="ms-3 btn btn-link"
        >
          Выбрать все
        </button>
        <button v-else @click="clearSelected" class="ms-3 btn btn-link">
          Отменить
        </button>

        <button @click="deleteSelectedUsers" class="ms-4 btn btn-link">
          Удалить
        </button>

        <button @click="attachSelectedUsers" class="ms-4 btn btn-link">
          Назначить роль
        </button>
      </div>
      <b-table
        striped
        bordered
        small
        hover
        ref="selectableEmployeeTable"
        :fields="fields"
        :items="items"
      >
        <template v-slot:cell(selected)="row">
          <b-form-group>
            <input type="checkbox" v-model="row.item.checked" />
          </b-form-group>
        </template>
        <template v-slot:cell(actions)="row">
          <div class="mx-auto" style="width: fit-content">
            <b-btn
              v-if="row.item.id !== signer_user_id"
              class="border-0"
              variant="default btn-xs icon-btn"
              @click="deleteUser(row.item.id)"
              v-b-tooltip.hover
            >
              <b-icon icon="trash-fill" aria-hidden="true"></b-icon
            ></b-btn>
            <b-btn
              v-if="row.item.id !== signer_user_id"
              class="border-0"
              variant="default btn-xs icon-btn"
              @click="showModal(row.item)"
              v-b-tooltip.hover
              ><b-icon icon="pencil" aria-hidden="true"></b-icon
            ></b-btn>
            <b-btn
              class="border-0"
              variant="default btn-xs icon-btn"
              @click="goToUser(row.item.id)"
              v-b-tooltip.hover
              ><b-icon icon="chevron-right" aria-hidden="true"></b-icon
            ></b-btn>
          </div>
        </template>
      </b-table>
    </div>
  </div>
</template>

<script>
import HTTPV2 from "../../common/httpv2-client";
export default {
  name: "company-employee",
  data: () => ({
    signer_user_id: null,
    fields: [
      {
        label: "",
        key: "selected",
      },
      {
        label: "Имя",
        key: "name",
      },
      {
        label: "Роль",
        key: "role",
      },
      {
        label: "Действия",
        key: "actions",
      },
    ],
    items: [],
  }),
  computed: {
    selected() {
      return this.items.filter((item) => item.checked == true);
    },
  },
  created() {
    this.$root.$refs.employeetable = this;
  },
  methods: {
    selectAllRows() {
      this.items = this.items.map((item) => ({ ...item, checked: true }));
    },
    clearSelected() {
      this.items = this.items.map((item) => ({ ...item, checked: false }));
    },
    deleteSelectedUsers() {
      const selectedUsers = this.selected;

      selectedUsers.forEach(async (user) => {
        this.deleteUser(user.id);
      });
    },
    deleteUser(id) {
      HTTPV2.delete(`company/employees/${id}`).then((res) => {
        this.items = this.items.filter((item) => item.id != id);
      });
    },
    attachRole(id) {
      HTTPV2.post(`company/roles/assign/user/${id}`).then((res) => {
        this.items = this.items.filter((item) => item.id != id);
      });
    },
    attachSelectedUsers() {
      const selectedUsers = this.selected;
      if (selectedUsers.length > 0) {
        this.$root.$refs.editusermodal.$data.users = selectedUsers.map(
          (item) => item.id
        );
        this.$bvModal.show("edit-user");
      }
    },
    getUsers() {
      const request = HTTPV2.get("company/employees").then((res) => {
        this.items = res.data.employees?.map((item) => ({
          id: item.id,
          name: `${item.lastname} ${item.firstname} ${item.patronymic}`,
          role: item.roles?.map((i) => i.name).join(",") || "",
          roleIDs: item.roles || [],
        }));
      });
    },
    showModal(row) {
      const userID = row.id;
      const roles = row.roleIDs;
      this.$root.eventbus.emit("openpemployeemodal", {
        users: [userID],
        roles: roles,
      });
      this.$root.$refs.editusermodal.showModal();
    },
    goToUser(userId) {
      window.location = "/employees/" + userId;
    },
  },
  mounted() {
    this.getUsers();
    const companyData = JSON.parse(window.localStorage.getItem("company"));
    if (companyData) {
      const signerUserId = companyData.signer_user_id;
      this.signer_user_id = signerUserId;
    }
  },
};
</script>

<style scoped>
</style>
