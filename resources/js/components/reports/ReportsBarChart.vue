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
                        backgroundColor: '#E4572E',
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
    max-width: 45%
    width: 50%
    display: inline-block
    background-color: #fff
    padding: 2em
    box-shadow: 0px 10px 40px rgba(0,0,0, 0.1)

    @media(max-width: 700px)
        max-width: 90%
        width: 100%
        margin-bottom: 2em
</style>
