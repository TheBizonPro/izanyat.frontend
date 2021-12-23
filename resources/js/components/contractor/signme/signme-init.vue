<template>
  <div>
    <p class="mt-5 font-custom">
      Вы не можете подписывать документы на площадке и брать задания от
      организаций, пока не получите квалифицированную электронную подпись (КЭП).
      Если у вас есть действительная электронная подпись напишите на почту
      info@izanyat.ru
    </p>
    <p class="mt-4 font-custom">
      Мы предлагаем оформить КЭП на выгодных условиях у наших партнеров Sing.me
      Что отличает этот сервис от других? Их электронная подпись работает без
      токена на любом устройстве и готова к использованию сразу после
      подтверждения личности без дополнительных установок. Вы сможете
      подписывать любые документы в электронном виде, сохраняя их полную
      юридическую силу.
    </p>
    <b-row class="justify-content-end mt-5">
      <b-col cols="3">
        <b-button
          @click="register"
          :disabled="isLoading"
          squared
          variant="info"
        >
          <span v-show="isLoading" class="wait"
            ><b class="fad fa-spinner fa-pulse"></b> Пожалуйста, подождите</span
          >
          <span v-show="!isLoading">Получить КЭП</span>
        </b-button>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import HTTPV2 from "../../../common/httpv2-client";
export default {
  data: () => ({
    isLoading: false,
  }),
  methods: {
    register() {
      this.isLoading = true;
      HTTPV2.post("contractor/signme/register")
        .then((res) => {
          this.isLoading = false;
          this.$store.dispatch('signmeState');
          boottoast.success({
            message: res.data.message,
            title: "Успех",
            imageSrc: "/images/logo-sm.svg",
          });
        })
        .catch((err) => {
          this.$router.push({ name: 'personal', params: { isEdit: true } })
          this.isLoading = false;
          boottoast.danger({
            message: err.response.data.error,
            title: "Ошибка",
            imageSrc: "/images/logo-sm.svg",
          });
        });
    },
  },
};
</script>

<style scoped>
.font-custom {
  font-size: 18px;
  text-align: justify;
}
</style>
