<template>
	<div tabindex="0" class="position-relative d-flex align-items-center rounded border" @focusout="num=0;show()">
		<div class="d-flex justify-content-between align-items-center cursor-pointer w-100" :class="labelClasses" @click="showIt()">
			<label>{{$t((label ? value[label] : value ) || data[0])}}</label>
			&nbsp;
			<div class="rotate"><CaretUp/></div>
		</div>
		<div ref="select" class="results bg-white shadow-sm pl-3 transition">
			<div class="py-2 cursor-pointer" :class="{'border-bottom':index!=data.length-1}" v-for="(info,index) in data" :key="index" @click="onClick(info)">{{label ? $t(info[label]) : $t(info)}}</div>
		</div>
	</div>
</template>
<script type="text/javascript">
// icons
import CaretUp from '../../common/assets/icons/CaretUp'

export default{
	model:{
		event:'change',
		prop:'value'
	},
	props:{
		value:[Object,String],
		data:Array,
		placeholder:String,
		// label - what is shown as an option
		label:String,
		labelClasses:[String,Array],
		disabled:Boolean,
	},
	components:{CaretUp},
	data(){
		return{
			num:0
		}
	},
	methods:{
		show(){
			let doc=this.$refs.select;
			doc.style.maxHeight=this.num+'px';
		},
		showIt(){
			if(!this.disabled){
				if(this.num==0){
					let doc=this.$refs.select;
					this.num=(window.innerHeight-(doc.getBoundingClientRect().bottom))>250 ? 250 : (window.innerHeight-doc.getBoundingClientRect().bottom) ;
				}
				else{
					this.num=0;
				}
				this.show();
			}
		},
		onClick(info){
			if(!this.disabled){
				this.$emit('change',info);
				this.num=0;
				this.show();
			}
		}
	},
	created(){
		try{
			this.$emit('change',this.data[0]);
		}catch(e){}
	}
}
</script>
<style scpoed>
.results{
	position: absolute;
	top:102%;
	width: 100%;
	left:0;
	z-index: 1000;
	overflow: hidden;
	overflow-y:auto;
	max-height: 0;
}
</style>