<template>
	<div class="d-flex flex-column">
		<keep-alive>
			<component :is="components[num].component" class="d-flex mb-3" />
		</keep-alive>
		<u class="d-flex aling-items-center link text-black font-weight-bold text-lightgrey align-self-end" :class="{'text-black':$store.getters.theme=='dark'}" @click="move(components[num].num)" v-if="!$mobileCheck()">
			<component class="font-size-24 mr-2" :is="components[num].icon" />
			{{$t(components[num].name)}}
		</u>
	</div>
</template>
<script type="text/javascript">
	import simple from './simple'
	import advanced from './advanced'
	import AdvancedSearch from '../../../../../common/assets/icons/AdvancedSearch.vue'
	export default{
		data(){
			return{
				num:0,
				components:[
				{
					component:simple,
					num:1,
					name:'advanced_search',
					icon:AdvancedSearch
				},
				{
					component:advanced,
					num:0,
					name:'simple_search'
				}
				]
			}
		},
		methods:{
			move(num){
				this.num=num;
			}
		}
	}
</script>
