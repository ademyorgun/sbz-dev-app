<template>
    <modal
    name="geolocation-modal"
    @before-open="beforeOpen"
    :width="'50%'"
    height="auto"
    :maxWidth="900"
    :scrollable="true"
    :maxHeight="400"
    :min-width="260"
    :adaptive="true"
  >
    <div class="close-btn" @click="$modal.hide('comments-modal')">
      <i class="voyager-x"></i>
    </div>

    <input type="text" v-model="address">
    
  </modal>
</template>

<script>
export default {
    name: 'appointmentsGeolocationModal',

    data() {
        return {
            address: ''
        }
    },


}
</script>

<style>

</style>
