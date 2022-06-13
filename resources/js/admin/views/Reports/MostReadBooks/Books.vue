<template>
	<div class="p-2">
		<form class="bg-white p-3 rounded-lg" @submit.prevent="search()">
			<div class="pad font-weight-bold font-size-20">{{$t('mrbooks')}}</div>
			<div class="d-flex align-items-center mt-2">
				<div class="d-flex flex-fill">
					<div class="pad flex-fill">
						<input type="date"  v-model="most_read.search.add_options.borrow_date.from"/>
						<label class="placeholder">{{$t('from')}}</label>
					</div>
					<div class="pad flex-fill">
						<input type="date" 	v-model="most_read.search.add_options.borrow_date.to"/>
						<label class="placeholder">{{$t('until')}}</label>
					</div>
				</div>
				<div class="d-flex align-items-center flex-fill">
					<div class="pad d-flex ml-5">
						<button type="submit">{{$t('search')}}</button>
						<button type="button" class="ml-2" @click="reset(commit)">{{$t('reset')}}</button>
					</div>
				</div>
			</div>
			<div v-if="most_read.searching">
				<table-div 
				class="margin-top" 
				:heads="heads"
				:data="most_read.data.res" 
				:link="link" 
				:commit="commit"
				/>
			</div>
		</form>
	</div>
</template>
<script type="text/javascript">
// components
import TableDiv from '../../../components/Table'

// mixins
import {getResults,reset} from '../../../mixins/common'

import {mapGetters} from 'vuex'
export default {
	components:{
		TableDiv
	},
	mixins:[getResults,reset],
	computed:{
		...mapGetters(['most_read'])
	},
	data(){
		return{
			heads:[
			{name:'author',link:'author'},
			{name:'title',link:'title'},
			{name:'language',link:'language'},
			{name:'isbn',link:'isbn'},
			{name:'count',link:'count_issue'},
			],
			link:'most-read',
			commit:'most_read'
		}
	},
	methods:{
		search(){
			this.$store.dispatch('setStore',{label:this.commit,data:{page:0}});
			this.getResults(this.link,this.commit);
		},
		getInitData(){
			this.search();
		}
	},
	created(){
		this.getInitData();
	}
}		
</script>