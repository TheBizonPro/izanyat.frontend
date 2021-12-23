<template>
  <b-container class="px-5 mt-4" style="min-height: 50vh">
    <router-link to="/my/integrations/npd" class="element-custom">
      <div>
        Мой налог
        <span v-if="npdStatus == 'binded'" class="ms-3 text-success"
          >Привязка выполнена</span
        >
        <span v-else-if="npdStatus == 'unbinded'" class="ms-3 text-weak"
          >Привязка не выполнена</span
        >
       </div>
      <div><b-icon icon="chevron-right"></b-icon></div>
    </router-link>
  </b-container>
</template>

<script>
export default {
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
        this.$store.state?.taxpayer_binded_to_platform == false &&
        this.$store.state?.taxpayer_bind_id == null
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
.element-custom {
  display: flex;
  justify-content: space-between;
  width: 100%;
  color: black;
  padding: 20px 10px;
  font-size: 18px;
  font-weight: 600;
  border-bottom: 1px solid #dbdbdb;
  text-decoration: none;
  margin-bottom: 10px;
}
.element-custom:hover {
  text-decoration: none;
  cursor: pointer;
  background-color: gainsboro;
}
.text-weak {
  font-size: 18px;
  font-weight: 300 !important;
  color: #c5c5c5;
  font-style: normal;
  font-weight: normal;
}
.text-success {
  font-size: 18px;
  font-weight: 300 !important;
  color: #a4e2b9;
  font-style: normal;
  font-weight: normal;
}
</style>
