<template>
  <div>
    <div class="mt-4 ms-4 d-flex justify-content-between">
      <div class="text-muted px-0">СНИЛС</div>
      <b-button @click="isShow = !isShow" variant="link" class="py-0">{{
        !isShow ? "Развернуть" : "Скрыть"
      }}</b-button>
    </div>
    <div v-if="isShow" class="mt-3 ms-4 col">
      <div class="d-flex">
        <div class="px-0">{{ snils || 'Не указан' }}</div>
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
    snils: { type: String },
  },
};
</script>

<style>
</style>
