<template>
	<form class="d-flex" @submit.prevent="loadResults()">
		<div class="mt-2 ml-2 bg-white rounded-lg col-2"><filter-div /></div>
		<div class="mt-2 mx-2 bg-white rounded-lg flex-fill p-3">
			<div class="d-flex align-items-start justify-content-between">
				<div class="d-flex align-items-start flex-fill">
					<div class="d-flex flex-column flex-fill">
						<div class="d-flex flex-fill" :class="{'mt-3':index!=0}" v-for="(input,index) in items.search.search_options" :key="index">
							<div class="d-flex flex-fill">
								<div class="select position-relative bg-white z-index-1">
									<select class="no_border_right h-100" v-model="input.key">
										<option v-for="(item,index) in items.search_fields" :value="item.key" :key="index">{{$tc(item.key,1)}}</option>
									</select>
									<label class="placeholder">{{$t('type')}}</label>
								</div>
								<input-div class="flex-fill" classes="border-grey no_border_left h-100" :search='true' :onSubmit="loadResults" v-model="input.value" :placeholder="$t('search_by',{type:$t(input.key+'_by')})"/>
							</div>
							<div class="ml-1 d-flex">
								<div class="select double-width mr-2" v-if="items.search.search_options.length>1 && index<items.search.search_options.length-1">
									<select v-model="items.search.search_options[index+1].operator">
										<option value="and">{{$t('and')}}</option>
										<option value="or">{{$t('or')}}</option>
										<option value="not">{{$t('not')}}</option>
									</select>
								</div>
								<button type="button" class="width outline-orange" @click="addInput({key:'author',operator:'and'})" v-if="index==0">+</button>
								<button type="button" class="width outline-orange" @click="removeInput(input)" v-else>-</button>
							</div>
						</div>
					</div>
				</div>
				<div class="d-flex align-items-center ml-5">
					<button type="button" class="outline-blue" @click="$router.push({path:'print'})">
						<span><Print /> &nbsp;</span>
						<span>{{($t('print'))}}</span>
					</button>
					<button type="button" class="ml-2" @click="showModal(CreateItems,{lastCreated:lastCreated})">
						<span><Plus /> &nbsp;</span>
						<span>{{($t('add_item'))}}</span>
					</button>
				</div>
			</div>
			<div class="mt-5">
				<div v-if="items.searching">
					<table-div class="mt-5"
					:heads="heads"
					:data="items.data.res"
					:editObj="editObj"
					:deleteObj="deleteObj"
					:showMore="showMore"
					:link="link"
					:commit="commit"
					:pagination="items.pagination"
					/>
				</div>
			</div>
		</div>
	</form>
</template>
<script type="text/javascript">
// common components
import Table from '../../../components/Table'
import More from '../../../components/More'
import Input from '../../../components/Input'

// item components
import CreateItems from './CreateItems'
import Filter from './Filter'

// icons
import Print from '../../../../common/assets/icons/Print'
import Plus from '../../../../common/assets/icons/Plus'
import Download from '../../../../common/assets/icons/Download'

// mixins
import showModal from '../../../mixins/showModal'
import {getResults} from '../../../mixins/common'

import {mapGetters} from 'vuex'
import store from '../../../store'

export default{
	mixins:[showModal,getResults],
	components:{
		'table-div':Table,
		'filter-div':Filter,
		'input-div':Input,
		Print,
		Download,
		Plus,
	},
	computed:{
		...mapGetters(['items'])
	},
	data(){
		return{
			loading:false,
			CreateItems:CreateItems,
			More:More,
			heads:[
			{name:'barcode',link:'barcode'},
			{name:'inventory_number',link:'id'},
			{name:'batches_number',link:'batch_id'},
			{name:'author',link:'author'},
			{name:'titles',link:'title'},
			{name:'print_status',link:'print_status',class_func:this.print_class_func},
			{name:'init_status',link:'init_status',class_func:this.init_class_func},
			],
			editObj:{
				available:true,
				reCreate:true,
				lastCreated:this.lastCreated,
				component:CreateItems
			},
			deleteObj:{
				available:true,
			},
			showMore:{
				available:true,
				title:'show_more',
				func:this.showit
			},
			specialities:[],
			link:'/item',
			commit:'items'
		}
	},
	methods:{
		print_class_func(info){
			let res={};
			if(info.print_status=='not printed'){
				res['text-blue']=true
			}
			return res;
		},
		init_class_func(info){
			let res={};
			if(info.init_status=='not initialized'){
				res['text-orange']=true
			}
			else{
				res['text-green']=true
			}
			return res;
		},
		lastCreated(){
			this.$http.get(this.link+'/last-created').then(response=>{
				this.$store.dispatch('setStore',{label:this.commit,data:{data:response.data,searching:true}});
			})
		},
		loadResults(){
			this.$store.dispatch('setStore',{label:this.commit,data:{page:0}});
			this.getResults(this.link,this.commit)
		},
		loadSpecialities(){
			this.$store.commit('setFullPageLoading',true);
			this.$http.get(this.link+'/specialities').then(response=>{
				this.specialities=response.data.res;
			}).catch(error=>{
				this.message_error('loading',error);
			}).then(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
		},
		loadSearchFields(){
			if(this.items.search_fields.length<1){
				this.$store.commit('setFullPageLoading',true);
				this.$http.get(this.link+'/search-fields').then(response=>{
					this.items.search_fields=response.data.res.search_options;
				}).catch(error=>{
					this.message_error('loading',error);
				}).then(()=>{
					this.$store.commit('setFullPageLoading',false);
				})
			}
		},
		display_speciality(info){
			if(info.prog_code){
				return this.specialities.find(spec=>spec.prog_code==info.prog_code).title;
			}
			return null;
		},
		showit(info){
			let heads=[
			{name:'barcode',link:'barcode'},
			{name:'inventory_number',link:'id'},
			{name:'batches_number',link:'batch_id'},
			{name:'isbn',link:'isbn'},
			{name:'author',link:'author'},
			{name:'titles',link:'title'},
			{name:'publishers',link:'publisher'},
			{name:'pub_year',link:'pub_year'},
			{name:'pub_city',link:'pub_city'},
			{name:'suppliers',link:'supplier'},
			{name:'cost',link:'cost'},
			{name:'speciality',display_func:this.display_speciality},
			{name:'currency',link:'currency'},
			{name:'type_of_item',link:'item_type'},
			{name:'type_of_supply',link:'sup_type'},
			{name:'location',link:'location_title'},
			{name:'fill_date',link:'create_date',is_date:true},
			{name:'created_by',link:'username'},
			{name:'init_status',link:'init_status'},
			{name:'print_status',link:'print_status'}
			];
			let props={
				data:info,
				heads:heads,
				width:'35%',
				editObj:this.editObj,
				deleteObj:this.deleteObj,
				link:this.link,
				commit:this.commit
			};
			this.showModal(this.More,props);
		},
		addInput(input){
			if(this.items.search.search_options.length<5)
			this.items.search.search_options.push(input);
		},
		removeInput(input){
			this.items.search.search_options.splice(this.items.search.search_options.indexOf(input),1);
		}
	},
	created(){
		this.loadSpecialities();
		this.loadSearchFields();
	}
}
</script>
<style scoped>
.pad{
	padding:0.3125em;
}
.add_button-wrapper{
	min-width: 9.375em;
	width:25%;
}
.no_border_right{
	border-top-right-radius: 0;
	border-bottom-right-radius: 0;
}

.no_border_left{
	border-top-left-radius: 0;
	border-bottom-left-radius: 0;
}

.width{
	min-width: 3.5em;
}

.double-width{
	min-width: 7em;
}

</style>