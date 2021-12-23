<template>
  <b-container class="px-5 mt-4" style="min-height: 50vh">
    <b-row>
      <router-link to="/my/integrations" class="element-custom">
        <div><b-icon icon="chevron-left"></b-icon></div>
        <div class="ms-4">Мой налог</div>
      </router-link>
    </b-row>
    <b-row class="mb-4">
      <b-col>
        <b-skeleton
          style="height: 400px !important"
          v-if="npdStatus == 'load'"
        ></b-skeleton>
        <npd-unbinded v-else-if="npdStatus == 'unbinded'"></npd-unbinded>
        <npd-wait v-else-if="npdStatus == 'wait'"></npd-wait>
        <npd-binded v-else-if="npdStatus == 'binded'"></npd-binded>
      </b-col>
    </b-row>
    <b-row class="mb-4">
      <b-col>
        <div class="card mt-3">
          <div class="card-body">
            <div class="form-group mt-3 mb-3 text-center">
              <h1><b class="fad fa-receipt text-primary"></b></h1>
              <h3>Автоматическая фискализация доходов</h3>
              <p>
                Все полученные в рамках работы в платформе «Я Занят» доходы за
                выполненные задания будут фискализированы в ПП НПД
                автоматически. Платформа автоматически сформирует чек и
                полученный вами доход будет отображаться в приложении и личном
                веб - кабинете «Мой Налог»
              </p>
            </div>
          </div>
        </div>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import NpdUnbinded from "./npd-unbinded.vue";
import NpdWait from "./npd-wait.vue";
import NpdBinded from "./npd-binded.vue";

export default {
  components: { NpdUnbinded, NpdWait, NpdBinded },
  computed: {
    npdStatus() {
      if (this.$store.state?.taxpayer_binded_to_platform == true) {
        return "binded";
      } else if (
        this.$store.state?.taxpayer_binded_to_platform == false &&
        this.$store.state?.taxpayer_bind_id != null
      ) {
        return "wait";
      } else if (
        this.$store.state?.taxpayer_binded_to_platform == false ||
        (this.$store.state?.taxpayer_binded_to_platform === null &&
          this.$store.state?.taxpayer_bind_id == null)
      ) {
        return "unbinded";
      } else {
        return "load";
      }
    },
  },
};
</script>

<style scoped>
.b-skeleton {
  height: 400px;
}
.custom-input {
  display: flex;
  height: 50px;
  width: 75%;
  border: 0;
  border-bottom: 1px solid #dbdbdb;
  padding: 20px 10px;
  font-size: 18px;
  font-weight: 600;
  text-decoration: none;
  margin-bottom: 10px;
}
.element-custom {
  display: flex;
  width: 50%;
  color: black;
  padding: 20px 10px;
  font-size: 18px;
  font-weight: 600;
  text-decoration: none;
  margin-bottom: 10px;
}
.element-custom:hover {
  text-decoration: none;
  cursor: pointer;
  color: black;
  background-color: gainsboro;
}
</style>
