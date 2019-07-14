<script>
import { Bar } from 'vue-chartjs'

export default {
    extends: Bar,

    props: {
        data: {
            type: Object,
            required: true
        }
    },

    computed: {
        // renderChart object
        ChartObject() {
            return {
            // labels: this.data.labels,
            labels: [1,3,4],
            datasets: [
                {
                label: 'GitHub Commits',
                backgroundColor: '#f87979',
                // data: this.data.data
                data: [3,4,5]
                }
            ]
        }
        }
    },
    mounted () {
        console.log('test');
        const ChartObject = ChartObject;
        this.renderChart({
            // labels: this.data.labels,
            labels: [1,3,4],
            datasets: [
                {
                label: 'GitHub Commits',
                backgroundColor: '#f87979',
                // data: this.data.data
                data: [3,4,5]
                }
            ]
        });
    },

    methods: {

    },
}
</script>
