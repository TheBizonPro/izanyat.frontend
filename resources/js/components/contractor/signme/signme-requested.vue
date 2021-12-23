<template>
  <div>
    <div class="d-flex justify-content-center mt-5">
      <div>
        <div style="width: 3rem; height: 3rem;" class="spinner-border text-info" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <span class="ms-2 text-info text-spinner">
            обработка заявки
        </span>
      </div>
    </div>
    <p class="mt-5 font-custom">
      Мы отправили заявление на выдачу квалифицированной электронной подписи в
      сервис Sing.me Для офлайн идентификации явитесь в ближайший офис Sing.me
      или вызовите курьера
    </p>
    <p class="mt-4 font-custom">
      Если у вас есть действительная электронная подпись напишите на почту
      info@izanyat.ru
    </p>
  </div>
</template>

<script>
export default {};
</script>

<style scoped>
.font-custom {
  font-size: 18px;
  text-align: justify;
}
.text-spinner{
    top: -25%;
    position: relative;
}
</style>
