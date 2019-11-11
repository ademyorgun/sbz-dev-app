<template>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="thead-light">
            <tr>
                <th scope="col">Bentuzer</th>
                <!-- appointments per agent -->
                <th scope="col">Termine pro Sales</th>
                
                <th scope="col">Anzahl Abschlüsse Total</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(value, index) in salesAgentsData" :key="index">
                <th scope="row">{{ value.name }}</th>
                <th>{{ value.total }}</th>
                <td>{{ value.anzahlAbschlusseTotal }}</td>
            </tr>
        </tbody>
    </table>
</div>
</template>

<script>
export default {
    name: 'ReportsSalesAgentsTable',
    props: {
        salesAgentsData: {
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
