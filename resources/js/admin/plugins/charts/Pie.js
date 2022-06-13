import { Pie } from "vue-chartjs";
import 'chartjs-plugin-labels'

export default {
  extends: Pie,
  props: ["data", "options"],
  methods:{
    render(){
      this.renderChart(this.data,Object.assign({},{plugins:{datalabels:{display:false}}},this.options));
    }
  },
  watch:{
  	data(){
      this.render();
  	}
  },
  mounted() {
    this.render();
  }
};
