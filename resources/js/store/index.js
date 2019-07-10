import Vuex from 'vuex';
import Vue from 'vue';
import appointments from './modules/appointments';

// load Vuex
Vue.use(Vuex);

// create store
export default new Vuex.Store({
    modules: {
        appointments,
    },
});
