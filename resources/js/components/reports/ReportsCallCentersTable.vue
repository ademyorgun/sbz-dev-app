<template>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="thead-light">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Total appointments</th>
                <th scope="col">Total won appointments</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(value, index) in callCentersData" :key="index">
                <th scope="row">{{ value.name }}</th>
                <th scope="row">{{ value.totalAppointments }}</th>
                <th scope="row">{{ value.wonAppointments }}</th>
            </tr>
        </tbody>
    </table>
</div>
</template>

<script>
    export default {
        name: 'ReportsCallCentersTable',

        props: {
            callCentersData: {
                type: Array,
                required: true
            }
        }
    }
</script>

<style lang="sass" scoped>
.table-responsive 
    margin-top: 2em
</style>