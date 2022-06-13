<template>
	<div class="relative checkbox" :style="checkboxStyles" :class="[(round? 'round':'square'),{clicked:checked,disabled:disabled}]" @click="onClick()" />
</template>
<script type="text/javascript">
// identication in sublime text 3
export default{
	model: {
		prop: 'checked',
		event: 'change'
	},
	props:{
		disabled:{type:Boolean,default(){return false;}},
		checked:{type:Boolean,default(){return false;}},
		checkboxStyles:{type:String},
		round:{type:Boolean,default(){return false;}}
	},
	data(){
		return{
			state:this.checked
		}
	},
	methods:{
		onClick(){
			if(!this.disabled){
				this.state=!this.state;
				this.$emit('change',this.state);
			}
		}
	}
}

</script>
<style scoped>
.checkbox{
	min-width: 1.25em;
	max-width: 1.25em;
	height: 1.25em;
	border: 0.125em solid #B5BAC7;
	cursor: pointer;
}

.square{
	border-radius: 0.3125em;
	display: flex;
	justify-content: center;
	align-items: center;
	position: relative;
}

.round{
	border-radius: 50%;
}

.clicked{
	border-color: #FF9D29;
}

.round.clicked{
	border-width: 0.3125em;
}

.square.clicked::after {
	content: " ";
	width:75%;
	height:75%;
	border-radius: 0.125em;
	background-color: #FF9D29;
}

.disabled{
	border-color: #B5BAC7;
}
</style>