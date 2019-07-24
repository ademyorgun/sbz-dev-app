import Vue from "vue";
require("./../bootstrap");

import AppointmentFilter from "../components/appointments/AppointmentsFilter.vue";
import AppointmentsPaginator from "../components/appointments/AppointmentsPaginator.vue";
import AppointmentsComments from '../components/appointments/appointmentsComments/AppointmentComments.vue';
import AppointmentsModalBtn from '../components/appointments/AppointmentsModalBtn.vue';
import AppointmentsCommentsModal from '../components/appointments/AppointmentsCommentsModal.vue';
import AppointmentsGeolocationBtn from '../components/appointments/appointmentGeolocation/appointmentGeolocationBtn.vue';
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
        AppointmentsGeolocationBtn
    },

    data: {
        filterData: {},
        paginationData: {},
        isResultsFiltered: false,
        pos: {},
        googleMapAPI: 'AIzaSyCdw_S7lZML8VVa7qppO6UsVjYcwinCCPk'
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
        getGeolocation() {
            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    this.pos = pos;
                },() => {
                    console.log(this.pos);
                    const latlng = this.pos.lat + this.pos.lng;
                    const url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latlng}&key=${this.googleMapAPI}`;
                    axios.get(url)
                        .then(response => console.log(response));
                }).catch(err => console.error(err, 'google map api'));
            } else {
                // Browser doesn't support Geolocation
                // so we open the modal to enter the location as text
                console.log('Geolocation is not supported by this browser');
            }
        },

        openGeolocationModal() {

        }
    }
});


