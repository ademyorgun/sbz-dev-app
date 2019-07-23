import Vue from "vue";
require("./../bootstrap");

import AppointmentFilter from "../components/appointments/AppointmentsFilter.vue";
import AppointmentsPaginator from "../components/appointments/AppointmentsPaginator.vue";
import AppointmentsComments from '../components/appointments/appointmentsComments/AppointmentComments.vue';
import AppointmentsModalBtn from '../components/appointments/AppointmentsModalBtn.vue';
import AppointmentsCommentsModal from '../components/appointments/AppointmentsCommentsModal.vue';
import VModal from 'vue-js-modal'


Vue.config.productionTip = false;

Vue.use(VModal);
const app = new Vue({
    el: "#app",

    components: {
        AppointmentFilter,
        AppointmentsPaginator,
        AppointmentsComments,
        AppointmentsModalBtn,
        AppointmentsCommentsModal
    },

    data: {
        filterData: {},
        paginationData: {},
        isResultsFiltered: false
    },

    computed: {},

    methods: {
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

        openCommentsModal(appointmentId) {
            this.$modal.show('comments-modal', appointmentId);
        }
    }
});


