<template>
  <b-container class="px-5 mt-4" style="min-height: 50vh">
    <b-row>
      <router-link to="/my/bank" class="element-custom">
        <div><b-icon icon="chevron-left"></b-icon></div>
        <div class="ms-4">Карты</div>
      </router-link>
    </b-row>
    <b-row>
      <b-col cols="6">
        <b-form-group label="Введите номер карты">
          <b-form-input
            v-mask="'#### #### #### ####'"
            style="height: 50px; width: 75%"
            v-model="cardNumber"
          ></b-form-input>
        </b-form-group>

        <b-button
          :disabled="isLoading"
          @click="saveCard"
          class="mt-4"
          block
          variant="primary"
        >
          <span v-show="isLoading" class="wait"
            ><b class="fad fa-spinner fa-pulse"></b> Пожалуйста, подождите</span
          >
          <span v-show="!isLoading"> Сохранить</span>
        </b-button>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import HTTPV2 from "../../../common/httpv2-client";

export default {
  data: () => ({
    cardNumber: "",
    isLoading: false,
  }),
  mounted() {
    HTTPV2.get("contractor/bank_account").then((response) => {
      this.cardNumber = response.data.bank_account.card_number;
      this.cardNumber = this.cardNumber.match(/.{1,4}/g)?.join(" ") || "";
    });
  },
  methods: {
    saveCard() {
      const cardNumber = this.cardNumber
        ?.replace(" ", "")
        ?.replace(" ", "")
        ?.replace(" ", "");
      this.isLoading = true;
      HTTPV2.post("contractor/bank_account", {
        card_number: cardNumber,
      }).then((response) => {
        this.isLoading = false;
        if (response.status == 200) {
          boottoast.success({
            message: "Данные карты успешно сохранены",
            title: "Успешно",
            imageSrc: "/images/logo-sm.svg",
          });
        }
      });
    },
  },
};
</script>

<style scoped>
.element-custom {
  display: flex;
  width: 50%;
  color: black;
  padding: 20px 10px;
  font-size: 18px;
  font-weight: 600;
  /* border-bottom: 1px solid #dbdbdb; */
  text-decoration: none;
  margin-bottom: 10px;
}
.element-custom:hover {
  text-decoration: none;
  cursor: pointer;
  background-color: gainsboro;
}
</style>
