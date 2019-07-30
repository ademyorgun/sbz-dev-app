<template>
  <div class="filter-form">
    <form id="form">
      <div class="row">
        <div class="form-group col-md-6">
          <!-- year -->
          <label class="control-label">Jahr</label>
          <select class="form-control" v-model="year">
            <option v-for="(year, index) in yearsToShow" :value="year" :key="index">{{year}}</option>
          </select>
        </div>
        <div class="form-group col-md-6">
          <!-- month -->
          <label class="control-label">Monat</label>
          <select class="form-control" v-model="month">
            <option v-for="(month, index) in monthsToShow" :value="month" :key="index">{{month}}</option>
          </select>
        </div>
      </div>
      <div class="row pr-2">
        <div class="form-group pull-left col-md-3 col-sm-12 toggle">
          <!-- meeting date set -->
          <label class="control-label">Termin vorhanden</label>
          <div class="toggle-button-wrapper">
            <toggle-button 
              v-model="isAgentMeetingDateSet"
              :value="false"
              :labels="{checked: 'Yes', unchecked: 'No'}"
              @change="clearForm" />
          </div>
        </div>
        <div class="form-group pull-left col-md-3 col-sm-12 toggle">
          <!-- appointment won -->
          <label class="control-label">Termin gewonnen</label>
          <div class="toggle-button-wrapper">
            <toggle-button 
              v-model="isAppointmentWon"
              :value="false"
              :labels="{checked: 'Yes', unchecked: 'No'}"
              @change="clearForm" />
          </div>
        </div>
        <button class="btn btn-light pull-right" @click.prevent="clearForm">
          <i class="voyager-trash"></i>
          <!-- clear filter -->
          <span>Abbrechen</span>
        </button>
        <button class="btn btn-primary pull-right" @click.prevent="$emit('fetch-data', $data)">
          <i class="voyager-search"></i>
          <!-- filter results -->
          <span>Ergebnisse anzeigen</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import { ToggleButton } from "vue-js-toggle-button";

export default {
  name: "ReportsFiler",

  components: {
    ToggleButton
  },

  data() {
    return {
      month: "",
      year: "",
      day: new Date().getDate(),
      isAgentMeetingDateSet: false,
      isAppointmentWon: false
    };
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
      (this.month = now.getMonth() + 1), (this.year = now.getFullYear());

      // load the equivalent data
      this.$emit("fetch-data", this.$data);
    },

    /**
     * Used to calculat successive decreasing values
     * starting from the given one until the limit + 1
     *
     * @param Int, Int
     * @return array
     */
    decreaseValue(value, limit) {
      let result = [];

      while (value != limit) {
        result.push(value);
        value = value - 1;
      }

      result = result.reverse();
      return result;
    }
  }
};
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

    .toggle
        margin-bottom: 21px
        
        .toggle-button-wrapper
            display: inline-block
            margin-left: 10px

.form-control[readonly]
    color: #76838f
    background-color: #fff !important
    background-image: none
    border: 1px solid #e4eaec

</style>
