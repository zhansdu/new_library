import { Line } from "vue-chartjs";
import 'chartjs-plugin-datalabels'

export default {
  extends: Line,
  props: ["data", "options"],
  methods:{
    render(){
      this.renderChart(this.data,this.options);
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
