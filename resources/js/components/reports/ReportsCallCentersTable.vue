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
            <!-- <tr v-for="(value, index) in callCentersData" :key="index">
                <th scope="row">{{ value.name }}</th>
                <th>{{ value.total }}</th>
            </!--> 
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
                required: false
            }
        }
    }
</script>

<style lang="sass" scoped>
.table-responsive 
    margin-top: 2em
</style>