<template>
  <div class="card">
    <BaseLineChart :chart-data="chartData" :options="chartOption"></BaseLineChart>
  </div>
</template>

<script>
import BaseLineChart from "../baseComponents/BaseLineChart";

export default {
  name: "ReportsLineChart",

  components: {
    BaseLineChart
  },

  props: {
    DataToLoad: {
      type: Object,
      required: true
    },
    backgroundColor: {
      type: String
    },
    label: {
      type: String
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
            label: this.label,
            backgroundColor: this.backgroundColor,
            data
          }
        ]
      };
    },
    chartOption() {
      return { 
        plugins: {
          datalabels: false
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
    display: inline-block
    background-color: #fff
    padding: 2em
    box-shadow: 0px 10px 40px rgba(0,0,0, 0.1)

    @media(max-width: 700px)
        max-width: 90%
        width: 100%
        margin-bottom: 2em
</style>
