import Vue from "vue";
require("./../bootstrap");

import ReportsFilter from '../components/reports/ReportsFilter.vue';

const app = new Vue({
    el: '#app-reports',

    components: {
        ReportsFilter
    },

    methods: {
        fetchData(data) {
            console.log(data);
            axios.post('/reports', data)
                .then(response => console.log(response))
        }
    }
});