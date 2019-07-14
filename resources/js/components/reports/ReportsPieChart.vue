<template>
    <div class="card">
        <base-pie-chart :chart-data="ChartData"></base-pie-chart>
    </div>
</template>

<script>
import BasePieChart from '../baseComponents/BasePieChart';

export default {
    name: 'ReportsPieChart',

    components: {
        BasePieChart
    },

    props: {
        DataToLoad: {
            type: Object,
            // required: true
        }
    },

    data() {
        return {
            ChartData: {
                labels: ['VueJs', 'EmberJs', 'ReactJs', 'AngularJs'],
                datasets: [
                    {
                    backgroundColor: [
                        '#41B883',
                        '#E46651',
                        '#00D8FF',
                        '#DD1B16'
                    ],
                    data: [40, 20, 80, 10]
                    }
                ]
            }
        }
    },

    computed: {
        chartsData() {
            let labels = [];
            let data = [];
            const object = this.DataToLoad;

            for (const key in object ) {
                if (object.hasOwnProperty(key)) {
                    const element = object[key];
                    
                    labels.push(key.replace('_', ' ').toUpperCase());
                    data.push(element);
                }
            }
        }
    }
}
</script>

<style lang="sass" scoped>
.card 
    max-width: 400px
</style>
