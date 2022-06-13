<template>
	<div class="d-flex">
		<div class="bg-white m-2 p-3 w-100">
			<label class="pad font-size-18 font-weight-bold">{{$t('inventory_number')}}</label>
			<form class="d-flex flex-fill mt-2" @submit.prevent="loadResults()">
				<div class="pad flex-fill">
					<input type="text" v-model="books_inv_number.search.add_options.inventory_no" required />
					<label class="placeholder required">{{$t('from')}}</label>
				</div>
				<div class="pad flex-fill">
					<input type="text" v-model="books_inv_number.search.add_options.rownum" required />
					<label class="placeholder required">{{$t('count')}}</label>
				</div>
				<div class="pad">
					<button type="submit">{{$t('search')}}</button>
				</div>
				<div class="pad">
					<button type="button" @click="reset(commit)">{{$t('reset')}}</button>
				</div>
			</form>
			<div v-if="books_inv_number.searching">
				<table-div
				class="mt-5"
				:heads="heads"
				:data="books_inv_number.data.res"
				:link="link"
				:commit="commit"
				:sortable="false"
				:clickables="false"
				:pagination="books_inv_number.pagination"
				/>
				<div class="d-flex mt-4">
					<button class="width-unset mr-4" @click="print()">{{$t('print')}}</button>
					<button class="width-unset" @click="exportToExcel()">{{$t('export')}}</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
// components
import Table from '../../../components/Table'

// loading indicator
import PulseLoader from 'vue-spinner/src/PulseLoader'

//mixins
import {getResults,reset} from '../../../mixins/common'
import {print_file,download_file} from '../../../mixins/files'

import {mapGetters} from 'vuex'
export default{
	mixins:[getResults,print_file,download_file,reset],
	components:{'table-div':Table,PulseLoader},
	computed:{
		...mapGetters(['books_inv_number'])
	},
	data(){
		return{
			loading:false,
			heads:[
			{name:'author_title',link:'author_title'},
			{name:'year_city',link:'year_city'},
			{name:'call_number',link:'call_number'},
			{name:'barcode',link:'barcode'},
			{name:'cost',link:'cost'},
			{name:'currency',link:'currency'},
			{name:'document_number',link:'doc_no'},
			{name:'batch_id',link:'batch_id'},
			{name:'inventory_number',link:'inventory_no'},
			{name:'create_date',link:'create_date',is_date:true},
			],
			link:'/inventory-books',
			commit:'books_inv_number'
		}
	},
	methods:{
		loadResults(){
			this.$store.dispatch('setStore',{label:this.commit,data:{page:0}});
			this.getResults(this.link,this.commit);
		},
		print(){
			this.$store.commit('setFullPageLoading',true);	
			this.$http.post(this.link+'/print',{inventories:this.books_inv_number.all},{responseType:'blob'}).then(response=>{
				this.print_file(response);
			}).catch(error=>{
				this.message_error('loading',error);
			}).finally(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
		},
		exportToExcel(){
			this.$store.commit('setFullPageLoading',true);	
			this.$http.post(this.link+'/export',{inventories:this.books_inv_number.all},{responseType:'blob'}).then(response=>{
				this.download_file(response,'inventory_number','xlsx');
			}).catch(error=>{
				this.message_error('loading',error);
			}).finally(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
		}
	}
}
</script>
<style scoped></style>