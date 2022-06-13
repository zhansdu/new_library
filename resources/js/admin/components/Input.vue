<template>
	<div class="position-relative h-100"  @submit.prevent="submitTotal" tabindex="1" @focusout="close()">
		<form class="position-relative h-100">
			<input 
				:type="type"
				class="text-ellipsis px-3" 
				:class="classes" 
				v-model="text" 
				@input="onInput()"
				:placeholder="placeholder"  
				:disabled="disabled" 
				:required="required"
				:min="min" 
				:max="max" 
				:step="step" 
			/>
			<span v-if="selectable.available" class="d-flex align-items-center icon cursor-pointer selectable">
				<span @click="showList()">
					<CaretUp class="down" />
				</span>
			</span>
			<span v-if="search" class="d-flex align-items-center icon cursor-pointer">
				<span @click="reset" v-if="value">
					<Cancel />
				</span>
				<span @click="submitTotal" v-else>
					<Search/>
				</span>
			</span>
		</form>
		<div class="results" :class="{'bordered':shown}" :style="'max-height:'+(shown? maxHeight : '0')">
			<div class="result text-ellipsis" :class="{no_border_bottom:index==results.length-1}" v-for="(result,index) in results" :key="index" @click="select(result)">{{$t(head ? result[head] : result)}}</div>
		</div>
	</div>
</template>
<script type="text/javascript">
// identication in sublime 3
// icons
import Search from '../../common/assets/icons/Search'
import Cancel from '../../common/assets/icons/Cancel'
import CaretUp from '../../common/assets/icons/CaretUp'
export default{
	model: {
		prop: 'value',
		event: 'input'
	},
	components:{Search,Cancel,CaretUp},
	props:{
		placeholder:{
			type:String,
			default:''
		},
		maxHeight:{
			type:String,
			default:'20em'
		},
		search:{
			type:Boolean,
			default:false
		},
		autocomplete:{
			type:Object,
			default(){return {available:false}}
		},
		selectable:{
			type:Object,
			default(){return {available:false}}
		},
		head:[String,null],
		body:[String,null],
		value:{
			type:[Object,String,Number,Boolean],
			default(){return null}
		},
		disabled:{
			type:Boolean,
			default:false
		},
		required:{
			type:Boolean,
			default:false
		},
		onSubmit:{
			type:Function,
			default:null
		},
		afterSubmit:{
			type:Function,
			default:null
		},
		classes:{
			type:[Array,String]
		},
		showBody:{
			type:Boolean,
			default:true
		},
		type:{
			type:String,
			default:'text'
		},
		min:{
			type:[String,Number],
		},
		max:{
			type:[String,Number]
		},
		step:{
			type:[String,Number]
		}
	},
	watch:{
		'value'(newValue){
			this.setText(newValue);
		},
		'selectable.data'(){
			this.setText(this.value);
		},
		'autocomplete.data'(){
			this.setText(this.value);
		}
	},
	data(){
		return{
			text:'',
			shown:false,
			results:[0],
			result:{},
			timer:0
		}
	},
	methods:{
		submitTotal(){
			if(!this.disabled){
				if(this.onSubmit!=null){
					this.onSubmit();
				}
				try{
					this.afterSubmit()
				}catch(e){}
			}
		},
		onInput(){
			if(this.autocomplete.available){
				if(this.autocomplete.data){
					let data=copy(this.autocomplete.data);
					if(this.head!=null){
						this.results=data.filter((item)=>{
							return item[this.head].toLowerCase().includes(this.text.toLowerCase())
						}).splice(0,200);
					}
					else{
						this.results=data.filter((item)=>{
							return (item+'').toLowerCase().includes(this.text.toLowerCase())
						}).splice(0,200);
					}
					this.results=data.filter((item)=>{
						if(this.head!=null){
							return item[this.head].toLowerCase().includes(this.text.toLowerCase())
						}
						return (item+'').toLowerCase().includes(this.text.toLowerCase())
					}).splice(0,200);
					this.shown=true;
				}
				else if(this.autocomplete.func){
					if(this.text.length>2){
						clearTimeout (this.timer);
						let vm=this;
						this.timer = setTimeout(()=>{
							vm.autocomplete.func(vm.text).then(response=>{
								try{
									vm.results=response.data.res;
								}catch(e){
									vm.results=response.body ?? response.data;
								}
								vm.shown=true;
							});
						}, 500);
					}
				}
				
			}
			this.$emit('input',this.text);
		},
		showList(){
			let data=copy(this.selectable.data);
			this.results=data.splice(0,200);
			this.show();
		},
		show(){
			this.shown=!this.shown;
		},
		close(){
			setTimeout(()=>{
				this.shown=false
			},100)	
		},
		select(result){
			let res=''
			if(this.body!=null){
				res=result[this.body];
			}
			else{
				res=result 
			}
			this.$emit('input',res);
			this.setText(res);
			this.shown=false;
		},
		reset(){
			this.$emit('input','');
		},
		setText(value){
			let result=[];
			if(this.selectable.available || (this.autocomplete.available && this.autocomplete.data!=null)){
				if(this.selectable.available){
					result = this.selectable.data;
				}
				else if( this.autocomplete.available){
					result = this.autocomplete.data;
				}
				if(this.body != null){
					result = result.filter(item=>item[this.body]==value)
				}
				else{
					result = result.filter(item=>item==value);
				}
				if(result.length > 0){
					if(this.head!=null){
						this.text = result[0][this.head]
					}
					else{
						this.text=result[0];
					}
					if(this.showBody){
						this.text += ' (' + result[0][this.body] + ')';
					}
				}
				else{
					this.text=value;
				}
			}
			else{
				this.text=value;
			}
		}
	},
	created(){
		this.setText(this.value);
	}
}
</script>
<style scoped>
.icon{
	font-size: 1.2em;
	position: absolute;
	top:0;
	height:100%;
	right:1.25em;
}
.selectable{
	/*hard..*/	
	right: .86em;
}
.padding-right{
	padding-right: 2.3em !important;
}
.down{
	transform: rotate(180deg);
}
.results{
	position: absolute;
	top:110%;
	width: 100%;
	background:white;
	overflow:auto;
	transition: .3s;
	border-radius: .3125em;
	z-index: 998;
	max-height: 0em;
}
.result{
	padding:.4em .625em;
	border-bottom:.03125em solid #B5BAC7;
	cursor: pointer;
}
.bordered{
	border:0.03125em solid #B5BAC7;
}
.result:hover{
	background-color:rgba(100,100,100,0.1);
	color:#FF9D29;
}
.no_border_bottom{
	border-bottom: none;
}
</style>