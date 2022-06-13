<template>
	<form class="d-flex flex-column justify-content-around align-items-center h-100" @submit.prevent="login">
		<div class="font-size-24 font-weight-bold">{{$t('login')}}</div>
		<div class="text-grey mt--2">* {{$t('portal_credentials')}}</div>
		<input class="py-2" v-model="request.username" autocomplete="off" :placeholder="$t('username')" />		
		<input type="password" class="py-2" v-model="request.password" autocomplete="off" :placeholder="$t('login_password')" />		
		<button type="submit" class="font-size-18">{{$t('login')}}</button>
	</form>
</template>
<script type="text/javascript">
	import {message_success,message_error} from '../../../mixins/messages'
	export default{
		mixins:[message_success,message_error],
		data(){
			return{
				request:{
					username:'',
					password:''
				}
			}
		},
		methods:{
			login(){
				this.$store.commit('setFullPageLoading',true);
				this.$http.post('login', this.request,{api:false}).then(response=>{
					this.$store.dispatch('login',response.data.res);
					this.message_success('login',response);
					this.$emit('close');
				}).catch(error=>{
					this.message_error('login',error);
				}).then(()=>{
					this.$store.commit('setFullPageLoading',false);
				});
			}
		}
	}
</script>
<style scoped>
.mt--2{
	margin-top: -0.5em;
}
input{
	font-size: 1.125em;
	width: 100%;
}
</style>