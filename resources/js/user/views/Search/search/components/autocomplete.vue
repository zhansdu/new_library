<template>
	<form class="text-black" @submit.prevent="submitting()" @keyup="onKeyPress($event)">
		<input type="text" autocomplete="off" class="h-100 w-100" 
		:class="input_classes"
		:placeholder="placeholder || $t('search')"
		@input="commitQuery()"
		@blur="closeResults()"
		v-model="query"
		/>
		<div ref="results" class="position-absolute results bg-white shadow-sm w-100 mt-2">
			<div class="p-2" :class="{'bg-lightgrey':chooseIndex==index}" @mouseover='chooseIndex=index' v-for="(result,index) in results" :key="index" @click="Choose();commitQuery();closeResults();">
				<div class="d-flex">
					<span class="text-ellipsis mr-10">
						{{result.result}}
						<span v-if="result.title">, {{result.title}}</span>
						<span v-if="result.year">, {{result.year}}</span>
					</span>
					<span class="ml-auto cursor-pointer" @click="move(result)"><book /> &nbsp;--></span>
				</div>
			</div>
		</div>
	</form>
</template>
<script type="text/javascript">
// icons
import Book from '../../../../../common/assets/icons/Book'

export default{
	model: {
		prop: 'value',
		event: 'input'
	},
	props:{
		placeholder:String,
		value:[Object,String],
		submit_method:Function,
		input_classes:[String,Array],
		results:Array,
	},
	components:{Book},
	watch:{
		'value'(newVal){
			this.query=newVal;
			if(this.query.length>0){
				this.showResults();
			}
			else{
				this.closeResults();
			}
		}
	},
	data(){
		return {
			query:'',
			chooseIndex:null
		};
	},
	methods:{
		commitQuery(){
			this.$emit('input',this.query);
		},
		closeResults(){
			setTimeout(()=>{
				this.chooseIndex=null;

				let doc=this.$refs.results;
				doc.style.maxHeight='0px';

			},200);
		},
		showResults(){
			let doc=this.$refs.results;
			doc.style.maxHeight=(window.innerHeight-doc.getBoundingClientRect().top)+'px'; 
		},
		submitting(){
			try{
				this.$store.getters.request.cancel();
			}
			catch(e){}
			if(this.submit_method){
				this.submit_method();
			}
			this.closeResults();
		},
		onKeyPress(event){
			if(event.keyCode==38){
				if(this.chooseIndex==null){
					this.chooseIndex=0;

				}
				else{
					if(this.chooseIndex>0){
						this.chooseIndex-=1;
					}
				}
				this.Choose();
			}
			else if(event.keyCode==40){
				if(this.chooseIndex==null){
					this.chooseIndex=0;
				}
				else{
					if(this.chooseIndex<this.results.length-1){
						this.chooseIndex+=1;
					}
				}
				this.Choose();
			}
		},
		Choose(){
			try{
				this.query = this.results[this.chooseIndex].result;
			}catch(e){}
		},
		move(result){
			this.$router.push({path:'/book_full?id=' + result.id});
		}
	}
}
</script>
<style scoped>
.results{
	min-width: 25em;
	top: 102%;
	left: 0;
	z-index: 1000;
	overflow: hidden;
	overflow-y: auto;
	max-height: 0;
}
input::placeholder{
	color: #9C9FA7, 100%;
}
</style>
