<template>
  <div class="filter-form">
    <form id="form">
      <div class="row">
        <div class="form-group col-md-4">
          <label class="control-label">Anrufdatum von</label>
          <Datepicker class="datePicker" input-class="form-control" v-model="callDateStart" :format="dateFormat"></Datepicker>
          <!-- <datepicker v-model="callDateStart"></datepicker> -->
        </div>

        <div class="form-group col-md-4">
          <label class="control-label">Anrufdatum bis</label>
          <Datepicker class="datePicker" input-class="form-control" v-model="callDateEnd" :format="dateFormat"></Datepicker>
        </div>

        <div class="form-group col-md-4">
          <label class="control-label">Termindatum von</label>
          <Datepicker class="datePicker" input-class="form-control" v-model="appointmentDateStart" :format="dateFormat"></Datepicker>
        </div>

        <div class="form-group col-md-4">
          <label class="control-label">Termindatum bis</label>
          <Datepicker class="datePicker" input-class="form-control" v-model="appointmentDateEnd" :format="dateFormat"></Datepicker>
        </div>

        <!-- wanted expert -->
        <div class="form-group col-md-4">
          <label class="control-label">Gewünschter Experte</label>
          <select
            class="form-control"
            name="wanted_expert"
            aria-hidden="true"
            v-model="wantedExpert"
          >
            <option disabled value selected>Please select one</option>
            <slot name="experts"></slot>
          </select>
        </div>

        <!-- canton- city -->
        <div class="form-group col-md-4">
          <label class="control-label">Kanton</label>
          <select class="form-control" name="canton" aria-hidden="true" v-model="canton">
            <option disabled value selected>Please select one</option>
            <slot name="cities"></slot>
          </select>
        </div>

        <!-- user  -->
        <div class="form-group col-md-4">
          <label class="control-label">Agent / Berater</label>
          <select class="form-control" name="wanted_expert" aria-hidden="true" v-model="userID">
            <option disabled value selected>Please select one</option>
            <slot name="users"></slot>
          </select>
        </div>

        <!-- Telephone number -->
        <div class="form-group col-md-4">
          <label class="control-label">Telefonnummer</label>
          <input
            type="number"
            class="form-control"
            name="telephone_number"
            step="any"
            min="0" 
            oninput="validity.valid||(value='');"
            v-model="phoneNumber"
          />
        </div>

        <!-- Appointment ID -->
        <div class="form-group col-md-4">
          <label class="control-label">Termin ID</label>
          <input
            type="number"
            class="form-control"
            name="telephone_number"
            step="any"
            v-model="appointmentID"
          />
        </div>
      </div>
      <div class="row pr-2">
        <button class="btn btn-light pull-right" @click.prevent="clearForm">
          <i class="voyager-trash"></i>
          <!-- clear filter -->
          <span>Löschen</span>
        </button>
        <button class="btn btn-primary pull-right" @click.prevent="$emit('filter', $data)">
          <i class="voyager-search"></i>
          <!-- filter results -->
          <span>Suchen</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';

export default {
  name: "AppointmentFilter",

  components: {
    Datepicker,
  },

  data() {
    return {
      callDateStart: null,
      callDateEnd: null,
      appointmentDateStart: null,
      appointmentDateEnd: null,
      wantedExpert: null,
      canton: null,
      userID: null,
      phoneNumber: null,
      appointmentID: null,
      dateFormat: 'yyyy MM dd'
    };
  },

  methods: {
    clearForm() {
      this.callDateStart = "";
      this.callDateEnd = "";
      this.appointmentDateStart = "";
      this.appointmentDateEnd = "";
      this.wantedExpert = "";
      this.canton = "";
      this.user = "";
      this.phoneNumber = "";
      this.appointmentID = "";
    }
  }
};
</script>

<style lang="sass">
.filter-form
    margin-top: 1.6em

    .btn 
        margin-left: 1em 

    .pr-2 
        padding-right: 1em

    .datePicker--input , .datePicker--input[readonly]
      color: #76838f
      background-color: #fff !important
      background-image: none
      border: 1px solid #e4eaec

</style>
