<template>
    <div class="filter-form">
        <form>
            <div class="row">
                <div class="form-group  col-md-3 ">
                    <label class="control-label" for="name">Meeting Date start</label>
                    <input type="date" class="form-control " name="meeting_date" value="">
                </div>

                <div class="form-group  col-md-3 ">
                    <label class="control-label" for="name">Meeting Date end</label>
                    <input type="date" class="form-control " name="meeting_date" value="">
                </div>

                <div class="form-group  col-md-3 ">
                    <label class="control-label" for="name">Appointment Date start</label>
                    <input type="date" class="form-control " name="meeting_date" value="">
                </div>

                <div class="form-group  col-md-3 ">
                    <label class="control-label" for="name">Appointment Date end</label>
                    <input type="date" class="form-control " name="meeting_date" value="">
                </div>

                <!-- wanted expert -->
                <div class="form-group  col-md-3 ">                   
                    <label class="control-label" for="name">Wanted Expert</label>
                    <select class="form-control select2 select2-hidden-accessible" name="wanted_expert" aria-hidden="true">
                        <slot name="experts"></slot>
                    </select>
                </div>

                <!-- canton- city -->
                <div class="form-group  col-md-3 ">                   
                    <label class="control-label" for="name">Canton</label>
                    <select class="form-control select2 select2-hidden-accessible" name="wanted_expert" aria-hidden="true">
                        <slot name="cities"></slot>
                    </select>
                </div>

                <!-- user  -->
                <div class="form-group  col-md-3 ">                   
                    <label class="control-label" for="name">User</label>
                    <select class="form-control select2 select2-hidden-accessible" name="wanted_expert" aria-hidden="true">
                        <slot name="users"></slot>
                    </select>
                </div>

                <!-- Telephone number -->
                <div class="form-group  col-md-3 ">
                    <label class="control-label" for="name">Telephone Number</label>
                    <input type="number" class="form-control" name="telephone_number" step="any" placeholder="" value="">
                </div>

                <!-- Appointment ID -->
                <div class="form-group  col-md-3 ">
                    <label class="control-label" for="name">Appointment ID</label>
                    <input type="number" class="form-control" name="telephone_number" step="any" placeholder="" value="">
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

        }
    },

    methods: {

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
