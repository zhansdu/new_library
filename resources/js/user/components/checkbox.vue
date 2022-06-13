<template>
	<div class="relative checkbox cursor-pointer" :class="{clicked:checked,disabled:disabled}" @click="onClick()" />
</template>
<script type="text/javascript">
// identication in sublime text 3
export default{
	model: {
		prop: 'checked',
		event: 'change'
	},
	props:{
		disabled:Boolean,
		checked:Boolean
	},
	data(){
		return{
			state:this.checked || false
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
	border-radius: 0.25em;
}

.clicked{
	border-color: #FF9D29;
	display: flex;
	justify-content: center;
	align-items: center;
}

.clicked:after{
	content: '';
	width:0.75em;
	height:0.75em;
	position: absolute;
	background-color: #FF9D29;
	border-radius: 0.125em;
}

.disabled{
	border-color: #B5BAC7;
}

.disabled:after{
	background-color: #B5BAC7;
}
</style>