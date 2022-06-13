<template>
	<form class="d-flex" @submit.prevent="loadResults()">
		<div class="bg-white m-2 rounded w-100 p-3 ">
			<div class="d-flex justify-content-between">
				<div class="col-6">
					<input-div :search="true" :onSubmit="loadResults" v-model="publishers.search.add_options.name" :placeholder="$t('search_by',{type:$t('name_by')})" />
				</div>
				<div class="d-flex align-items-center">
					<button type="button" @click="showModal(CreatePublisher,{loadAll:getAllData})">{{$t('create_publisher')}}</button>
					<button type="button" class="ml-2" @click="loadAllData()">{{$t('load_all')}}</button>
				</div>
			</div>
			<div class="mt-5">
				<div v-if="publishers.searching">
					<table-div 
					class="mt-5"
					:heads="heads"
					:data="publishers.data.res"
					:editObj="editObj"
					:deleteObj="deleteObj"
					:link="link"
					:commit="commit"
					:pagination="publishers.pagination"
					/>
				</div>
			</div>
		</div>
	</form>
</template>
<script type="text/javascript">
// mixins
import showModal from '../../../mixins/showModal'
import {getResults,getAllData} from '../../../mixins/common'

// components
import CreatePublisher from './CreatePublisher'
import table from '../../../components/Table'
import input from '../../../components/Input'

import {mapGetters} from 'vuex'

export default{
	mixins:[showModal,getResults,getAllData],
	components:{
		'table-div':table,
		'input-div':input
	},
	computed:{
		...mapGetters(['publishers'])
	},
	data(){
		return{
			CreatePublisher:CreatePublisher,
			heads:[
			{name:'name',link:'name'},
			{name:'commercial_name',link:'com_name'},
			{name:'address',link:'address'},
			{name:'email',link:'email'},
			{name:'phone',link:'phone'},
			{name:'fax',link:'fax'},
			],
			editObj:{
				available:true,
				component:CreatePublisher
			},
			deleteObj:{
				available:true,
			},
			link:'/publisher',
			commit:'publishers'
		}
	},
	methods:{
		loadResults(){
			this.$store.dispatch('setStore',{label:this.commit,data:{page:0}});
			this.getResults(this.link,this.commit)
		},
		loadAllData(){
			this.$store.dispatch('setStore',{label:this.commit,data:{page:0}});
			this.getAllData(this.link,this.commit);
		}
	}
}
</script>