<template>
    <div class="filter-form">
        <div class="test">

        </div>
        <form id="form">
            <div class="row">
                <div class="form-group col-md-3">
                    <label class="control-label">Meeting Date start</label>
                    <input type="date" class="form-control" v-model="meetingDateStart" >
                </div>

                <div class="form-group  col-md-3 ">
                    <label class="control-label">Meeting Date end</label>
                    <input type="date" class="form-control" v-model="meetingDateEnd" >
                </div>

                <div class="form-group  col-md-3 ">
                    <label class="control-label">Appointment Date start</label>
                    <input type="date" class="form-control" v-model="appointmentDateStart">
                </div>

                <div class="form-group  col-md-3 ">
                    <label class="control-label">Appointment Date end</label>
                    <input type="date" class="form-control" v-model="appointmentDateEnd">
                </div>

                <!-- wanted expert -->
                <div class="form-group  col-md-3 ">                   
                    <label class="control-label">Wanted Expert</label>
                    <select class="form-control " name="wanted_expert" aria-hidden="true">
                        <slot name="experts"></slot>
                    </select>
                </div>

                <!-- canton- city -->
                <div class="form-group  col-md-3 ">                   
                    <label class="control-label">Canton</label>
                    <select class="form-control " name="wanted_expert" aria-hidden="true">
                        <slot name="cities"></slot>
                    </select>
                </div>

                <!-- user  -->
                <div class="form-group  col-md-3 ">                   
                    <label class="control-label">User</label>
                    <select class="form-control " name="wanted_expert" aria-hidden="true">
                        <slot name="users"></slot>
                    </select>
                </div>

                <!-- Telephone number -->
                <div class="form-group  col-md-3 ">
                    <label class="control-label">Telephone Number</label>
                    <input type="number" class="form-control" name="telephone_number" step="any" placeholder="" >
                </div>

                <!-- Appointment ID -->
                <div class="form-group  col-md-3 ">
                    <label class="control-label">Appointment ID</label>
                    <input type="number" class="form-control" name="telephone_number" step="any" placeholder="" >
                </div>
            </div>
            <div class="row pr-2">
                <button class="btn btn-light pull-right">
                    <i class="voyager-trash"></i> <span>Clear filter</span>
                </button>
                <button class="btn btn-primary pull-right">
                    <i class="voyager-search"></i> <span>Fitler results</span>
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: 'AppointmentFilter',

    data() {
        return {
            meetingDateStart: '',
            meetingDateEnd: '',
            appointmentDateStart: '',
            appointmentDateEnd: ''
        }
    },

    mounted() {
        this.test();
    },

    methods: {
        test() {
            axios.post('appointments/filter')
                .then(function(response) {
                    try {
                        let table = document.querySelector('#table');
                        table.innerHTML = response.data.test;
                    } catch(e ) {
                        console.log(e)
                    }
                })
        },
    }
}
</script>

<style scoped lang="sass">
.filter-form
    margin-top: 1.6em

    .btn 
        margin-left: 1em 

    .pr-2 
        padding-right: 1em
</style>
