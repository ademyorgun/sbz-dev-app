<template>
    <button class="btn btn-primary pull-right" @click.prevent="$emit('get-geolocation', appointmentId)">
        <i class="voyager-location"></i>
        <span>Geolocation</span>
         <!-- <span class="signingin"><span class="voyager-refresh"></span>...</span> -->
    </button>
</template>

<script>
export default {
    name: 'appointmentGeolocationBtn',

    props: {
        appointmentId: {
            type: Number, 
            required: true
        }
    }
}
</script>

<style>

</style>
