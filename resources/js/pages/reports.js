import Vue from "vue";
require("./../bootstrap");

import ReportsFilter from '../components/reports/ReportsFilter.vue';
import ReportsBarChart from '../components/reports/ReportsBarChart.vue';
import ReportsUsersTable from '../components/reports/ReportsUsersTable.vue';
import ReportsPieChart from '../components/reports/ReportsPieChart.vue';
import ReportsLineChart from '../components/reports/ReportsLineChart.vue';
import ReportsTotalCard from '../components/reports/ReportsTotalCard.vue';

Vue.config.productionTip = false;

const app = new Vue({
    el: '#app-reports',

    components: {
        ReportsFilter,
        ReportsBarChart,
        ReportsUsersTable,
        ReportsPieChart,
        ReportsLineChart,
        ReportsTotalCard
    },

    data: {
        numOfAllApointments: 0,
        numOfAppointmentsPerUser: {},
        numOfAppointmentsPerDay: {},
        numOfAppointmentsPerStatus: {},
        numOfAllApointmentsPerDayPositive: {},
        numOfAllApointmentsPerDayNegative: {},
        numberOfAppointmentsWonPerDay: {},
        numberOfAppointmentsNotWonPerDay: {}
    },

    methods: {
        fetchData(data) {
            axios.post('/reports', data)
                .then((response) => {
                    this.numOfAllApointments = response.data.numOfAllApointments;
                    this.numOfAppointmentsPerUser = response.data.numOfAppointmentsPerUser;
                    this.numOfAppointmentsPerDay = response.data.numOfAppointmentsPerDay;
                    this.numOfAppointmentsPerStatus = response.data.numOfAppointmentsPerStatus;
                    this.numOfAllApointmentsPerDayPositive = response.data.numOfAllApointmentsPerDayPositive;
                    this.numOfAllApointmentsPerDayNegative = response.data.numOfAllApointmentsPerDayNegative;
                    this.numberOfAppointmentsWonPerDay = response.data.numberOfAppointmentsWonPerDay;
                    this.numberOfAppointmentsNotWonPerDay = response.data.numberOfAppointmentsNotWonPerDay;
                })
        }
    }
});