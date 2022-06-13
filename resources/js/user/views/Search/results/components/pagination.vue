<template>
	<div id="pagination" class="d-flex">
		<div class="d-flex align-items-center bg-lightgrey rounded px-3 mr-3 mt-1">
			<div :class="{'cursor-pointer':data.current_page!=1}" @click="movePrev()">
				<right-little class="rotate" :class="{'text-grey':data.current_page==1}"/>
			</div>
			<div class="position-relative d-flex align-items-center text-grey mx-2">
				<div class="wrapper wrapperRect transition" />
				<div class="wrapper" v-for="(page,index) in pages" @click="getResults(page);" :key="index">{{page}}</div>
			</div>
			<div :class="{'cursor-pointer':data.current_page!=data.last_page}" @click="moveNext()">
				<right-little :class="{'text-grey':data.current_page==data.last_page}"/>
			</div>
		</div>
		<div class="d-none d-sm-flex align-items-center bg-lightgrey rounded px-3 mt-1">
			<div>
				{{$t('to_page')}} :
			</div>
			<form @submit.prevent="getResults(inputPage);" class="d-flex align-items-center ml-2">
				<input class="rounded border border-darkgrey text-darkgrey" type="text" v-model="inputPage"/>
				<button type="submit" class="text-black ml-3"><right-long /></button>
			</form>
		</div>
	</div>
</template>
<script type="text/javascript">
	import RightLittle from '../../../../../common/assets/icons/RightLittle'	
	import RightLong from '../../../../../common/assets/icons/RightLong'

	import {filters} from '../../../../mixins/search'

	export default {
		components:{RightLittle,RightLong},
		mixins:[filters],
		computed:{
			pages(){
				let range=this.data.last_page>5 ? (window.innerWidth>500 ? 5 : 4) : this.data.last_page;
				return this.paginate(range)
			},
			data(){
				return this.$store.getters.results;
			},
			wrapperIndex(){
				return this.$store.getters.wrapper_index;
			}
		},
		data(){
			return{
				inputPage:null,
			}
		},
		methods:{
			moveRect(){
				try{
					let rects=document.getElementsByClassName('wrapperRect');
					for(let rect of rects){
						rect.style.left=rect.offsetWidth*this.wrapperIndex+'px';
					}
				}
				catch(e){}
			},
			moveNext(){
				this.getResults(this.data.current_page+1);
			},
			movePrev(){
				this.getResults(this.data.current_page-1);
			},
			getResults(page){
				if(!(page>this.data.last_page || page<1)){
					this.$store.commit('setFullPageLoading',true);

					let search=this.$store.getters.search_request;
					let request= {
						search_options:search
					}

					this.setFilters(request);

					request.page=page;


					this.$http.post('media/search',request).then(response=>{
						this.$store.commit('setResults',response.data.res);
						this.$store.commit('setFullPageLoading',false);
					})
				}
			},
			paginate(range){

			// found this code in internet 

			// https://codereview.stackexchange.com/questions/183417/pagination-algorithm-in-js
			
			let currentPage = this.data.current_page;
			let totalPages  = this.data.last_page  
			let start       = 1; 
			let paging      = []; 

			if (currentPage < (range / 2) + 1 ) {
				start = 1;

			} else if (currentPage >= (totalPages - (range / 2) )) {
				start = Math.floor(totalPages - range + 1);

			} else {
				start = (currentPage - Math.floor(range / 2));
			}

			for (let i = start,move=0; i <= ((start + range) - 1); i++,move++) {
				if (i == currentPage) {
					if(move!=this.wrapperIndex){
						this.$store.commit('setWrapperIndex',move);
						this.moveRect();
					}
				}
				paging.push(i.toString());
			}
			return paging;
		}
	},
	mounted(){
		this.moveRect();
	}
}
</script>
<style scoped>
.wrapper{
	width:3.43em;
	height:3.125em;
	display: flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;
}
.wrapperRect{
	position: absolute;
	top:0;
	left:0;
	border-radius: 0.3125em;
	border: 0.09375em solid #FF9D29;

}
input{
	min-width: 0;
	padding: 0;
	text-align: center;
	width: 3.75em;
	height:1.875em;
}
button{
	padding: 0;
	background-color: transparent;
	border-color: transparent;
}
</style>