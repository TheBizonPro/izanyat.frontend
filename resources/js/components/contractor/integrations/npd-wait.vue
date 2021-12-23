<template>
  <div class="alert alert-info text-center">
    <h3>Необходимо подтвердить привязку в «Мой Налог»</h3>
    <p>
      Для связки вашего аккаунта самозанятого с платформой «Я Занят» необходимо
      открыть мобильное приложение или веб-сервис «Мой Налог» и предоставить
      доступ сервису «Я Занят». Настройки → Партнёры → Разрешить действия
      партнёру «Я Занят»
    </p>
    <p>
      После этого подождите несколько минут, статус вашей привязки к партнеру
      будет обновлен на этой странице.
    </p>
    <div class="mb-3">
      <b-button :disabled="isLoading" @click="checkBind" variant="success">
        <span v-show="isLoading" class="wait"
          ><b class="fad fa-spinner fa-pulse"></b> Пожалуйста, подождите</span
        >
        <span v-show="!isLoading">Проверить статус привязки</span>
      </b-button>
      <!-- <b-button class="ms-2"  @click="cancelBind" variant="danger">
        Отменить привязку
      </b-button> -->
    </div>
    <div class="mb-3"></div>
  </div>
</template>

<script>
export default {
  data: () => ({
    isLoading: false,
  }),
  methods: {
    cancelBind() {
      this.$store.dispatch("cancelBind");
    },
    checkBind() {
      this.$store.dispatch("checkBind", {
        component: this,
      });
    },
  },
};
</script>

<style>
</style>
