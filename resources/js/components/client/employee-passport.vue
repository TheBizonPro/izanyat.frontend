<template>
  <div>
    <div class="mt-4 ms-4 d-flex justify-content-between">
      <div class="text-muted px-0">Паспорт</div>
      <b-button @click="isShow = !isShow" variant="link" class="py-0">{{
        !isShow ? "Развернуть" : "Скрыть"
      }}</b-button>
    </div>
    <div v-if="isShow" class="mt-3 ms-4  col">
      <div class="d-flex">
        <div class="text-muted px-0">Серия и номер</div>
        <div class="ms-3 px-0">{{ passport }}</div>
      </div>
      <div class="d-flex mt-3">
        <div class="text-muted px-0">Кем выдан</div>
        <div class="ms-3 px-0">{{ passportissuer }}</div>
      </div>
      <div class="d-flex mt-3">
        <div class="text-muted px-0">Дата выдачи</div>
        <div class="ms-3 px-0">{{ passportdate }}</div>
      </div>
      <div class="d-flex mt-3">
        <div class="text-muted px-0">Код подразделения</div>
        <div class="ms-3 px-0">{{ passportcode }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    isShow: false,
  }),
  props: {
    passport: {
      type: String,
      default: "",
    },
    passportissuer: {
      type: String,
      default: "",
    },
    passportdate: {
      type: String,
      default: "",
    },
    passportcode: {
      type: String,
      default: "",
    },
  },
};
</script>

<style>
</style>
