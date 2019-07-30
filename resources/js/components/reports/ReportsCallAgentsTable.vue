<template>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="thead-light">
            <tr>
                <th scope="col">Bentuzer</th>
                <th scope="col">Termine pro call</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(value, index) in callAgentsData" :key="index">
                <th scope="row">{{ value.name }}</th>
                <th>{{ value.total }}</th>
            </tr>
        </tbody>
    </table>
</div>
</template>

<script>
export default {
    name: 'ReportsSalesAgentsTable',
    props: {
        callAgentsData: {
            type: Array,
            required: true
        }
    }
}
</script>

<style scoped lang="sass">
.table-responsive 
    margin-top: 2em
</style>
