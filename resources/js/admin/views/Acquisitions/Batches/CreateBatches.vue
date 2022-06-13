<template>
	<form @submit.prevent="save()">
		<!-- it's used for both create and edit -->
		<div class="title mt-0">{{$t((edit ?'edit':'create')+'_batches')}}</div>
		
		<div class="d-flex">
			<div class="pad w-100">
				<input type="string" v-model="batch.cost" required />
				<!-- placeholder is needed only when it's required or we really need a placeholder -->
				<label class="placeholder required">{{$t('cost')}}</label>
			</div>
			<div class="pad w-100 select">
				<select v-model="batch.sup_type">
					<option value=''>&nbsp;</option>
					<option value="D">Donated</option>
					<option value="B">Bought</option>
				</select>
				<label class="placeholder">{{$t('type_of_supply')}}</label>
			</div>
		</div>
		<div class="d-flex">
			<div class="d-flex w-100">
				<div class="pad w-100">
					<input type="date" v-model="batch.invoice_date" required/>
					<label class="placeholder required">{{$t('invoice_date')}}</label>
				</div>
			</div>
			<div class="d-flex w-100">
				<div class="pad w-100 select">
					<select v-model="batch.sup_id">
						<option value=''>&nbsp;</option>
						<option v-for="(supply,index) in suppliers" :value="supply.id" :key="index">{{supply.name}}</option>
					</select>
					<label class="placeholder">{{$tc('suppliers',1)}}</label>
				</div>
				<div class="pad col-2">
					<button type="button" class="outline-orange px-0" @click="showModal(CreateSupplier,{afterSave:loadSuppliers})">{{$t('create')}}</button>
				</div>
			</div>
		</div>
		<div class="subtitle">{{$t('count')}}</div>
		<div class="d-flex">
			<div class="pad w-100">
				<input type="string" v-model="batch.items_no" required/>
				<label class="placeholder required">{{$t('quantity_items')}}</label>
			</div>
			<div class="pad w-100">
				<input type="string" v-model="batch.titles_no" required />
				<label class="placeholder required">{{$t('quantity_titles')}}</label>
			</div>
		</div>
		<div class="subtitle">{{$t('by_contract')}}</div>
		<div class="d-flex">
			<div class="pad w-100">
				<input type="string" v-model="batch.doc_no" required />
				<label class="placeholder required">{{$t('document_number')}}</label>
			</div>
			<div class="pad w-100">
				<input type="string" v-model="batch.contract_no"/>
				<label class="placeholder">{{$t('contract_number')}}</label>
			</div>
		</div>
		<div class="d-flex">
			<div class="pad w-100">
				<textarea v-model="batch.invoice_details"/>
				<label class="placeholder">{{$t('invoice_details')}}</label>
			</div>
			<div class="pad w-100 d-flex justify-content-end align-items-end">
				<div class="pad">
					<button type="submit">{{$t('save')}}</button>
				</div>
				<div class="pad">
					<button type="button" class="cancel-button" @click="cancel()">{{$t('cancel')}}</button>
				</div>
			</div>
		</div>
	</form>
</template>
<script type="text/javascript">
// identication in sublime text 3
import showModal from '../../../mixins/showModal'
import {last,last_created,create_it,edit_it} from '../../../mixins/common'

import CreateSupplier from '../Supplier/CreateSupplier'

// mixins
import { message_error } from '../../../mixins/messages'

export default{
	mixins:[showModal,last,last_created,create_it,edit_it,message_error],
	props:{
		edit:Boolean,
		data:Object,
		afterSave:Function
	},
	data(){
		return{
			CreateSupplier:CreateSupplier,
			batch:{
				invoice_date:null,
				items_no:null,
				titles_no:null,
				doc_no:null,
				sup_type:null,
				sup_id:null,
				contract_no:null,
				invoice_details:null,
				cost:null
			},
			suppliers:[],
			link:'/batch',
			commit:'batches'
		}
	},
	methods:{
		cancel(){
			this.$emit('close');
		},
		save(){
			if(!this.$store.state.fullPageLoading){
				this.$store.commit('setFullPageLoading',true);
				if(this.edit){
					this.batch.batch_id=this.data.id;
					this.editIt();
				}
				else{
					this.createIt();
				}
			}
		},
		editIt(){
			this.edit_it(this.link,this.commit,this.batch,this.afterSave,this.last);
		},
		createIt(){
			this.create_it(this.link,this.commit,this.batch,this.afterSave,this.last_created);
		},
		loadSuppliers(value){
			this.batch.sup_id=value;
			this.$store.commit('setFullPageLoading',true);
			this.$http.get('/supplier/names').then(response=>{
				this.suppliers=response.data.res;
			}).catch(error=>{
				this.message_error('loading',error)
			}).then(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
		}
	},
	created(){
		this.loadSuppliers();
		if(this.edit){
			this.batch=copy(this.data);
			this.batch.sup_type=copy(this.batch.sup_key);
			if(this.batch.invoice_date){
				this.batch.invoice_date=new Date(this.batch.invoice_date).toDateInputValue();
			}
		}
	}
}
</script>
<style scoped>
.subtitle,.pad{
	margin-top:0.5em;
}
</style>