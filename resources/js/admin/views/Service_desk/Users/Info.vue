<template>
	<div class="text-choosable">
		<div class="d-flex justify-content-between align-items-center">
			<div class="font-weight-bold">{{$t('more_info')}}</div>
			<div @click="$emit('close')">
				<span class="cursor-pointer font-size-20"><X /></span>
			</div>
		</div>
		<div class="d-flex mt-2">
			<div class="d-flex flex-column">
				<div class="p-3 rounded-lg bg-lightgrey">
					<div class="image imageWidth imageHeight" :style="'background-image: url('+backgroundImage+')'"/>
					<div class="mt-2 text-center">{{$t(type)}}</div>
				</div>
				<div class="py-2 mt-3 bg-lightgrey d-flex flex-column align-items-center">
					<div class="imageWidth d-flex justify-content-between" v-for="(value,key,index) in user.total" :key="index">
						<div :class="[{'text-green':index==0},{'text-orange':index==1},{'text-red':index==2}]">
							{{$t(key)+":"}}
						</div>
						<div>
							{{value}}
						</div>
					</div>
				</div>
			</div>
			<div class="bg-lightgrey rounded-lg p-3 ml-4 flex-fill" v-if='user.info'>
				<div class="d-flex" :class="{'mt-4':index!=0}" v-for="(value,key,index) in objectWithoutKey(user.info,'user_cid')" :key="index">
					<div class="text-grey">{{capitalize($t(key))}}:</div>
					<div class="ml-2">{{value}}</div>
					&nbsp;
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
// mixins
import {goTo} from '../../../mixins/goTo'
// icons
import X from '../../../../common/assets/icons/X'
import { message_error } from '../../../mixins/messages';

export default {
	props:{
		id:String,
		type:String
	},
	mixins:[goTo,message_error],
	components:{X},
	computed:{
		backgroundImage(){
			return this.user.photo;
		}
	},
	data(){
		return{
			user:{}
		}
	},
	methods:{
		capitalize(string){
			return capitalize(string);
		},
		objectWithoutKey(string,key){
			return objectWithoutKey(string,key);
		},
		getInfo(){
			this.$http.get('service/user/'+this.type+'/'+this.id).then(response=>{
				this.user=response.data.res;
				this.user.info=objectWithoutKey(this.user.info,'id');
			}).catch(e=>{
				this.message_error('loading',e)
				this.goTo('users');
			})
		},

	},
	created(){
		this.getInfo();
	}
}
</script>
<style scoped>
.placeholder{
	background-color: white;
}
input:disabled{
	color:black;
}
.image{
	background-repeat: no-repeat;
	background-size: 100% 100%;
}
.imageWidth{
	width:15.625em;
}
.imageHeight{
	height: calc(15.625em * 4/3);
}
</style>