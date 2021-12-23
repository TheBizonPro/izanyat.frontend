<template>
  <div class="card">
    <div class="card-header">
      <h2 class="card-title"><b class="fad fa-user me-2"></b>Настройки прав</h2>
      <button v-b-modal.add-role class="ms-auto btn btn-primary">
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

        <button @click="deleteSelectedRoles" class="ms-4 btn btn-link">
          Удалить
        </button>
      </div>
      <b-table
        small
        bordered
        hover
        ref="selectableTable"
        :select-mode="selectMode"
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
              class="border-0"
              variant="default btn-xs icon-btn"
              @click="deleteRole(row.item.id)"
              v-b-tooltip.hover
            >
              <b-icon icon="trash-fill" aria-hidden="true"></b-icon
            ></b-btn>
            <b-btn
              class="border-0"
              variant="default btn-xs icon-btn"
              @click="showModal(row.item)"
              v-b-tooltip.hover
              ><b-icon icon="pencil" aria-hidden="true"></b-icon
            ></b-btn>
          </div>
        </template>
      </b-table>
    </div>
    <changepermissionsmodal
      :role-current="roleCurrent"
    ></changepermissionsmodal>
  </div>
</template>

<script>
import changepermissionsmodal from "./change-permissions-modal.vue";
import "bootstrap-vue/dist/bootstrap-vue.css";
import PermissionTranslate from "../../common/permission-translate";
import HTTPV2 from "../../common/httpv2-client";

export default {
  components: { changepermissionsmodal },
  data: () => ({
    selectMode: "multi",
    fields: [
      {
        label: "",
        key: "selected",
      },
      {
        label: "Роль",
        key: "role",
      },
      {
        label: "Права",
        key: "permissions",
      },
      {
        label: "",
        key: "actions",
      },
    ],
    items: [],
    roleCurrent: null,
  }),
  created() {
    this.$root.$refs.companypermissiontable = this;
  },
  computed: {
    selected() {
      return this.items.filter((item) => item.checked == true);
    },
  },
  methods: {
    showModal(role) {

      const permissions = new PermissionTranslate(
        role.permissionsData
      ).translate();
      const roleID = role.id;
      const roleName = role.role;

      this.$root.eventbus.emit("openpermissionmodal", {
        permissions: permissions,
        roleID: roleID,
        name: roleName,
      });

      this.$bvModal.show("change-role");
    },
    selectAllRows() {
      this.items = this.items.map((item) => ({ ...item, checked: true }));
    },
    clearSelected() {
      this.items = this.items.map((item) => ({ ...item, checked: false }));
    },
    getRoles() {
      const request = HTTPV2.get("company/roles?withPermissions=1").then(
        (response) => {
          console.log(response.data);
          this.items = response.data.roles.map((item) => ({
            id: item.id,
            role: item.name,
            permissions: new PermissionTranslate(item.permissions)
              .translate()
              .map((item) => item?.display_name || "")
              .join(","),
            actions: "",
            permissionsData: item.permissions,
          }));
        }
      );
    },
    deleteSelectedRoles() {
      const selectedRoles = this.selected;
      console.log(selectedRoles);
      selectedRoles.forEach(async (role) => {
        this.deleteRole(role.id);
      });
    },
    deleteRole(id) {
      HTTPV2.delete(`company/roles/${id}`).then((res) => {
        this.items = this.items.filter((item) => item.id != id);
      });
    },
  },
  mounted() {
    this.getRoles();
  },
};
</script>

<style lang="scss" scoped>
@import "bootstrap/scss/_functions.scss";
@import "bootstrap/scss/_variables.scss";
@import "bootstrap/scss/_mixins.scss";
@import "bootstrap/scss/_tables.scss";
</style>
