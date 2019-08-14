import Vue from "vue";
require("./bootstrap");

import AppointmentFilter from "./components/appointments/AppointmentsFilter.vue";
import AppointmentsPaginator from "./components/appointments/AppointmentsPaginator.vue";
import AppointmentsComments from './components/appointments/appointmentsComments/AppointmentComments.vue';
const app = new Vue({
    el: "#app",

    components: {
        AppointmentFilter,
        AppointmentsPaginator,
        AppointmentsComments
    },

    data: {
        filterData: {},
        paginationData: {},
        isResultsFiltered: false
    },

    computed: {},

    methods: {
        getResults(data , page = 1) {
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
        }
    }
});
