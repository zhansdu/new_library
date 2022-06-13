<template>
	<form @submit.prevent='move(move_page)' class="d-flex align-items-center">
		<div>
			<!-- whoash! I couldn't do " data.from ?? 0 "  -->
			{{$t('showing_pages',{total:data.total,from:data.from ? data.from : 0,to:data.to ? data.to : 0})}}
		</div>
		<div class="d-flex align-items-center ml-4">
			<button type="button" class="button left_button bg-lightgrey" :class="data.current_page==1 ? 'text-grey cursor-unset':'text-black cursor-pointer'" @click="move(data.current_page-1)">
				<span>
					<ArrowDown />
				</span>
			</button>
			<button type="button" class="button right_button bg-lightgrey ml-1" :class="data.current_page==data.last_page ? 'text-grey cursor-unset':'text-black cursor-pointer'" @click="move(data.current_page+1)">
				<span>
					<ArrowDown />
				</span>
			</button>
			<form @submit.prevent="move(move_page)" class="d-flex align-items-center ml-4">
				<input type="text" v-model='move_page' class="left_button border-grey col-3 height-unset pl-2" :placeholder="$t('page')"/>
				<button type="submit" class="right_button width-unset border-grey bg-grey text-black">{{$t('move_to')}}</button>
				<div class="ml-3">{{$t('page_num',{num:data.current_page,last:data.last_page})}}</div>
			</form>
		</div>
	</form>
</template>
<script type="text/javascript">
// mixins
import {last} from "..//mixins/common"

//icons
import ArrowDown from "../../common/assets/icons/ArrowDown"
export default{
	mixins:[last],
	props:{
		link:String,
		commit:String
	},
	components:{ArrowDown},
	computed:{
		data(){
			return this.$store.state[this.commit].data.res;
		}
	},
	data(){
		return{
			move_page:''
		}
	},
	methods:{
		async move(num){
			if(num>0 && num<=this.data.last_page){
				await this.$store.dispatch('setStore',{label:this.commit,data:{page:num}});
				this.last(this.link,this.commit);
			};
		}
	}
}
</script>
<style scoped>
.ml-1{
	margin-left: .1em;
}

.current{
	border:0.125em solid grey;
}

.button{
	padding:0;
	width:2.5em;
	height:2.5em;
}

.left_button{
	border-top-right-radius: 0;
	border-bottom-right-radius: 0;
}

.left_button > span > * {
	transform: rotate(-90deg);
}

.right_button > span > * {
	transform: rotate(90deg);
}

.button > span > * {
	font-size: 1.5em
}

.right_button{
	border-top-left-radius: 0;
	border-bottom-left-radius: 0;
}

input{
	border-right:none;
}

</style>