<template>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="thead-light">
            <tr>
                <th scope="col">Bentuzer</th>
                <th scope="col">Abschluss</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(value, name, index) in usersData" :key="index">
                <th scope="row">{{ name }}</th>
                <td>{{ value }}</td>
            </tr>
        </tbody>
    </table>
</div>
</template>

<script>
export default {
    name: 'ReportsUsersTable',
    props: {
        usersData: {
            type: Object,
            required: true
        }
    }
}
</script>

<style scoped lang="sass">
.table-responsive 
    margin-top: 1em

</style>
