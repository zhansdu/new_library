<template>
	<div class="filter-width bg-lightgrey p-3 rounded-lg">
		<div class="font-weight-bold">{{$t('filter')}}</div>
		<div class="text-grey font-size-14 mt-3">{{$t('types')}} :</div>
		<div class="d-flex justify-content-between mt-1" v-for="(type,index) in filter_data.type_key">
			<div>{{$t(type.title)}}</div>
			<div>
				<checkbox @change="checkType(type)" :checked="filter_search.types.some(e => e.value == type.value)">
				</checkbox>
			</div>
		</div>
		<div class="text-grey font-size-14 mt-3">{{$t('languages')}} :</div>
		<div class="d-flex justify-content-between mt-1" v-for="(lang,index) in filter_data.language">
			<div>{{$t(lang)}}</div>
			<div>
				<checkbox @change="checkLang(lang)" :checked="filter_search.languages.some(e => e == lang)">
				</checkbox>
			</div>
		</div>
		<div class="text-grey font-size-14 mt-3">{{$t('year')}} :</div>
		<div class="mt-1">
			<select-div class="p-2 bg-white" :data="years" label="name" v-model="filter_search.year"/>
		</div>
		<button class="mt-3 w-100 bg-white text-orange" @click="apply()">{{$t('apply')}}</button>
	</div>
</template>
<script type="text/javascript">
	import SelectDiv from '../../../../components/select'
	import Checkbox from '../../../../components/checkbox'

	import {mapGetters} from 'vuex'

	import {filters} from '../../../../mixins/search'
	export default{
		components:{
			SelectDiv,
			Checkbox
		},
		mixins:[filters],
		computed:{
			...mapGetters(['filter_data','filter_search','search_request']),
			years(){
				let all=[{name:'all',value:''}];
				let years=this.filter_data.year.map(year=>{return {name:year,value:year}});
				return all.concat(years);
			}
		},
		methods:{
			checkType(type){
				let filter=this.filter_search;
				if(filter.types.some(e => e.value == type.value)){
					filter.types=filter.types.filter(item => item.value !== type.value);
				}
				else{
					filter.types.push(type)
				}
			},
			checkLang(lang){
				let filter=this.filter_search;
				if(filter.languages.some(e=>e==lang)){
					filter.languages=filter.languages.filter(item=>item!==lang);
				}
				else{
					filter.languages.push(lang)
				}
			},
			apply(){
				this.$store.commit('setFullPageLoading',true);
				
				let search=this.search_request;
				let request= {
					search_options:search
				}

				this.setFilters(request);


				this.$http.post('media/search',request).then(response=>{
					this.$store.commit('setResults',response.data.res);
					this.$store.commit('setFullPageLoading',false);
				})
			}
		}
	}
</script>
