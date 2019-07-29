import Vue from "vue";
require("./../bootstrap");

import ReportsFilter from '../components/reports/ReportsFilter.vue';
import ReportsBarChart from '../components/reports/ReportsBarChart.vue';
import ReportsSalesAgentsTable from '../components/reports/ReportsSalesAgentsTable.vue';
import ReportsCallAgentsTable from '../components/reports/ReportsCallAgentsTable.vue';
import ReportsPieChart from '../components/reports/ReportsPieChart.vue';
import ReportsLineChart from '../components/reports/ReportsLineChart.vue';
import ReportsTotalCard from '../components/reports/ReportsTotalCard.vue';

Vue.config.productionTip = false;

const app = new Vue({
    el: '#app-reports',

    components: {
        ReportsFilter,
        ReportsBarChart,
        ReportsSalesAgentsTable,
        ReportsPieChart,
        ReportsLineChart,
        ReportsTotalCard,
        ReportsCallAgentsTable
    },

    data: {
        numOfAllApointments: 0,
        numOfAppointmentsPerSalesAgent: [],
        numOfAppointmentsPerCallAgent: [],
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
                    const res = response.data;
                    this.numOfAllApointments = res.numOfAllApointments;
                    this.numOfAppointmentsPerDay = res.numOfAppointmentsPerDay;
                    this.numOfAppointmentsPerStatus = res.numOfAppointmentsPerStatus;
                    this.numOfAllApointmentsPerDayPositive = res.numOfAllApointmentsPerDayPositive;
                    this.numOfAllApointmentsPerDayNegative = res.numOfAllApointmentsPerDayNegative;
                    this.numberOfAppointmentsWonPerDay = res.numberOfAppointmentsWonPerDay;
                    this.numberOfAppointmentsNotWonPerDay = res.numberOfAppointmentsNotWonPerDay;
                    
                    this.numOfAppointmentsPerSalesAgent = _.sortBy(res.numOfAppointmentsPerSalesAgent, 'total').reverse();
                    this.numOfAppointmentsPerCallAgent = _.sortBy(res.numOfAppointmentsPerCallAgent, 'total').reverse();
                })
        }
    }
});