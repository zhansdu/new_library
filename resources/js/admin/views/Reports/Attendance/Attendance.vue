<template>
	<div class="d-flex">
		<div class="m-2 bg-white rounded-lg w-100 p-4">
			<div class="d-flex align-items-center justify-content-between">
				<div class="font-weight-bold font-size-18">{{$t('attendance_statistics')}}</div>
			</div>
			<div class="d-flex mt-1">
				<div class="flex-fill mt-5">
					<line-chart :data="data" :options="withOptions(lineOptions)"/>
				</div>
				<div class="col-2 ml-2">
					<dropdown dropdownClasses="dropdown-left w-100" titleClasses="border rounded-lg border-black p-2 d-flex justify-content-center no-hover-color" :title="$t('show_for_' + ( weekly ? 'week' : 'month' ))" :items="dropdownItems" :itemOnClick="dropdownItemOnClick"/>
					<pie-chart :data="pieData" :options="withOptions(pieOptions)" />
				</div>
			</div>
			<div class="col-2 mt-5 ml-2">
				<div class="font-weight-bold mt-4">{{$t('in_lib_by_' + (weekly ? 'week':'month'))}}</div>
				<div class="d-flex justify-content-between mt-3">
					<span>{{$t('student')}}: </span>
					<span>{{studentsNumber}}</span>
				</div>
				<div class="d-flex justify-content-between mt-3">
					<span>{{$t('employee')}}: </span>
					<span>{{stuffNumber}}</span>
				</div>
			</div>
		</div> 
	</div>
</template>
<script type="text/javascript">
// common components
import Tabs from '../../../../common/components/Tabs'
import Dropdown from '../../../components/Dropdown'

// plugins / charts
import LineChart from '../../../plugins/charts/Line'
import PieChart from '../../../plugins/charts/Pie'

// mixins
import chartMixins,{lineOptions,PieOptions} from '../../../mixins/charts'


export default{
	mixins:[chartMixins,lineOptions,PieOptions],
	components:{Tabs,LineChart,Dropdown,PieChart},
	computed:{
		studentsNumber(){
			let num=0;
			return this.data.datasets[0].data.reduce((a, b) => a + b, 0)
		},
		stuffNumber(){
			let num=0;
			return this.data.datasets[1].data.reduce((a, b) => a + b, 0)
		}
	},
	data(){
		return{
			weekly:true,
			components:[{name:'Library'},{name:'Virtual'},{name:'Total'}],
			data:{
				datasets:[
				{
					label:'Students',
					data:[]
				},
				{
					label:'Stuff',
					data:[]
				}
				]
			},
			dropdownItems:[{name:'Week',value:true},{name:'Month',value:false}],
			pieData:{
				labels: ['Students', 'Stuff'],
				datasets: [
					{
						backgroundColor: [ '#FF9D29', '#9013FE' ],
					},
				],
			}
		}
	},
	methods:{
		changeData(){
			this.$store.commit('setFullPageLoading',true);
			this.$http.get('/attendance/virtual').then(response=>{
				let lineCopied={};
				let pieCopied={};

				let data=[];
				if(this.weekly){
					data=response.data.res.byWeek;
				}
				else{
					data=response.data.res.byMonth;
				}

				lineCopied.datasets=this.data.datasets;
				lineCopied.labels=this.data.labels;
				lineCopied.datasets.forEach(set=>{
					if(set.label=='Students'){
						set.data=data.students;
					}
					else{
						set.data=data.employees;
					}
				})
				
				pieCopied.labels=this.pieData.labels;
				pieCopied.datasets=this.pieData.datasets;
				pieCopied.datasets[0].data=[data.total.students,data.total.employees];
				
				this.data=lineCopied;
				this.pieData=pieCopied
				
			}).catch(error=>{
				this.message_error('loading',error);
			}).finally(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
			this.changeLabels();
		},
		chooseTab(){
			this.changeData();
		},
		changeLabels(){
			if(this.weekly){
				this.data.labels=this.weekLabels;
			}
			else{
				this.data.labels=this.monthLabels;
			}
		},
		dropdownItemOnClick(item){
			this.weekly=item.value;
			this.changeData();
		},
		setData(){
			this.data.datasets.forEach(item=>{
				if(item.label=="Students"){
					item.borderColor="#FF9D29"
				}
				else{
					item.borderColor="#9013FE"
				}
			})
		}
	},
	created(){
		this.setData();
		this.changeData();
	}
}
</script>
