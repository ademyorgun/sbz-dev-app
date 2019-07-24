<template>
    <button class="btn btn-primary pull-right" @click.prevent="$emit('get-geolocation')">
        <i class="voyager-location"></i>
        <span>Geolocation</span>
    </button>
</template>

<script>
export default {
    name: 'appointmentGeolocationBtn'
}
</script>

<style>

</style>
