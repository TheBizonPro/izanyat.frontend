<template>
  <div>
    <b-row>
      <b-col cols="6">
        <b-form-group class="mb-3" label="Email:">
          <b-form-input
            v-model="emailData"
            type="email"
            placeholder="Введите email"
          ></b-form-input>
        </b-form-group>
        <b-form-group class="mb-3" label="Предпочтительная категория:">
          <multiselect
            select-label="Нажмите Enter, чтобы выбрать"
            selectedLabel="Выбрано"
            deselectLabel="Нажмите Enter, чтобы удалить"
            v-model="jobCategory"
            label="name"
            track-by="id"
            placeholder="Выберите права доступа"
            :options="options"
          ></multiselect>
        </b-form-group>
        <b-form-group label="Предпочтительная категория:">
          <b-form-textarea
            id="textarea"
            v-model="aboutData"
            rows="3"
            max-rows="6"
          ></b-form-textarea>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row class="justify-content-end mt-3">
      <b-col cols="4">
        <b-button @click="hideEdit" variant="light">Отмена</b-button>
        <b-button class="ms-3" @click="saveData" variant="dark">Сохранить</b-button>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import HTTP from "../../../common/http-client";

export default {
  data: () => ({
    emailData: "",
    jobCategory: null,
    aboutData: "",
    options: [],
  }),
  props: {
    email: {
      type: String,
    },
    jobCategoryID: {
      type: Number,
    },
    about: {
      type: String,
    },
    hideEdit:{
        type: Function
    }
  },
  created() {
    this.options = this.$store.state.job_categories;
    this.emailData = this.email;
    this.aboutData = this.about;
    this.jobCategory =
      this.options.filter((item) => item.id == this.jobCategoryID)[0] || null;
  },
  methods: {
    saveData() {
      const jobID = this.jobCategory.id;
      const jobName = this.jobCategory.name;
      const email = this.emailData;
      const about = this.aboutData;
      if (email.length == 0 || !jobID) {
        return;
      }
      HTTP.post("me", {
        email: email,
        job_category_id: jobID,
        about: about,
      }).then((res) => {
          if(res.status==200){
              this.$store.state.me.job_category_id = jobID;
              this.$store.state.me.job_category_name = jobName;
              this.$store.state.me.about = about;
              this.$store.state.me.email = email;
              const newData = {
                  me: this.$store.state.me
              }
              this.$store.commit('setUser', newData);
              this.hideEdit();
          }
      });
    },
  },
};
</script>

<style>
</style>
