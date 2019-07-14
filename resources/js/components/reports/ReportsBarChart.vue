<template>
    <div class="card">
        <base-chart-bar :chart-data="ChartData"></base-chart-bar>
    </div>
</template>

<script>
import BaseChartBar from '../baseComponents/BaseChartBar';

export default {
    name: 'ReportsChart',

    props: {
        DataToLoad: {
            type: Object,
            required: true
        }
    },

    components: {
        BaseChartBar
    },

    computed: {
        ChartData() {
            let labels = [];
            let data = [];

            for (const key in this.DataToLoad) {
                if (this.DataToLoad.hasOwnProperty(key)) {
                    const element = this.DataToLoad[key];
                    
                    labels.push(key);
                    data.push(element);
                }
            };

            return {
                labels: labels,
                datasets: [
                    {
                        label: 'Appointments per day',
                        backgroundColor: '#f87979',
                        data: data
                    }
                ]
            };
        }
    }
}
</script>

<style scoped lang="sass">
.card
    max-width: 400px
</style>
