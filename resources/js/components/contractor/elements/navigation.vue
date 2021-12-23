<template>
  <div>
    <b-row class="mb-4 justify-content-center">
      <b-col cols="11">
        <h1 v-if="userName.length > 0">{{ userName }}</h1>
        <b-skeleton width="35%" height="30px" v-else></b-skeleton>
      </b-col>
    </b-row>
    <b-row class="justify-content-center">
      <b-col cols="9">
        <b-button-group style="width: 100%">
          <b-button squared to="/my" variant="light" style="height: 50px"
            >Общая информация</b-button
          >
          <b-button squared to="/my/personal" variant="light" style="height: 50px"
            >Персональные данные</b-button
          >
          <b-button squared to="/my/sign" variant="light" style="height: 50px"
            >Электронная подпись</b-button
          >
          <b-button squared to="/my/bank" variant="light" style="height: 50px"
            >Платежные реквизиты</b-button
          >
          <b-button
            squared
            to="/my/settings"
            variant="light"
            style="height: 50px"
            >Настройки</b-button
          >
          <b-button
            squared
            to="/my/integrations"
            variant="light"
            style="height: 50px"
            >Интеграции</b-button
          >
        </b-button-group>
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  computed: {
    userName() {
      return this.$store.state?.me?.name || "";
    },
  },
};
</script>

<style scoped>
.router-link-exact-active {
  background-color: gainsboro;
}
</style>
