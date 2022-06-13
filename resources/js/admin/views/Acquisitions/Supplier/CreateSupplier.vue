<template>
	<form @submit.prevent="save()">
		<div class="title">{{$t((edit ?'edit':'create')+'_supplier') }}</div>
		
		<div class="d-flex">
			<div class="pad">
				<input type="string" v-model="supplier.name" required />
				<label class="placeholder required">{{$t('name')}}</label>
			</div>
			<div class="pad">
				<input type="string" v-model="supplier.bin"/>
				<label class="placeholder">{{$t('bin')}}</label>
			</div>
		</div>
		<div class="d-flex">
			<div class="pad">
				<input type="string" v-model="supplier.com_name"/>
				<label class="placeholder">{{$t('commercial_name')}}</label>
			</div>
		</div>
		<div class="subtitle">{{$t('contact')}}</div>
		<div class="d-flex">
			<div class="pad">
				<input type="string" v-model="supplier.address"/>
				<label class="placeholder">{{$t('address')}}</label>
			</div>
			<div class="pad">
				<input type="string" v-model="supplier.email" />
				<label class="placeholder">{{$t('email')}}</label>
			</div>
		</div>
		<div class="d-flex">
			<div class="pad">
				<input type="string" v-model="supplier.phone"/>
				<label class="placeholder">{{$t('phone')}}</label>
			</div>
			<div class="pad">
				<input type="string" v-model="supplier.fax"/>
				<label class="placeholder">{{$t('fax')}}</label>
			</div>
		</div>
		<div class="flexible justify-content-end">
			<div class="pad">
				<button type="submit">{{$t('save')}}</button>
			</div>
			<div class="pad">
				<button type="button" class="cancel-button" @click="cancel">{{$t('cancel')}}</button>
			</div>
		</div>
	</form>
</template>
<script type="text/javascript">
// mixins
import {last,last_created,create_it,edit_it} from '../../../mixins/common'
export default{
	mixins:[last,last_created,create_it,edit_it],
	props:{
		edit:Boolean,
		data:Object,
		afterSave:Function,
	},
	data(){
		return{
			supplier:{
				name:null,
				bin:null,
				com_name:null,
				address:null,
				email:null,
				phone:null,
				fax:null
			},
			link:'/supplier',
			commit:'suppliers'
		}
	},
	methods:{
		save(){
			if(!this.$store.state.fullPageLoading){
				this.$store.commit('setFullPageLoading',true);
				this.supplier.sup_name=this.supplier.name;
				if(this.edit){
					this.supplier.sup_id=this.data.id;
					this.editIt();
				}
				else{
					this.createIt();
				}
			}
		},
		cancel(){
			this.$emit('close');
		},
		editIt(){
			this.edit_it(this.link,this.commit,this.supplier,this.afterSave,this.last);
		},
		createIt(){
			this.create_it(this.link,this.commit,this.supplier,this.afterSave,this.last_created);
		}
	},
	created(){
		if(this.edit){
			this.supplier=copy(this.data);
		}
	}
}
</script>
<style scoped>
.flexible{
	display: flex;
}
.d-flex>div{
	width:100%;
}
.pad{
	margin-top:0.5em;
}
</style>