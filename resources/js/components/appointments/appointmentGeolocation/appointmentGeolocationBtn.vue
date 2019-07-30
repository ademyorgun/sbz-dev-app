<template>
    <div class="btn-wrapper">
        <base-loader v-if="isLoading"></base-loader>
        <button class="btn btn-primary" @click.prevent="submit">
            <i class="voyager-location"></i>
            <!-- geolocation -->
            <span>Standort ermitteln</span>
        </button>
    </div>
</template>

<script>
import BaseLoader from '../../baseComponents/BaseLoader';

export default {
    name: 'appointmentGeolocationBtn',

    components: {
        BaseLoader
    },

    props: {
        appointmentId: {
            type: Number, 
            required: true
        },
        isLoading: {
            type: Boolean,
            required: true,
            default: false
        }
    },

    methods: {
        submit() {
            this.$emit('get-geolocation', this.appointmentId);
        }
    },

}
</script>

<style lang="sass" scoped>
.btn-wrapper 
    position: relative

    .btn
        margin: 0
</style>
