<template>
    <button @click.prevent="openCommentsModal" class="btn btn-sm btn-primary pull-right edit">Feedback</button>
</template>

<script>
export default {
    name: 'appointmentsModalBtn',

    props: {
        appointmentId: {
            type: Number,
            required: true
        }
    },

    methods: {
        openCommentsModal() {
            this.$emit('open-comments-modal', this.appointmentId);
        }
    }
}
</script>

<style>

</style>
