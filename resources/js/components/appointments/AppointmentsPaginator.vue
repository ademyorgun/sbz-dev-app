<template>
    <paginator :data="data" @pagination-change-page="changePage"></paginator>
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
    },
    initialPaginationData: {
      type: Object
    }
  },

  computed: {
    data() {
      if(this.isEmpty(this.paginationData)) {
        return this.initialPaginationData;
      } else {
        return this.paginationData;
      }
    }
  },

  methods: {
      changePage(page = 1) {
          console.log(page, 'paginator');
          this.$emit('get-results', page);
      },

      isEmpty(obj) {
          for(var key in obj) {
              if(obj.hasOwnProperty(key))
                  return false;
          }
          return true;
      }
  },
};
</script>

<style>
</style>
