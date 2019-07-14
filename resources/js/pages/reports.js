import Vue from "vue";
require("./../bootstrap");

import ReportsFilter from '../components/reports/ReportsFilter.vue';
import ReportsChart from '../components/reports/ReportsChart.vue';
import ReportsUsersTable from '../components/reports/ReportsUsersTable.vue';

const app = new Vue({
    el: '#app-reports',

    components: {
        ReportsFilter,
        ReportsChart,
        ReportsUsersTable
    },

    data: {
        numOfAllApointments: 0,
        numOfAppointmentsPerUser: {},
        numOfAppointmentsPerDay: {}
    },

    methods: {
        fetchData(data) {
            axios.post('/reports', data)
                .then((response) => {
                    this.numOfAllApointments = response.data.numOfAllApointments;
                    this.numOfAppointmentsPerUser = response.data.numOfAppointmentsPerUser;
                    this.numOfAppointmentsPerDay = response.data.numOfAppointmentsPerDay;
                })
        }
    }
});