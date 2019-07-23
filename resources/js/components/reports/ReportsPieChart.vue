<template>
  <div class="card">
    <base-pie-chart :chart-data="chartData" :options="chartOption" ></base-pie-chart>
  </div>
</template>

<script>
import BasePieChart from "../baseComponents/BasePieChart";

export default {
  name: "ReportsPieChart",

  components: {
    BasePieChart
  },

  props: {
    DataToLoad: {
      type: Object
    }
  },

  computed: {
    chartData() {
      let labels = [];
      let data = [];
      const object = this.DataToLoad;

      for (const key in object) {
        if (object.hasOwnProperty(key)) {
          const element = object[key];

          labels.push(key.replace("_", " ").toUpperCase());
          data.push(element);
        }
      }

      return {
        labels,
        datasets: [
          {
            backgroundColor: [
              "#41B883",
              "#E46651",
              "#00D8FF",
              "#DD1B16",
              "#00ffff",
              "#a4a4a4"
            ],
            data
          }
        ]
      };
    },

    chartOption() {
      return { 
        plugins: {
          datalabels: {
              formatter: (value, ctx) => {
                  let sum = 0;
                  let dataArr = ctx.chart.data.datasets[0].data;
                  dataArr.map(data => {
                      sum += data;
                  });
                  let percentage = (value*100 / sum).toFixed(2)+"%";
                  if(percentage != '0.00%') {
                    return percentage;
                  }
              },
              color: '#000',
          }
        }
      }
    }
  }
};
</script>

<style lang="sass" scoped>
.card 
    max-width: 45%
    width: 50%
    display: block
    margin: 0.5em 1em
    background-color: #fff
    padding: 2em
    box-shadow: 0px 10px 40px rgba(0,0,0, 0.1)

    @media(max-width: 700px)
        max-width: 90%
        width: 100%
        margin-bottom: 2em
</style>
