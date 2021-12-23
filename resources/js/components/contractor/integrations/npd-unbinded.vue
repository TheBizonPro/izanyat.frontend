<template>
  <div class="alert alert-danger text-center">
    <h1><b class="fad fa-times text-danger"></b></h1>
    <h3>Привязка не выполнена.</h3>
    <p>
      Чтобы работать через платформу «Я Занят» необходима привязка к партнёру.
      Без привязки вы не сможете брать задачи в работу, получить оплату и
      отчитаться в ФНС о своих доходах!
    </p>
    <div class="mb-3">
      <b-button :disabled="isLoading" @click="bindTaxpayer" variant="success">
        <span v-show="isLoading" class="wait"
          ><b class="fad fa-spinner fa-pulse"></b> Пожалуйста, подождите</span
        >
        <span v-show="!isLoading"> Выполнить привязку к «Я Занят» </span>
      </b-button>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    isLoading: false,
  }),
  methods: {
    bindTaxpayer() {
      this.$store.dispatch("bindTaxpayer", {
        component: this,
      });
    },
  },
};
</script>

<style>
</style>
