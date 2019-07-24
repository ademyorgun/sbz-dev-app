<template>
    <modal
    name="geolocation-modal"
    :width="'100%'"
    height="auto"
    :maxWidth="600"
    :scrollable="true"
    :maxHeight="400"
    :adaptive="true"
  >
    <div class="close-btn" @click="$modal.hide('geolocation-modal')">
      <i class="voyager-x"></i>
    </div>
    <div class="loader" v-if="isLoaderActive">
        <div class="spinning-loader"></div>
    </div>
    <div class="container">
        <div class="row">
            <div class="form-group succes-message" v-if="isSucess">
                <p>Sales visit address has been saved succesfully</p>
            </div>
            <div class="form-group col-12">
                <label class="control-label">Berater Standort</label>
                <input type="text" class="form-control" v-model="address">
            </div>
            <div class="form-group col-12">
                <button class="btn btn-primary pull-right" @click.prevent="saveAddress">
                    <span>Save</span>
                </button>
            </div>
        </div>
    </div>
  </modal>
</template>

<script>
export default {
    name: 'appointmentsGeolocationModal',

    props: {
        appointmentId: {
            type: Number,
            required: true,
        }
    },

    data() {
        return {
            address: '',
            isLoaderActive: false,
            isSucess: false
        }
    },

    methods: {
        saveAddress() {
            const url = `/appointment/${this.appointmentId}/location`;
            const data = {
                address: this.address
            };

            this.showLoader();

            axios
                .put(url, data)
                .then(response => {
                    this.hideLoader();
                    this.showSuccessMessage();
                    setTimeout(() => {
                        this.hideSuccessMessage();
                        this.$modal.hide('geolocation-modal');
                    }, 2000);
                })
                .catch(err => {
                    this.hideLoader();
                    console.log(err)
                })
        },

        showLoader() {
            this.isLoaderActive = true;
        },

        hideLoader() {
            this.isLoaderActive = false;
        },

        showSuccessMessage() {
            this.isSucess = true;
        },

        hideSuccessMessage() {
            this.isSucess = false;
        }
    }
}
</script>

<style scoped lang="sass">

.close-btn
    position: absolute
    top: 0
    right: 0
    z-index: 4
    color: #76838f
    font-size: 24px
    cursor: pointer

.container  

    .row
        padding: 20px

    .control-label
        font-size: 18px
        font-weight: 500
        margin-bottom: 16px

.loader
    position: absolute
    top: 0
    left: 0
    right: 0 
    bottom: 0
    z-index: 100
    display: flex
    justify-content: center
    align-items: center
    background-color: rgba(255,255,255,0.5)

    &::after
        background-color: transparent

    .spinning-loader 
        width: 30px
        height: 30px
        border: 5px solid rgba(29, 161, 242, 0.2)
        border-left-color: rgb(29, 161, 242)
        border-radius: 50%
        background: transparent
        animation-name: rotate-s-loader
        animation-iteration-count: infinite
        animation-duration: 1s
        animation-timing-function: linear
        position: relative
        
@keyframes rotate-s-loader 
    from 
        transform: rotate(0)
    to 
        transform: rotate(360deg)

</style>
