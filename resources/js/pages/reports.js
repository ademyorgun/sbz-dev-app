import Vue from "vue";
require("./../bootstrap");

import ReportsFilter from '../components/reports/ReportsFilter.vue';

const app = new Vue({
    el: '#app-reports',

    components: {
        ReportsFilter
    }
});