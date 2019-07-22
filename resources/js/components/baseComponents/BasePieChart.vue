<script>
import { Pie, mixins } from "vue-chartjs";
const { reactiveProp } = mixins;
// import ChartDataLabels from 'chartjs-plugin-datalabels';

export default {
  extends: Pie,

  mixins: [reactiveProp],

  props: ["options"],

  mounted() {
    this.renderChart(this.chartData, {
      plugins: {
        ChartDataLabels: {
            formatter: (value, ctx) => {
                let sum = 0;
                let dataArr = ctx.chart.data.datasets[0].data;
                dataArr.map(data => {
                    sum += data;
                });
                let percentage = (value*100 / sum).toFixed(2)+"%";
                return percentage;
            },
            color: '#fff',
        }
    }
    });
  },

  methods: {}
};
</script>