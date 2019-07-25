<template>
  <div :class="{ 'success': isSuccess, 'error': !isSuccess, 'card': 'modal' }">
    <div class="wrapper">
      <p>{{ message }}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: "BaseNotificationModal",

  props: {
    message: {
      type: String,
      required: true
    },

    isSuccess: {
      type: Boolean,
      required: true
    }
  }
};
</script>

<style lang="sass" scoped>
.card
    position: fixed
    top: 80px 
    right: 20px
    z-index: 10000
    box-shadow: 0px 4px 16px rgba(0,0,0,0.08)
    text-align: left
    font-size: 14px
    max-width: 300px
    width: 90%

    @media(max-width: 600px)
        top: auto
        bottom: 30px

    &.success
        background-color: #00ba13
        color: #fff

    &.error 
        background-color: #FF3C38
        color: #fff

    .wrapper 
        padding: 20px

</style>
