import Vue from 'vue';
import store from './store';

import ExampleComponent from './components/ExampleComponent';
import AppointmentFilter from './components/appointments/AppointmentsFilter.vue';

const app = new Vue({
    el: '#app',
    components: {
        ExampleComponent,
        AppointmentFilter
    },
    store
});
