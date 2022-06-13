<template>
	<div class="mt-2 bg-white py-4 px-3">
		<Back />
		<div class="d-flex mt-2">
			<div class="d-flex flex-column">
				<div class="d-flex flex-column align-items-center p-3 px-5">
					<div class="imageWidth imageHeight image rounded" :style="'background-image: url('+backgroundImage+')'"/>
					<div class="mt-2 text-center">{{$t(info.type)}}</div>
				</div>
				<div class="d-flex flex-column bg-lightgrey p-2 px-5 mt-auto">
					<div class="d-flex justify-content-between imageWidth align-self-center" v-for="(value,key,index) in user.total" :key="index">
						<div :class="[{'text-green':index==0},{'text-orange':index==1},{'text-red':index==2}]">{{$t(key)+':'}}</div>
						<div>{{value}}</div>
					</div>
				</div>
			</div>
			<div class="d-flex bg-lightgrey rounded-lg p-3 ml-4 flex-fill" v-if='user.info'>
				<table class="info_table">
					<tr v-for="(item,index) in new Array(Math.ceil(Object.keys(user.info).length / 2))" :key="index">	
						<td v-if="user_info.leftArray[index]!=undefined">
							<div class="d-flex" :class="{'mt-3 mt-xl-5':index!=0}">
								<div class="text-grey">{{capitalize($t(user_info.leftArray[index].key))}}:</div>
								<div class="ml-2">{{user_info.leftArray[index].value}}</div>
							</div>
						</td>
						<td v-if="user_info.rightArray[index]!=undefined">
							<div class="d-flex" :class="{'mt-3 mt-xl-5':index!=0}">
								<div class="text-grey">{{capitalize($t(user_info.rightArray[index].key))}}:</div>
								<div class="ml-2">{{user_info.rightArray[index].value}}</div>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="d-flex justify-content-between mt-4">
			<tabs :components="components" tabClasses="font-size-18 mr-3" :tabOnClick="tabOnClick"/>
			<div class="d-flex" v-if="state=='issuance'">
				<input-div :search="true" :placeholder="$t('barcode')" v-model="barcode" :onSubmit="search"/>
				<button class="ml-3 width-unset" @click="getRfidInfo()">{{$t('read_from_rfid')}}</button>
			</div>
		</div>
		<div class="mt-4">
			<table-div
			:heads="heads"
			:data="data"
			:selectable="selectable"
			:clickables="clickables"
			:sortable="false"
			:pagination="false"
			:custom_func="custom_func"
			/>
		</div>
	</div>
</template>
<script type="text/javascript">
// components
import Back from '../../../components/Back'
import TableDiv from '../../../components/Table'
import Tabs from '../../../../common/components/Tabs'
import InputDiv from '../../../components/Input'
import SelectedItems from '../../../components/SelectedItems'

import EditDuration from './EditDuration'
// mixins
import readFromRfid from '../../../mixins/readFromRfid'
import {message_success,message_error} from '../../../mixins/messages'
import {goTo} from '../../../mixins/goTo'
import showModal from '../../../mixins/showModal'
export default{
	components:{Back,TableDiv,Tabs,InputDiv,SelectedItems},
	mixins:[readFromRfid,message_success,message_error,goTo,showModal],
	props:{
		info:{
			type:Object,
			default(){
				return {}
			}
		}
	},
	computed:{
		selectable(){
			let selectable={
				available:false,
				button_title:'check_in',
				func:this.checkIn,
				if:this.selectable_if,
				showSelected:this.showSelected
			};
			if(this.state=='issuance'){
				selectable.available=true;
			}
			return selectable;
		},
		clickables(){
			let bool=false;
			if(this.state=='return'){
				bool=true;
			}
			return bool;
		},
		backgroundImage(){
			return this.user.photo
		},
		heads(){
			let heads=[];
			if(this.state=='issuance'){
				heads=[
				{
					name:'barcode',link:'barcode'
				},
				{
					name:'duration_in_days',link:'duration',
					class_func:this.duration_class_func,
					edit_icon:this.duration_edit_icon_func,
					display_func:this.duration_display_func,
					custom_func:this.duration_edit_func
					
				},
				{
					name:'title',link:'title'
				},
				{
					name:'author',link:'author'
				},
				{
					name:'status',link:'status',
					class_func:this.issue_status_class_func
				}
				]
			}
			else if(this.state=='return'){
				heads=[
				{
					name:'issue_date',link:'issue_date',is_date:true
				},
				{
					name:'due_date',link:'due_date',is_date:true
				},
				{
					name:'author',link:'authors'
				},
				{
					name:'barcode',link:'barcode'
				},
				{
					name:'inventory_number',link:'inv_id'
				},
				{
					name:'title',link:'title'
				},
				{
					name:'status',link:'status',
					class_func:this.status_class_func
				}
				]
			}
			else{
				heads=[
				{
					name:'issue_date',link:'issue_date',is_date:true
				},
				{
					name:'due_date',link:'due_date',is_date:true
				},
				{
					name:'author',link:'authors'
				},
				{
					name:'barcode',link:'barcode'
				},
				{
					name:'inventory_number',link:'inv_id'
				},
				{
					name:'title',link:'title'
				},
				{
					name:'delivery_date',link:'delivery_date',is_date:true
				},
				{
					name:'status',link:'status',
					class_func:this.status_class_func
				}
				]
			}
			return heads
		},
		data(){
			let data=[];
			if(this.state=='history'){
				data=this.user.history
			}
			else if(this.state=='issuance'){
				data=this.books
			}
			else{
				data=this.user.return
			}
			return data;
		}
	},
	data(){
		return{
			user:{},
			components:[
			{
				name:'issuance'
			},
			{
				name:'return'
			},
			{
				name:'history'
			}],
			state:'issuance',
			user_info:{
				leftArray:[],
				rightArray:[]
			},
			barcode:'',
			books:[],
			custom_func:{
				title:'return',
				class:'outline-green',
				func:this.checkOut,
				available:true
			}
		}
	},
	methods:{
		showSelected(books,func){
			let props={
				heads:this.heads,
				data:books,
				selectable:this.selectable,
				commit:this.commit,
				link:this.link,
				pagination:false,
				sortable:false
			}
			if(func!=undefined){
				props.func=func;
			}
			this.showModal(SelectedItems,props);
		},
		issue_status_class_func(info){
			let res={};
			if (info.status=='not borrowed'){
				res['text-green']=true;
			}
			else{
				res['text-red']=true;
			}
			return res;
		},
		status_class_func(info){
			let res={};
			if (info.status=='issued'){
				res['text-orange']=true;
			}
			else if(info.status=='returned'){
				res['text-green']=true;
			}
			else{
				res['text-red']=true;
			}
			return res;
		},
		duration_class_func(info){
			return{
				'cursor-pointer':info.status!='borrowed'
			}
		},
		duration_edit_icon_func(info){
			return info.status!="borrowed";
		},
		duration_display_func(info){
			let before = new Date();
			let answer = ''
			let duration=copy(info.duration);
			console.log(duration)
			before.setDate(before.getDate()+parseInt(duration));
			
			answer+=before.toDateInputValue() + ' ( '+duration+' ) ';
			return answer;
		},
		duration_edit_func(info){
			if(info.status!='borrowed'){
				this.showModal(EditDuration,{info});
			}
		},
		capitalize(string){
			return capitalize(string);
		},
		objectWithoutKey(string,key){
			return objectWithoutKey(string,key);
		},
		tabOnClick(tab){
			this.state=tab.name.toLowerCase();
		},
		selectable_if(info){
			return info.status!='borrowed'
		},
		makeUserInfo(){
			let even=[];
			let odd=[];
			let index=0;
			for(let [key,value] of Object.entries(objectWithoutKey(this.user.info,'user_cid'))){
				if(index%2==0){
					even.push({key:key,value:value});
				}
				else{
					odd.push({key:key,value:value});
				}
				index++
			}
			this.user_info.leftArray=even;
			this.user_info.rightArray=odd;
		},
		getInfo(){
			this.$http.get('service/user/'+this.info.type+'/'+this.info.id).then(response=>{
				this.user=response.data.res;
				this.user.info=objectWithoutKey(this.user.info,'id');
				this.makeUserInfo();
			}).catch(e=>{
				this.message_error('loading',error)
				this.goTo('users');
			})
		},
		addDurationToBooks(array){
			array.forEach(book=>{
				if(book.duration==null){
					book.duration=this.user.duration;
				}
			})
		},
		search(){
			this.$store.commit('setFullPageLoading',true);
			this.$http.get('service/media/search/by-inventory?barcodes[]='+this.barcode).then(response=>{
				this.addDurationToBooks(response.data.res);
				this.addToBooks(response.data.res);
			}).catch(e=>{
				this.message_error('search',e);
			}).then(()=>{
				this.$store.commit('setFullPageLoading',false);
			});
		},
		async searchAllBarcodes(){
			this.$store.commit('setFullPageLoading',true);
			let barcodes=''
			this.books.forEach(book=>{
				barcodes+='barcodes[]='+book.barcode+'&'
			})
			barcodes=barcodes.split('');
			barcodes.splice(-1,1);
			barcodes=barcodes.join('');
			await this.$http.get('service/media/search/by-inventory?'+barcodes).then(response=>{
				this.books=response.data.res;
				this.addDurationToBooks(this.books);
			}).catch(error=>{
				this.message_error('loading',error);
			}).finally(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
		},
		addToBooks(books){
			this.books=this.books.concat(books);
		},
		checkIn(selected){
			let check_in=async (selected)=>{
				this.$store.commit('setFullPageLoading',true);
				try{
					let info ={
						loan_id:0,
						inv_id:selected[0].inv_id,
						user_cid:this.user.info.user_cid,
					};
					if(selected[0].due_date){
						info.due_date=selected[0].due_date;
					}
					else if ( selected[0].duration ){
						info.duration=selected[0].duration;
					}
					await this.readFromRfid('SetItemsCheckInOut','status=0');
					await this.$http.post('service/media/give',info).then(response=>{
						this.message_success('check_in ',response);
					});
					this.books=[]
					await this.getInfo();
					// await this.searchAllBarcodes();
					this.$eventHub.$emit('selectRefresh');
				}catch(e){
					if(e!="rfid problem"){
						this.message_error('check_in',e);
					}
				}finally{
					this.$store.commit('setFullPageLoading',false);
				}
			}
			this.showSelected(selected,check_in);
		},
		async checkOut(book){
			this.$store.commit('setFullPageLoading',true);
			try{
				let info ={
					loan_id:book.loan_id,
					inv_id:book.inv_id,
					user_cid:this.user.info.user_cid
				};
				await this.readFromRfid('SetItemsCheckInOut','status=1');
				await this.$http.post('service/media/back',info).then(response=>{
					this.message_success('check_out',response);
				});
				await this.getInfo();
			}catch(e){
				if(e.message!="rfid problem"){
					this.message_error('check_out',e);
				}
			}finally{
				this.$store.commit('setFullPageLoading',false);
			}
		},
		getRfidInfo(){
			this.$store.commit('setFullPageLoading',true);
			try{
				this.getRfidBarcode();
				this.search();
			}finally{
				this.$store.commit('setFullPageLoading',false);
			}
			
		},
		getRfidBarcode(){
			this.readFromRfid('getItemIDS','',(json)=>{
				this.barcode = json.ArrayOfResponse.Response.Result['_text'];
			});
		}
	},
	created(){
		this.getInfo();
	}
}
</script>
<style scoped>
.image{
	background-repeat: no-repeat;
	background-size: 100% 100%;
}
.imageWidth{
	width:14em;
}
.imageHeight{
	height: calc(14em * 4/3);
}
.info_table{
	width: 100%;
	border-spacing: .625em 0;
	border-collapse: separate;
}
.info_table > tr >td{
	vertical-align: top;
}
</style>
