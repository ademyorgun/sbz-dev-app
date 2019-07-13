<template>
     <div class="filter-form">
    <form id="form">
      <div class="row">
        <div class="form-group col-md-6">
          <label class="control-label">Year</label>
          <Datepicker class="datePicker" input-class="form-control" v-model="year" :format="yearFormat"></Datepicker>
        </div>
        <div class="form-group col-md-6">
          <label class="control-label">Month</label>
          <Datepicker class="datePicker" input-class="form-control" v-model="month" :format="monthFormat"></Datepicker>
          <!-- <datepicker v-model="callDateStart"></datepicker> -->
        </div>
      </div>
      <div class="row pr-2">
        <button class="btn btn-light pull-right" @click.prevent="clearForm">
          <i class="voyager-trash"></i>
          <span>Clear filter</span>
        </button>
        <button class="btn btn-primary pull-right" @click.prevent="$emit('filter', $data)">
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
            dateFormat: 'yyyy MM dd',
            yearFormat: 'yyyy',
            monthFormat: 'MM',
            month: '',
            year: ''
        }
    },

    created() {
        const now = new Date();
        this.month = now.getMonth();
        this.year = now.getFullYear();
    },

    methods: {
        clearForm() {
            this.month = '',
            this.year = ''
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
