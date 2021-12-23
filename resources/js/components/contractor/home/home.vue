<template>
  <b-container class="px-5" style="min-height: 50vh">
    <b-row class="justify-content-end">
      <b-col cols="2">
        <b-button @click="showEdit = !showEdit" variant="link">
          Редактировать
        </b-button>
      </b-col>
    </b-row>
    <b-row class="mb-5">
      <b-col cols="6">
        <home-info class="mb-3" :label="'ФИО'" :value="name"></home-info>
        <home-info
          :type="'phone'"
          :label="'Телефон'"
          :value="phone"
        ></home-info>
      </b-col>
      <b-col cols="6"></b-col>
    </b-row>
    <b-row v-if="!showEdit">
      <b-col cols="6">
        <home-info class="mb-3" :label="'Email'" :value="email"></home-info>
        <home-info
          class="mb-3"
          :label="'Предпочтительная категория'"
          :value="jobName"
        ></home-info>
        <home-info :label="'О себе'" :value="about"></home-info>
      </b-col>
    </b-row>
    <contractor-edit
      v-else
      :hideEdit="hideEdit"
      :email="email"
      :jobCategoryID="$store.state.me.job_category_id"
      :about="about"
    ></contractor-edit>
  </b-container>
</template>

<script>
import HTTP from "../../../common/http-client";
import HomeInfo from "../elements/home-info.vue";
import ContractorEdit from "./contractor-edit.vue";

export default {
  components: { HomeInfo, ContractorEdit },
  data: () => ({
    showEdit: false,
  }),
  computed: {
    name() {
      return this.$store.state?.me?.name || "";
    },
    email() {
      return this.$store.state?.me?.email || "";
    },
    phone() {
      return this.$store.state?.me?.phone.toString() || "";
    },
    about() {
      return this.$store.state?.me?.about || "";
    },
    jobName() {
      return this.$store.state?.me?.job_category_name || "";
    },
  },
  methods: {
    hideEdit() {
      this.showEdit = false;
    },
  },
  created() {
    HTTP.get("job_categories").then((response) => {
      const categories = response.data.job_categories;
      this.$store.commit("setCategories", categories);
    });
  },
};
</script>
