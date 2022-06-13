<template>
	<div class="text-choosable">
		<div class="d-flex justify-content-between align-items-center">
			<div class="font-weight-bold">{{$tc(tableName.name,1)}}</div>
			<div class="font-weight-bold cursor-pointer p-3" @click="close"><X /></div>
		</div>
		<table class="w-100">
			<tr class="font-size-14" v-for="(head,index) in heads" :key="index">
				<td class="text-grey">{{$tc(head.name,1)}} :</td>
				<td class="pl">
					<span v-if="head.display_func">
						{{head.display_func(data)}}
					</span>
					<span v-else>
						{{data[head.link]}}
					</span>
				</td>
			</tr>
		</table>
		<div class="mt-3 d-flex justify-content-end">
			<div class="pad" v-if="editObj">
				<button type="button" class="outline-black" @click="editit()">
					<span><Edit /></span>
					{{$t('edit')}}
				</button>
			</div>
			<div class="pad ml-2" v-if="deleteObj">
				<button type="button" class="outline-black" @click="deleteit()">
					<span><Delete /></span>
					{{$t('delete')}}					
				</button>
			</div>
			<div class="pad ml-2" v-if="custom_func">
				<button type="button" class="outline-black" :class="custom_func.class" @click="custom_method()">
					{{$t(custom_func.title)}}
				</button>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
// components
import DeleteModal from './DeleteModal'
// icons
import X from '../../common/assets/icons/X'
import Edit from '../../common/assets/icons/Edit'
import Delete from '../../common/assets/icons/Delete'
// mixins
import showModal from '../mixins/showModal'

export default{
	props:{
		data:Object,
		heads:Array,
		status:{
			type:Boolean,
			default:()=>{return false}
		},
		tableName:{
			type:Object,
			default(){
				return {name:'table'}
			}
		},
		editObj:Object,
		deleteObj:Object,
		custom_func:Object,
		link:String,
		commit:String
	},
	components:{X,Edit,Delete},
	mixins:[showModal],
	data(){
		return{
			DeleteModal:DeleteModal
		}
	},
	methods:{
		capitalize(string){
			return capitalize(string);
		},
		close(){
			this.$emit('close');
		},
		editit(){
			let props={
				data:this.data,
				lastCreated:this.editObj.lastCreated,
				edit:true,
				afterSave:this.close
			};
			this.showModal(this.editObj.component,props);
		},
		deleteit(){
			this.showModal(this.DeleteModal,{link:this.link,commit:this.commit,id:this.data.id,afterDelete:this.close,width:'41.6%'});
		},
		custom_method(){
			this.custom_func.func(this.data);
			if(this.custom_func.close){
				this.close();
			}
		}
	}
}
</script>
<style scoped>
.p-3{
	padding-right: 0;
	padding-top: 0;
}
.bg-grey{
	background-color: #E8E8E8;
}
td{
  padding-top: 1.25em;
}
.pl{
	padding-left: 1.25em; 
}
</style>