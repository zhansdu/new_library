<template>
	<form class="flex-column" @submit.prevent="search()">
		<div class="d-flex flex-wrap mb-4" v-for="(input,index) in inputs" :key="index">
			<div class="d-flex flex-fill position-relative">
				<div :id="'warn-'+index" class="warn">{{$t('at_least_2')}}</div>
				<select-div :data="select_data" label="name" class="w-min-120 p-3 border-grey no-border-right no-border-right-radius border-width bg-white text-black" labelClasses="text-no-wrap" v-model="input.search.type"/>
				<autocomplete class="flex-fill" input_classes="no-border-left-radius border-grey w-100" v-model="input.search.query" :submit_method="search"/>
			</div>
			<select-div :data="operations" class="w-min-120 border-grey border-width bg-white p-3 ml-0 ml-sm-3 mt-3 mt-sm-0 text-black" v-model="inputs[index+1].operator" v-if="index<(inputs.length-1)" />
			<button type="button" class="w-min-120 ml-0 ml-sm-3 mt-3 mt-sm-0" @click="addItem()" v-if="!(index < (inputs.length-1)) && (index < maximum-1)"> + </button>
		</div>
		<div class="d-flex justify-content-between flex-wrap mt-3">
			<button type="button" @click="clear()">{{$t('reset')}}</button>
			<button type="submit">{{$t('search')}}</button>
		</div>	
	</form>
</template>
<script type="text/javascript">
	import SelectDiv from '../../../../../components/select'
	import autocomplete from '../../components/autocomplete'

	import {selectData,performSearch} from '../../../../../mixins/search'
	import validate from '../../../../../mixins/validate'
	import warn from '../../../../../mixins/warn'
	export default{
		props:{
			maximum:{
				type:Number,
				default(){
					return 5
				}
			}
		},
		mixins:[selectData,performSearch,validate,warn],
		components:{
			SelectDiv,
			autocomplete
		},
		data(){
			return{
				operations:['and','or','not'],
				inputs:[{search:{}},{search:{},operator:'and'}]
			}
		},
		methods:{
			addItem(){
				if(this.inputs.length<this.maximum){
					this.inputs.push({search:{},operator:'and'});
				}
			},
			search(){
				let options=[];
				let query='';
				let valid=0;
				for(let i=0;i<this.inputs.length;i++){
					let input=this.inputs[i];
					let option={}
					
					option.key=input.search.type.key
					option.value=input.search.query || ''
					option.operator=input.operator
					
					if(this.validate(option.value)){
						this.warn(i,false);
					}
					else{
						this.warn(i,true);
						valid+=1;
					}

					if(option.operator){
						query+=this.$t(option.operator).toLowerCase() + ' '
					}
					query+=this.$t(option.key)+' : '+option.value+' ';


					options.push(option);

				}
				let request={
					search_options:options
				};
				if(valid<=0){
					this.performSearch(request,query,options);
				}
			},
			clear(){
				this.inputs=[{search:{}},{search:{},operator:'and'}];
			}
		}
	}
</script>