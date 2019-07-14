import Vue from "vue";
require("./../bootstrap");

import ReportsFilter from '../components/reports/ReportsFilter.vue';
import ReportsBarChart from '../components/reports/ReportsBarChart.vue';
import ReportsUsersTable from '../components/reports/ReportsUsersTable.vue';
import ReportsPieChart from '../components/reports/ReportsPieChart.vue';

Vue.config.productionTip = false;

const app = new Vue({
    el: '#app-reports',

    components: {
        ReportsFilter,
        ReportsBarChart,
        ReportsUsersTable,
        ReportsPieChart
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