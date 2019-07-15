<template>
    <div class="card">
        <h1>{{ ReportsTotal }}</h1>
        <p>Total of appointments for this month</p>
    </div>
</template>

<script>
export default {
    name: 'ReportsTotalCard',

    props: {
        ReportsTotal: {
            type: Number
        }
    }
}
</script>

<style scoped lang="sass">
.card
    padding: 2em
    box-shadow: 0px 4px 20px rgba(0,0,0,0.1)
    margin-top: 1.2em
</style>
