
<template>
  <div class="alert alert-success text-center">
    <h1><b class="fad fa-check-circle text-success"></b></h1>
    <h3>Привязка выполнена!</h3>
    <p>
      Вы можете работать в платформе: брать и выполнять задачи, получать оплату.
    </p>
    <div id="manually_unbind_wrapper" class="mb-3">
      <b-button :disabled="isLoading" @click="unbindTaxpayer" variant="danger">
        <span v-show="isLoading" class="wait"
          ><b class="fad fa-spinner fa-pulse"></b> Пожалуйста, подождите</span
        >
        <span v-show="!isLoading"> Выполнить отвязку от «Я Занят» </span>
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
    unbindTaxpayer() {
      this.$store.dispatch("unbindTaxpayer", {
        component: this,
      });
    },
  },
};
</script>

<style>
</style>
