import Vue from "vue";
require("./../bootstrap");

import AppointmentFilter from "../components/appointments/AppointmentsFilter.vue";
import AppointmentsPaginator from "../components/appointments/AppointmentsPaginator.vue";
import AppointmentsComments from "../components/appointments/appointmentsComments/AppointmentComments.vue";
import AppointmentsModalBtn from "../components/appointments/AppointmentsModalBtn.vue";
import AppointmentsCommentsModal from "../components/appointments/AppointmentsCommentsModal.vue";
import AppointmentsGeolocationBtn from "../components/appointments/appointmentGeolocation/appointmentGeolocationBtn.vue";
import AppointmentsGeolocationModal from "../components/appointments/appointmentGeolocation/appointmentsGeolocatoinModal.vue";
import BaseNotificationModal from "../components/baseComponents/BaseNotificationModal.vue";
import VModal from "vue-js-modal";

Vue.config.productionTip = false;

Vue.use(VModal);
const app = new Vue({
    el: "#app",

    components: {
        AppointmentFilter,
        AppointmentsPaginator,
        AppointmentsComments,
        AppointmentsModalBtn,
        AppointmentsCommentsModal,
        AppointmentsGeolocationBtn,
        AppointmentsGeolocationModal,
        BaseNotificationModal
    },

    data: {
        filterData: {},
        paginationData: {},
        isResultsFiltered: false,
        pos: {},
        googleMapAPI: "AIzaSyCdw_S7lZML8VVa7qppO6UsVjYcwinCCPk",
        appointmentId: 0,
        address: "",
        isSavingGeolocation: false,
        isNotificationModalOn: false,
        isGeoSavedSuccess: false,
        responseMessage: ''
    },

    computed: {},

    methods: {
        /**
         *
         * @param {*} data
         * @param {*} page
         */
        getResults(data, page = 1) {
            this.filterData = data;
            axios
                .post("appointments/filter?page=" + page, data)
                .then(response => {
                    try {
                        // update the table
                        let table = document.querySelector("#table");
                        table.innerHTML = response.data.table;

                        this.paginationData = response.data.dataTypeContent;
                        this.isResultsFiltered = true;
                    } catch (e) {
                        console.warn(e);
                    }
                });
        },

        /**
         *
         * @param {*} page
         */
        paginatorChangePage(page) {
            axios
                .post("appointments/filter?page=" + page, this.filterData)
                .then(response => {
                    try {
                        // update the table
                        let table = document.querySelector("#table");
                        table.innerHTML = response.data.table;

                        this.paginationData = response.data.dataTypeContent;
                        this.isResultsFiltered = true;
                    } catch (e) {
                        console.warn(e);
                    }
                });
        },

        /**
         *
         * @param {*} appointmentId
         */
        openCommentsModal(appointmentId) {
            this.$modal.show("comments-modal", appointmentId);
        },

        /**
         * Get the user location
         *
         *
         */
        getGeolocation(appointmentId) {
            this.appointmentId = appointmentId;
            this.isSavingGeolocation = true;
            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                try {
                    navigator.geolocation.getCurrentPosition(position => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        this.pos = pos;
                        this.getGoogleMapGeo(pos);
                    });
                } catch(e) {
                    console.error(e);
                    this.isSavingGeolocation = false;
                };
            } else {
                // Browser doesn't support Geolocation
                // so we open the modal to enter the location as text
                this.showNotificationModal(false, "Geolocation is not supported by this browser");
                this.$modal.show("geolocation-modal");
                this.isSavingGeolocation = false;
            }
        },

        /**
         *
         * @param {*} pos
         */
        getGoogleMapGeo(pos) {
            try {
                const point = new google.maps.LatLng(pos.lat, pos.lng);
                // var point = new google.maps.LatLng(38.41054600530499, -112.85153749999995);
                const Geocoder = new google.maps.Geocoder();
                Geocoder.geocode({ latLng: point }, (results, status) => {
                    if (status !== google.maps.GeocoderStatus.OK) {
                        // we open the modal to enter the location as text
                        this.$modal.show("geolocation-modal");
                    }
                    // This is checking to see if the Geoeode Status is OK before proceeding
                    if (status == google.maps.GeocoderStatus.OK) {
                        this.address = results[0].formatted_address;
                        this.saveGeolocation(results[0].formatted_address);
                    }
                });
            } catch (e) {
                this.isSavingGeolocation = false;
                this.showNotificationModal(false, 'An error happened trying to get your current location');
            }
        },

        /**
         *
         */
        saveGeolocation(address) {
            const url = `/appointment/${this.appointmentId}/location`;
            const data = {
                address: address
            };
            axios
                .put(url, data)
                .then(response => {
                    if (response.data.alertType == 'error') {
                        this.showNotificationModal(false, response.data.message);
                    } else {
                        this.showNotificationModal(true, response.data.message);
                    }
                })
                .catch(err => {
                    console.error(err);
                    this.showNotificationModal(false, 'An error happend');
                });
        },

        /**
         * 
         * @param {boolean} saved ( succes or failed )
         * @param {string} message 
         */
        showNotificationModal(saved, message) {
            this.isSavingGeolocation = false;
            this.isGeoSavedSuccess = saved;
            this.isNotificationModalOn = true;
            this.responseMessage = message;
            setTimeout(() => {
                this.isNotificationModalOn = false;
            }, 4000);
        }
    }
});
