<template>
	<div class="d-flex">
		<div class="bg-white mt-2 flex-fill p-2 px-3">
			<Back />
			<form @submit.prevent="loadResults()" class="mt-3">
				<div class="font-size-18 font-weight-bold">{{$t('search_barcodes')}}</div>
				<div class="d-flex align-items-center flex-fill">
					<div class="d-flex flex-column flex-fill">
						<span class="font-weight-bold mt-3">{{$t('barcode')}}</span>
						<div class="d-flex flex-fill mt-2">
							<div class="position-relative flex-fill">
								<input type="text" v-model="print_barcode.search.add_options.barcode.from"/>
								<label class="placeholder">{{$t('from')}}</label>
							</div>
							<div class="position-relative  ml-2 flex-fill">
								<input type="text" v-model="print_barcode.search.add_options.barcode.to"/>
								<label class="placeholder">{{$t('until')}}</label>
							</div>
						</div>
						<span class="font-weight-bold mt-3">{{$t('title') + ' - ' + $t('author')}}</span>
						<div class="d-flex flex-fill mt-2">
							<div class="position-relative flex-fill">
								<input type="text" v-model="print_barcode.search.add_options.title"/>
								<label class="placeholder">{{$t('title',1)}}</label>
							</div>
							<div class="position-relative ml-2 flex-fill">
								<input type="text" v-model="print_barcode.search.add_options.author"/>
								<label class="placeholder">{{$t('author')}}</label>
							</div>
						</div>
						<!-- <span class="font-weight-bold mt-3">{{$t('status')}}</span>
						<div class="d-flex flex-fill mt-2">
							<div class="position-relative flex-fill">
								<input-div
									v-model="print_barcode.search.add_options.print_status"
									:selectable="{
										available: true,
										data: statuses.print
									}"
									:head="'print_status'"
									:body="'print_status'"
									:autocomplete="{
										available: true,
										data: statuses.init
									}"
									:showBody="false"
								/>
								<label class="placeholder">{{$t('print_status',1)}}</label>
							</div>
							<div class="position-relative ml-2 flex-fill">
								<input-div
									v-model="print_barcode.search.add_options.init_status"
									:selectable="{
										available: true,
										data: statuses.init
									}"
									:head="'init_status'"
									:body="'init_status'"
									:autocomplete="{
										available: true,
										data: statuses.init
									}"
									:showBody="false"
								/>
								<label class="placeholder">{{$t('init_status')}}</label>
							</div>
						</div> -->
						<div class="mt-2 d-flex justify-content-end">
							<button class="width-unset" type="submit">{{$t('search')}}</button>
						</div>
					</div>
				</div>
			</form>
			<div class="mt-5">
				<div v-if="print_barcode.searching">
					<table-div class="mt-5"
					:heads="heads"
					:data="print_barcode.data.res"
					:selectable="selectable"
					:link="link"
					:commit="commit"
					:pagination="print_barcode.pagination"
					:clickables="true"
					:sortable="false"
					:custom_func="custom_func"
					/>
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
// components
import Back from '../../../components/Back'
import Dropdown from '../../../components/Dropdown'
import Table from '../../../components/Table'
import SelectedItems from '../../../components/SelectedItems'
import InputDiv from '../../../components/Input.vue'

// loading indicator
import PulseLoader from 'vue-spinner/src/PulseLoader'

//mixins
import {getResults,last} from '../../../mixins/common'
import {print_file} from '../../../mixins/files'
import {message_success,message_error} from '../../../mixins/messages'
import readFromRfid from '../../../mixins/readFromRfid'
import showModal from '../../../mixins/showModal'

// libraries
import {mapGetters} from 'vuex'

export default{
	mixins:[getResults,print_file,readFromRfid,message_success,message_error,showModal,last],
	components:{Back,Dropdown,'table-div':Table,PulseLoader,InputDiv},
	computed:{
		...mapGetters(['print_barcode'])
	},
	data(){
		return{
			types:[],
			type:'barcode',
			heads:[
			{name:'barcode',link:'barcode'},
			{name:'inventory_number',link:'id'},
			{name:'titles',link:'title'},
			{name:'author',link:'author'},
			{name:'print_status',link:'print_status',class_func:this.print_class_func},
			{name:'init_status',link:'init_status',class_func:this.init_class_func},
			],
			selectable:{
				available:true,
				button_title:'print',
				func:this.printIt,
				showSelected:this.showSelected
			},
			link:'/barcode',
			commit:'print_barcode',
			barcodes:[],
			custom_func:{
				available:true,
				title:'initialize',
				func:this.initBarcode,
				class:['outline-green']
			},
			statuses:{}
		}
	},
	methods:{
		print_class_func(info){
			let res={};
			if(info.print_status=='not printed'){
				res['text-blue']=true
			}
			return res
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
		changeMode(mode){
			this.type=mode;
		},
		async initBarcode(item){
			await this.setBarcode(item.barcode);
			this.initializeInDB(item.id);
		},
		async setBarcode(barcode){
			await this.readFromRfid('SetItemID','newID='+barcode);
		},
		initializeInDB(id){
			this.$store.commit('setFullPageLoading',true)
			this.$http.get(this.link+'/init/'+id).then(()=>{
				this.last(this.link,this.commit);
				this.message_success('initialize',{});
			}).catch(error=>{
				this.message_error('loading',error);
			}).then(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
		},
		loadResults(){
			let sort_by={
				key:'barcode',
				mode:'asc'
			}
			this.$store.dispatch('setStore',{label:this.commit,data:{page:0}});
			this.getResults(this.link,this.commit,null,null,sort_by);
		},
		showSelected(barcodes,func){
			let props={
				heads:this.heads,
				data:barcodes,
				selectable:this.selectable,
				commit:this.commit,
				link:this.link,
				pagination:false,
				clickables:true,
				sortable:false,
				custom_func:this.custom_func,
			}
			if(func!=undefined){
				props.func=func;
			}
			this.showModal(SelectedItems,props);
		},
		printIt(barcodes){
			let print=(barcodes)=>{
				let inventories=barcodes.map(barcode=>barcode.id);
				this.$store.commit('setFullPageLoading',true)
				this.$http.post(this.link+'/print',{inventories:inventories},{responseType:'blob'}).then(response=>{
					this.print_file(response);
					this.last(this.link,this.commit);
					this.$eventHub.$emit('selectRefresh');
					this.$emit('close');
				}).catch(error=>{
					this.message_error('loading',error);
				}).then(()=>{
					this.$store.commit('setFullPageLoading',false);
				})
			}
			this.showSelected(barcodes,print);
		},
		getStatuses(){
			this.$store.commit('setFullPageLoading',true);
			this.$http.get(this.link+'/statuses').then(response=>{
				this.statuses=response.data.res
			}).catch(error=>{
				this.message_error('loading',error);
			}).finally(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
		}
	}
}
</script>