<template>
  <div class="card-body">
    <b-button :disabled="cantDelete" variant="danger" @click="deleteUser"
      >Удалить пользователя</b-button
    >
  </div>
</template>

<script>
import HTTPV2 from "../../common/httpv2-client";

export default {
  data: () => ({
    signer_user_id: null,
  }),
  props: {
    employeeid:{
        default:null
    }
  },
  computed: {
    cantDelete() {
      console.log(Number(this.signer_user_id) === Number(this.employeeid));
      return Number(this.signer_user_id) === Number(this.employeeid);
    },
  },
  methods: {
    deleteUser() {
      HTTPV2.delete(`company/employees/${this.employeeid}`).then((res) => {
        window.location = "/employees";
      });
    },
  },
  created() {
    const companyData = JSON.parse(window.localStorage.getItem("company"));
    if (companyData) {
      const signerUserId = companyData.signer_user_id;
      this.signer_user_id = signerUserId;
    }
  },
};
</script>

<style>
</style>
