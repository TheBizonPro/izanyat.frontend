//TODO перенести в директорию с общими UI элементами
<template>
  <div>
    <p v-if="canShowLabel" class="text-muted mb-2">
      {{ label }}
    </p>
    <span class="fw-normal" v-if="canShow">
      {{
        type == "phone" ? `+${value}` : value.length > 0 ? value : "Нет данных"
      }}
    </span>
    <b-skeleton v-else-if="countValue < 3"></b-skeleton>
    <span class="fw-normal" v-else-if="countValue == 3"> Нет данных </span>
  </div>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
    },
    value: {
      type: String,
    },
    type: {
      type: String,
      default: "text",
    },
  },
  data: {
    countValue: 0,
    valueData: "",
    interval: null,
  },
  created() {
    this.valueData = this.value;
    this.countValue = 0;
  },
  mounted() {
    this.interval = setInterval(() => {
      this.countValue++;
      if (this.countValue >= 3) {
        this.$forceUpdate();
        clearInterval(this.interval);
      }
    }, 300);
  },
  computed: {
    canShow() {
      if (this.value.length > 0) {
        return true;
      }
      if (this.value.length === 0 && this.countValue >= 3) {
        return true;
      }
      return false;
    },
    canShowLabel() {
      return typeof this.label !== "undefined";
    },
  },
};
</script>

<style>
</style>
