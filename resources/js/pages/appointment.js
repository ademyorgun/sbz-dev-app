import Vue from "vue";
require("./../bootstrap");

import AppointmentFilter from "../components/appointments/AppointmentsFilter.vue";
import AppointmentsPaginator from "../components/appointments/AppointmentsPaginator.vue";
import AppointmentsComments from '../components/appointments/appointmentsComments/AppointmentComments.vue';
import AppointmentsModalBtn from '../components/appointments/AppointmentsModalBtn.vue';
import AppointmentsCommentsModal from '../components/appointments/AppointmentsCommentsModal.vue';
import AppointmentsGeolocationBtn from '../components/appointments/appointmentGeolocation/appointmentGeolocationBtn.vue';
import AppointmentsGeolocationModal from '../components/appointments/appointmentGeolocation/appointmentsGeolocatoinModal.vue';
import VModal from 'vue-js-modal';


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
        AppointmentsGeolocationModal
    },

    data: {
        filterData: {},
        paginationData: {},
        isResultsFiltered: false,
        pos: {},
        googleMapAPI: 'AIzaSyCdw_S7lZML8VVa7qppO6UsVjYcwinCCPk',
        appointmentId: 0
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
            console.log(page, 'yay');
        },

        /**
         * 
         * @param {*} appointmentId 
         */
        openCommentsModal(appointmentId) {
            this.$modal.show('comments-modal', appointmentId);
        },

        /**
         * Get the user location
         * 
         * 
         */
        getGeolocation(appointmentId) {
            this.appointmentId = appointmentId;
            console.log('working???');
            // Try HTML5 geolocation.
            console.log('testing 1');
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    this.pos = pos;
                    this.getGoogleMapGeo(pos);
                })
                console.log('testing 2');
            } else {
                // Browser doesn't support Geolocation
                // so we open the modal to enter the location as text
                console.log('Geolocation is not supported by this browser');
                this.$modal.show('geolocation-modal');
            }
        },

        getGoogleMapGeo(pos) {
            console.group('geolocation function');
            try {
                const point = new google.maps.LatLng(pos.lat, pos.lng);
                // var point = new google.maps.LatLng(38.41054600530499, -112.85153749999995);
                const Geocoder = new google.maps.Geocoder;
                console.log('test');
                Geocoder.geocode({ 'latLng': point }, function (results, status) {
                    console.log('function test')
                    if (status !== google.maps.GeocoderStatus.OK) {
                        alert(status);
                    }
                    console.log(results, status);
                    // This is checking to see if the Geoeode Status is OK before proceeding
                    if (status == google.maps.GeocoderStatus.OK) {
                        console.log(results);
                        var address = (results[0].formatted_address);
                    }
                });
            } catch(e) {
                console.error(e);
            }
            console.groupEnd();
        },
        
        saveGeolocation() {
            axios.post('')
                .then(response => console.log(response))
                .catch(err => console.error(err));
        }
    }
});


