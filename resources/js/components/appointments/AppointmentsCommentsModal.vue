<template>
  <modal
    name="comments-modal"
    @before-open="beforeOpen"
    :width="'90%'"
    height="auto"
    :maxWidth="1000"
    :scrollable="true"
    :maxHeight="400"
    :adaptive="true"
  >
    <div class="close-btn" @click="$modal.hide('comments-modal')">
      <i class="voyager-x"></i>
    </div>

    <appointment-comments :appointment-id="appointmentId"></appointment-comments>
  </modal>
</template>

<script>
import AppointmentComments from "./appointmentsComments/AppointmentComments";

export default {
  name: "appointmentsCommentsModal",

  components: {
    AppointmentComments
  },

  data() {
    return {
      appointmentId: 0
    };
  },

  created() {
    this.$emit('set-appoitment-id', this.appointmentId);
  },

  methods: {
    beforeOpen(event) {
      this.appointmentId = event.params;
    }
  }
};
</script>

<style lang="sass" scoped>

.close-btn
    position: absolute
    top: 0
    right: 0
    z-index: 4
    color: #76838f
    font-size: 24px
    cursor: pointer
</style>
