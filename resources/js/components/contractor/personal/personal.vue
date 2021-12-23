<template>
  <b-container class="px-5 mt-3" style="min-height: 50vh">
    <div class="d-flex justify-content-end mb-2">
      <b-button @click="isEdit = !isEdit" variant="link">
        {{ !isEdit ? "Редактировать" : "Отмена" }}
      </b-button>
    </div>
    <personal-show v-if="!isEdit"></personal-show>
    <personal-edit v-else></personal-edit>
  </b-container>
</template>

<script>
import PersonalShow from "../personal/personal-show.vue";
import PersonalEdit from "../personal/personal-edit.vue";

export default {
  components: {
    PersonalShow,
    PersonalEdit,
  },
  data: () => ({
    isEdit: false,
  }),
  mounted() {
    const isEdit = this.$route.params?.isEdit || false;
    if (isEdit) {
      this.isEdit = isEdit;
    }
  },
};
</script>

<style>
</style>
