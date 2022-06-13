<template>
	<form @submit.prevent="save()">
		<!-- this template is has 3 options in the same time : edit, create and recreate -->
		<div class="title mt-0">{{$t((edit ?'edit':reCreate ? 'reCreate':'create')+'_items') }}</div>
		<div class="d-flex">
			<div class="d-flex w-100">
				<div class="pad w-100">
					<input-div 
						v-model="item.title"
						:autocomplete="{available:true,func:this.autocomplete('title')}"
						:showBody="false"
						:disabled="(edit || reCreate)"
						:required="!(edit || reCreate)"
					/>
					<!-- it's required on create only  -->
					<label class="placeholder" :class="{required:!(edit || reCreate)}">{{$tc('titles',1)}}</label>
				</div>
			</div>
			<div class="d-flex w-100">
				<div class="pad w-100">
					<input-div 
						v-model="item.batch_id"
						:selectable="{available:true,data:batches}"
						:head="'id'"
						:body="'id'"
						:autocomplete="{available:true,data:batches}"
						:showBody="false"
						:disabled="edit"
						:required="!edit"
					/>
					<label class="placeholder" :class="{required:!edit}">{{$tc('batches',1)}}</label>
				</div>
				<div class="pad col-2" v-if="!edit">
					<button class="outline-orange px-0" type="button" @click="showModal(CreateBatches,{afterSave:loadBatches})" :disabled="edit">{{$t('create')}}</button>
				</div>
			</div>
		</div>
		<div class="d-flex">
			<div class="d-flex w-100">
				<div class="pad w-100">
					<input-div 
						maxHeight="15em"
						v-model="item.author"
						:autocomplete="{available:true,func:this.autocomplete('author')}"
						:showBody="false"
						:disabled="(edit || reCreate)"
						:required="!(edit || reCreate)"
					/>
					<label class="placeholder" :class="{required:!(edit || reCreate)}">{{$t('author')}}</label>
				</div>
			</div>
			<div class="d-flex w-100">
				<div class="pad w-100">
					<input-div 
						maxHeight="15em"
						v-model="item.publisher_id"
						:selectable="{available:true,data:publishers}"
						:head="'name'"
						:body="'id'"
						:autocomplete="{available:true,data:publishers}"
						:disabled="(edit || reCreate)"
						:required="!(edit || reCreate)"
					/>
					<label class="placeholder" :class="{required:!(edit || reCreate)}">{{$tc('publishers',1)}}</label>
				</div>
				<div class="pad col-2" v-if="!(edit || reCreate)">
					<button type="button" class="outline-orange px-0" @click="showModal(CreatePublisher,{afterSave:loadPublishers})">{{$t('create')}}</button>
				</div>
			</div>
		</div>
		<div class="d-flex">
			<div class="d-flex w-100">
				<div class="pad w-100">
					<input-div 
						maxHeight="10em"
						v-model="item.pub_city"
						:autocomplete="{available:true,func:this.autocomplete('city')}"
						:showBody="false"
						:disabled="(edit || reCreate)"
						:required="!(edit || reCreate)"
					/>
					<label class="placeholder" :class="{required:!(edit || reCreate)}">{{$t('pub_city')}}</label>
				</div>
				<div class="pad w-100">
					<input-div 
						maxHeight="10em"
						v-model="item.prog_code"
						:autocomplete="{available:true,data:this.specialities}"
						:body="'prog_code'"
						:head="'title'"
						:showBody="false"
						:disabled="(reCreate)"
						:selectable="{available:true,data:this.specialities}"
					/>
					<label class="placeholder">{{$t('speciality')}}</label>
				</div>
			</div>
			<div class="d-flex w-100">
				<div class="pad w-100">
					<input-div
						maxHeight="15em"
						type="number"
						:min="years.min"
						:max="years.max"
						:step="years.step"
					
						v-model="item.pub_year"
					
						:classes="years_valid ? '':'border-red'"
						:selectable="{available:true,data:years.array}"
						:autocomplete="{available:true,data:years.array}"
						:showBody="false"
						:disabled="(edit || reCreate)"
						:required="!(edit || reCreate)"
					/>
					<label class="placeholder" :class="{required:!(edit)}">{{$t('pub_year')}}</label>
				</div>
			</div>
		</div>
		<div class="d-flex">
			<div class="d-flex w-100">
				<div class="pad w-100">
					<input type="text" :required="!(edit)" v-model="item.count" :disabled="(edit)">
					<label class="placeholder" :class="{required:!(edit)}">{{$t('count')}}</label>
				</div>
				<div class="pad w-100">
					<input type="text" v-model="item.cost" required>
					<label class="placeholder required">{{$t('cost')}}</label>
				</div>
			</div>
			<div class="d-flex w-100">
				<div class="pad w-100 select">
					<select v-model="item.item_type" :required="!(edit || reCreate)" :disabled="(edit || reCreate)">
						<option value=''>&nbsp;</option>
						<option :value="item.item_type" v-if="(edit || reCreate)">{{item.item_type}}</option>
						<option v-for="(type,index) in support_data.types" :value="type.type_key" :key="index">{{type.type}}</option>
					</select>
					<label class="placeholder" :class="{required:!(edit || reCreate)}">{{$t('type_of_item')}}</label>
				</div>
			</div>
		</div>
		<div class="d-flex">
			<div class="d-flex w-100">
				<div class="pad w-100">
					<input type="text" v-model="item.isbn" :disabled="(edit || reCreate)" :required="!(edit || reCreate)">
					<label class="placeholder" :class="{required:!(edit || reCreate)}">{{$t('isbn')}}</label>
				</div>
				<div class="pad col-2" v-if="!(edit || reCreate)">
					<button type="button" class="px-0" @click="showIssn=!showIssn">{{showIssn?'-':'+'}}</button>
				</div>
			</div>
			<div class="d-flex w-100">
				<div class="pad w-100 select">
					<select v-model="item.currency" required>
						<option value=''>&nbsp;</option>
						<option v-for="(currency,index) in support_data.currencies" :value="currency.currency" :key="index">{{currency.currency}}</option>
					</select>
					<label class="placeholder required">{{$t('currency')}}</label>
				</div>
				<div class="pad w-100 select">
					<select v-model="item.location" required>
						<option value=''>&nbsp;</option>
						<option v-for="(location,index) in support_data.locations" :value="location.location_key" :key="index">{{location.location}}</option>
					</select>
					<label class="placeholder required">{{$t('location')}}</label>
				</div>
			</div>
		</div>
		<div class="d-flex col-6" v-if="showIssn || item.issn || item.volume">
			<div class="pad w-100">
				<input type="text" v-model="item.issn">
				<label class="placeholder">{{$t('issn')}}</label>
			</div>
			<div class="pad col-2">
				<input type="text" v-model="item.volume">
				<label class="placeholder">{{$t('volume')}}</label>
			</div>
		</div>

		<div class="mt-5 d-flex justify-content-end">
			<div class="pad">
				<button type="submit">{{$t('save')}}</button>
			</div>
			<div class="pad">
				<button type="button" class="cancel-button" @click="cancel()">{{$t('cancel')}}</button>
			</div>
		</div>
	</form>
</template>
<script type="text/javascript">
// identication in sublime text 3
import showModal from '../../../mixins/showModal'
import {last,last_created,create_it,edit_it,recreate_it} from '../../../mixins/common'

import CreateBatches from '../Batches/CreateBatches'
import CreatePublisher from '../Publisher/CreatePublisher'

import InputDiv from '../../../components/Input'
export default{
	mixins:[showModal,last,last_created,create_it,edit_it,recreate_it],
	components:{
		InputDiv
	},
	props:{
		edit:Boolean,
		data:Object,
		afterSave:Function,
		reCreate:Boolean,
	},
	computed:{
		years(){
			const range = (start, stop, step) => Array.from({ length: (stop - start) / step + 1}, (_, i) => start + (i * step));

			const max=(new Date()).getFullYear();
			const min=1900;
			const step=1;
			
			return {min:min,max:max,step:step,array:range(max, min , -step)} 
		},
		years_valid(){
			let year=this.item.pub_year;
			if( year!='' && year<this.years.min || year> this.years.max){
				return false
			}
			return true
		}
	},
	data(){
		return{
			CreateBatches:CreateBatches,
			CreatePublisher:CreatePublisher,
			item:{
				title:null,
				author:null,
				isbn:null,
				item_type:null,
				publisher_id:null,
				cost:null,
				user_cid:null,
				count:null,
				location:null,
				curredatancy:null
			},
			showIssn:false,
			support_data:[],
			specialities:[],
			batches:[],
			publishers:[],
			link:'/item',
			commit:'items'
		}
	},
	methods:{
		cancel(){
			this.$emit('close');
		},
		autocomplete(key){
			let vm=this;
			return (text)=>{
				return vm.$http.get(vm.link+'/autocomplete?key='+key+'&value='+text);
			}
		},
		// autocompleteCities(text){
		// 	return fetch('https://www.geobytes.com/AutoCompleteCity?q='+text,{
		// 		"mode":"no-cors",
		// 		"headers":{
		// 			"Access-Control-Allow-Origin":"*"
		// 		}
		// 	});
		// },
		save(){
			this.item.user_cid=this.$store.state.user.user_cid;
			if(!this.$store.state.fullPageLoading){
				this.$store.commit('setFullPageLoading',true);
				if(this.edit){
					this.editIt();
				}
				else if (this.reCreate){
					this.reCreateIt();
				}
				else{
					this.createIt();
				}
			}
		},
		editIt(){
			let item={
				inv_id:this.item.id,
				cost:this.item.cost,
				user_cid:this.item.user_cid,
				location:this.item.location,
				currency:this.item.currency,
				prog_code:this.item.prog_code
			};
			this.edit_it(this.link,this.commit,item,this.afterSave,this.last);
		},
		createIt(){
			let item={
				title:this.item.title,
				author:this.item.author,
				isbn:this.item.isbn,
				batch_id:this.item.batch_id,
				item_type:this.reCreate ? this.item.item_key : this.item.item_type,
				publisher_id:this.item.publisher_id,
				cost:this.item.cost,
				user_cid:this.item.user_cid,
				count:this.item.count,
				location:this.item.location,
				currency:this.item.currency,
				pub_city:this.item.pub_city,
				pub_year:this.item.pub_year,
				prog_code:this.item.prog_code
			};
			this.create_it(this.link,this.commit,item,this.afterSave,this.last_created);
		},
		reCreateIt(){
			let item={
				book_id:this.item.book_id,
				j_issue_id:this.item.j_issue_id,
				disc_id:this.item.disc_id,
				batch_id:this.item.batch_id,
				cost:this.item.cost,
				count:this.item.count,
				location:this.item.location,
				currency:this.item.currency,
				user_cid:this.item.user_cid
			};
			this.recreate_it(this.link,this.commit,item,this.afterSave,this.last_created);
		},
		loadBatches(value){
			this.item.batch_id=value;
			this.$store.commit('setFullPageLoading',true);
			this.$http.get('/batch/numbers').then(response=>{
				this.batches=response.data.res;
			}).catch(error=>{
				this.message_error('loading',error);
			}).then(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
		},
		loadPublishers(value){
			this.item.publisher_id=value;
			this.$store.commit('setFullPageLoading',true);
			this.$http.get('/publisher/names').then(response=>{
				this.publishers=response.data.res;
			}).catch(error=>{
				this.message_error('loading',error);
			}).then(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
		},
		loadSupportData(){
			this.$store.commit('setFullPageLoading',true);
			this.$http.get(this.link+'/create-data').then(response=>{
				this.support_data=response.data.res;
				this.item.currency='KZT';
			}).catch(error=>{
				this.message_error('loading',error);
			}).then(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
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
		}
	},
	created(){
		this.loadBatches();
		this.loadPublishers();
		this.loadSupportData();
		this.loadSpecialities();

		if(this.edit || this.reCreate){
			this.item=copy(this.data);
			if(this.item.invoice_date){
				this.item.invoice_date=new Date(this.item.invoice_date).toDateInputValue();
			}
		}
	}
}
</script>
<style scoped>
.pad{
	margin-top: .5em;
}
</style>
