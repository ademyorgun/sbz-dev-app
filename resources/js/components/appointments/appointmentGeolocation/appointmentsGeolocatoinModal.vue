<template>
    <modal
    name="geolocation-modal"
    :width="'90%'"
    height="auto"
    :maxWidth="600"
    :scrollable="true"
    :maxHeight="400"
    :adaptive="true"
  >
    <div class="close-btn" @click="$modal.hide('geolocation-modal')">
      <i class="voyager-x"></i>
    </div>
    <div class="container">
        <base-loader v-if="isLoaderActive"></base-loader>
        <div class="row form">
            <div class="form-group success-message" v-if="isSucess">
                <p>Sales visit address has been saved succesfully</p>
            </div>
            <div class="form-group error-message" v-if="isError">
                <p>{{ errorMessage }}</p>
            </div>
            <div class="form-group col-12">
                <label class="control-label">Berater Standort</label>
                <input type="text" class="form-control" v-model="address">
            </div>
            <div class="form-group col-12">
                <button class="btn btn-primary pull-right" @click.prevent="saveAddress" @keydown.enter="saveAddress">
                    <span>Save</span>
                </button>
            </div>
        </div>
    </div>
  </modal>
</template>

<script>
import BaseLoader from '../../baseComponents/BaseLoader';

export default {
    name: 'appointmentsGeolocationModal',

    components: {
        BaseLoader
    },

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
            isSucess: false,
            errorMessage: '',
            isError: false,
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
                    if(response.data.alertType == 'error') {
                        this.showErrorMessage(response.data.message);
                        setTimeout(() => {
                            this.hideErrorMessage();
                            this.$modal.hide('geolocation-modal');
                        }, 4000);
                    } else {
                        this.showSuccessMessage();
                        setTimeout(() => {
                            this.hideSuccessMessage();
                            this.$modal.hide('geolocation-modal');
                        }, 4000);
                    }
                })
                .catch(err => {
                    console.error(err);
                    this.hideLoader();
                    this.showErrorMessage('An error happend, please try again');
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
        },

        showErrorMessage(msg) {
            this.errorMessage = msg;
            this.isError = true;
        },
        
        hideErrorMessage() {
            this.isError = false;
        }
    }
}
</script>

<style scoped lang="sass">

.close-btn
    position: absolute
    top: 6px
    right: 6px
    z-index: 4
    color: #76838f
    font-size: 24px
    cursor: pointer

.container  

    .form-group
        margin-bottom: 10px

    .error-message 
        color: #FF3C38

    .success-message
        color: #087E8B

    .row
        padding: 20px

    .control-label
        font-size: 18px
        font-weight: 500
        margin-bottom: 16px

</style>
