<template>
    <paginator :data="paginationData" @pagination-change-page="changePage"></paginator>
</template>

<script>
import paginator from "laravel-vue-pagination";

export default {
  name: "AppointmentsPaginator",

  components: {
    paginator
  },

  props: {
    paginationData: {
      type: Object
    }
  },
  methods: {
      changePage(page = 1) {
          console.log(page, 'paginator');
          this.$emit('get-results', page);
      }
  },
};
</script>

<style>
</style>
