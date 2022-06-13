<template>
	<div class="position-relative" tabindex="1" @focusout="shown=false">
		<div class="link d-flex align-items-center py-2 px-0 transition" :class="titleClasses" :style="titleStyles" @click="showDropdown()">{{$t(title)}}&nbsp;<CaretUp :class="{rotate:!dropup}"/></div>
		<div class="dropdown dropdown-right transition rounded-lg bg-white text-black shadow-sm pl-3" :class="[dropdownClasses,{dropup:dropup}]" :style="(shown ? dropdownShownStyles : '') + dropdownStyles">
			<div class="link border-bottom py-2 px-0" v-for="(item,index) in items" :key="index" :class="itemClasses" :style="itemStyles" @click="itemOnClick(item);shown=false;">{{$t(item.name !=undefined ? item.name : item)}}</div>
		</div>
	</div>
</template>
<script type="text/javascript">
// icon
import CaretUp from '../../common/assets/icons/CaretUp'

export default{
	props:{
		title:{
			type:[String,Number],
			default(){
				return 'choose'
			}
		},
		itemOnClick:Function,
		items:Array,
		titleClasses:[Array,String],
		titleStyles:{
			type:String,
			default(){
				return '';
			}
		},
		dropdownClasses:[Array,String],
		dropdownShownStyles:{
			type:String,
			default(){
				return 'max-height: 11.25em;'
			}
		},
		dropdownStyles:String,
		dropup:{type:Boolean,default(){return false}},
		itemClasses:[Array,String],
		itemStyles:{
			type:String,
			default(){
				return '';
			}
		},
		onCreated:{
			type:Function,
			default(){
				return ()=>{}
			}
		}
	},
	components:{CaretUp},
	data(){
		return{
			shown:false
		}
	},
	methods:{
		showDropdown(){
			this.shown=!this.shown;
		}
	},
	created(){
		this.onCreated();
	}
}	
</script>
<style scoped>
/*firefox only*/
div * {
	scrollbar-color: #c5c5c5 #f4f4f4;
}

/*crossbd-flexser*/
::-webkit-scrollbar {
	width: 0.5em;
	height: 0.5em;
}

::-webkit-scrollbar-thumb {
	background: #c5c5c5;
}

::-webkit-scrollbar-track {
	background: #f4f4f4;
}
.dropup{
	top:unset;
	bottom:100%;
}
</style>