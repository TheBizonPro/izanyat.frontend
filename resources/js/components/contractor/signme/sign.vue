<template>
  <b-container class="px-5" style="min-height: 50vh">
    <signme-init
      :open-modal="openModal"
      v-if="signmeStatus == null"
    ></signme-init>
    <signme-requested
      v-if="signmeStatus == 'request_in_progress'"
    ></signme-requested>
    <signme-requested v-if="signmeStatus == 'await_approve'"></signme-requested>
    <signme-aproved v-if="signmeStatus == 'approved'"></signme-aproved>
  </b-container>
</template>


<script>
import SignmeInit from "../signme/signme-init.vue";
import SignmeRequested from "../signme/signme-requested.vue";
import SignmeAproved from "../signme/signme-aproved.vue";

export default {
  components: { SignmeInit, SignmeRequested, SignmeAproved },
  mounted() {
    this.$store.dispatch("signmeState");
  },
  computed: {
    signmeStatus() {
      return this.$store.state?.signme?.status || null;
    },
  },
  methods: {
    openModal() {
      this.$bvModal.show("modal-signme");
    },
  },
};
</script>

<style>
</style>
