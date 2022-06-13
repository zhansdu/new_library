<template>
	<div class="d-flex flex-column align-items-center justify-content-center">
		<div class="font-weight-bold">{{$t('confirmation')}}</div>
		<div class="d-flex justify-content-between mt-1">
			<button type="button" class="outline-red" @click="deleteIt()">{{$t('yes')}}</button>
			<button type="button" class="outline-black ml-1" @click="$emit('close')">{{$t('no')}}</button>
		</div>
	</div>
</template>
<script type="text/javascript">
// identication in sublime text3
import {last} from '..//mixins/common'
import {message_success,message_error} from '../mixins/messages'
export default{
	props:{link:String,commit:String,id:String,afterDelete:Function},
	mixins:[last,message_success,message_error],
	methods:{
		deleteIt(){
			if(this.link){
				this.$http.delete(this.link+'/delete/'+this.id).then(response=>{
					this.last(this.link,this.commit);
					try{
						this.afterDelete();
					}catch(e){}
					this.message_success('delete',response)
				}).catch(error=>{
					this.message_error('delete',error)
				}).then(()=>{
					this.$emit('close');
				})
			}
			else{
				this.afterDelete();
			}
		}
	}
}
</script>
