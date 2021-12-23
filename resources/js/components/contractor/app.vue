<template>
  <div class="container">
    <navigation class="mt-4"></navigation>
    <b-card class="pt-2 pb-2">
      <b-spinner
        v-if="$root.loading"
        style="display: block; width: 3rem; height: 3rem; margin: 0 auto"
        label="Spinning"
      ></b-spinner>
      <router-view v-else></router-view>
    </b-card>
  </div>
</template>

<script>
import navigation from "./elements/navigation.vue";

export default {
  components: { navigation },
  mounted() {
    this.$store.dispatch('getUser');
  },
};
</script>

<style>
</style>
