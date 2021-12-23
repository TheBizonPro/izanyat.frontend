<template>
  <b-container class="px-5 mt-4" style="min-height: 50vh">
    <router-link to="/my/settings/password" class="element-custom">
      Смена пароля
      <div><b-icon icon="chevron-right"></b-icon></div>
    </router-link>
    <!-- <router-link to="/my/settings/notifications" class="element-custom">
      Уведомления
      <div><b-icon icon="chevron-right"></b-icon></div>
    </router-link> -->
  </b-container>
</template>

<script>
export default {};
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
</style>
