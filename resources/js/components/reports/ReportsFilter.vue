<template>
     <div class="filter-form">
    <form id="form">
      <div class="row">
        <div class="form-group col-md-6">
          <label class="control-label">Year</label>
          <select class="form-control" v-model="year" >
            <option v-for="(year, index) in yearsToShow" :value="year" :key="index" >{{year}}</option>
          </select>
        </div>
        <div class="form-group col-md-6">
          <label class="control-label">Month</label>
          <select class="form-control" v-model="month" >
            <option v-for="(month, index) in monthsToShow" :value="month" :key="index" >{{month}}</option>
          </select>
        </div>
      </div>
      <div class="row pr-2">
        <button class="btn btn-light pull-right" @click.prevent="clearForm">
          <i class="voyager-trash"></i>
          <span>Clear filter</span>
        </button>
        <button class="btn btn-primary pull-right" @click.prevent="$emit('fetch-data', $data)">
          <i class="voyager-search"></i>
          <span>Fitler results</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';

export default {
    name: 'ReportsFiler',

    components: {
        Datepicker
    },

    data() {
        return {
            month: '',
            year: '',
            day: new Date().getDate()
        }
    },

    computed: {
      yearsToShow() {
        let currentYear = new Date().getFullYear();
        return this.decreaseValue(currentYear, 2016);
      },

      monthsToShow() {
        // for some weird reason getMonth returs the month
        // starting form 0, so we have to add 1 in order
        // to accomodate for that
        let currentMonth = new Date().getMonth() + 1;
        return this.decreaseValue(currentMonth, 0);
      }
    },

    created() {
        this.clearForm();
    },

    methods: {
        clearForm() {
            // reset the values
            const now = new Date();
            // for some weird reason getMonth returs the month
            // starting form 0, so we have to add 1 in order
            // to accomodate for that
            this.month = now.getMonth() + 1 ,
            this.year = now.getFullYear()

            // load the equivalent data
            this.$emit('fetch-data', this.$data);
        },

        /**
         * Used to calculat successive decreasing values 
         * starting from the given one until the limit + 1
         * 
         * @param Int, Int
         * @return array 
         */
        decreaseValue( value, limit) {
          let result = [];

          while (value != limit) {
            result.push(value);
            value = value - 1;
          };

          result = result.reverse();
          return result;
        }
    },
}
</script>

<style  lang="sass">
.filter-form
    margin-top: 1.6em

    .btn 
        margin-left: 1em 

    .pr-2 
        padding-right: 1em
    
    .datePicker--input
        color: #76838f
        background-color: #fff !important
        background-image: none
        border: 1px solid #e4eaec

    input.form-control
        background-color: #fff !important

.form-control[readonly]
    color: #76838f
    background-color: #fff !important
    background-image: none
    border: 1px solid #e4eaec

</style>
