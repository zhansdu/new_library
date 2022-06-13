<template>
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
</template>
<script type="text/javascript">

// mixins
import readFromRfid from '../../../mixins/readFromRfid'
import {message_success,message_error} from '../../../mixins/messages'
import {goTo} from '../../../mixins/goTo'
import showModal from '../../../mixins/showModal'
export default{
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
		backgroundImage(){
			return this.user.photo
		},
	},
	data(){
		return{
			user:{},
			user_info:{
				leftArray:[],
				rightArray:[]
			},
		}
	},
	methods:{
		capitalize(string){
			return capitalize(string);
		},
		objectWithoutKey(string,key){
			return objectWithoutKey(string,key);
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
            this.$http.get('service/user/'+this.info.user_cid).then(response=>{
				this.user=response.data.res;
				this.user.info=objectWithoutKey(this.user.info,'id');
				this.makeUserInfo();
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
